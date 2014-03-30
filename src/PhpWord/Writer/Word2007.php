<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer;

use PhpOffice\PhpWord\Exceptions\Exception;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Footnote;
use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\Writer\Word2007\ContentTypes;
use PhpOffice\PhpWord\Writer\Word2007\DocProps;
use PhpOffice\PhpWord\Writer\Word2007\Document;
use PhpOffice\PhpWord\Writer\Word2007\DocumentRels;
use PhpOffice\PhpWord\Writer\Word2007\Footer;
use PhpOffice\PhpWord\Writer\Word2007\Footnotes;
use PhpOffice\PhpWord\Writer\Word2007\FootnotesRels;
use PhpOffice\PhpWord\Writer\Word2007\Header;
use PhpOffice\PhpWord\Writer\Word2007\Rels;
use PhpOffice\PhpWord\Writer\Word2007\Styles;

/**
 * Word2007 writer
 */
class Word2007 extends Writer implements IWriter
{
    /**
     * Types of images
     *
     * @var array
     */
    private $imageTypes = array();

    /**
     * Types of objects
     *
     * @var array
     */
    private $objectTypes = array();

    /**
     * Create new Word2007 writer
     *
     * @param PhpOffice\PhpWord\PhpWord
     */
    public function __construct(PhpWord $phpWord = null)
    {
        // Assign PhpWord
        $this->setPhpWord($phpWord);

        // Set writer parts
        $this->writerParts['contenttypes'] = new ContentTypes();
        $this->writerParts['rels'] = new Rels();
        $this->writerParts['docprops'] = new DocProps();
        $this->writerParts['documentrels'] = new DocumentRels();
        $this->writerParts['document'] = new Document();
        $this->writerParts['styles'] = new Styles();
        $this->writerParts['header'] = new Header();
        $this->writerParts['footer'] = new Footer();
        $this->writerParts['footnotes'] = new Footnotes();
        $this->writerParts['footnotesrels'] = new FootnotesRels();
        foreach ($this->writerParts as $writer) {
            $writer->setParentWriter($this);
        }
    }

    /**
     * Save document by name
     *
     * @param string $pFilename
     */
    public function save($pFilename = null)
    {
        if (!is_null($this->phpWord)) {
            $pFilename = $this->getTempFile($pFilename);

            // Create new ZIP file and open it for writing
            $objZip = new \ZipArchive();

            // Try opening the ZIP file
            if ($objZip->open($pFilename, \ZipArchive::OVERWRITE) !== true) {
                if ($objZip->open($pFilename, \ZipArchive::CREATE) !== true) {
                    throw new Exception("Could not open " . $pFilename . " for writing.");
                }
            }

            $sectionElements = array();
            $_secElements = Media::getSectionMediaElements();
            foreach ($_secElements as $element) { // loop through section media elements
                if ($element['type'] != 'hyperlink') {
                    $this->addFileToPackage($objZip, $element);
                }
                $sectionElements[] = $element;
            }

            $_hdrElements = Media::getHeaderMediaElements();
            foreach ($_hdrElements as $_headerFile => $_hdrMedia) { // loop through headers
                if (count($_hdrMedia) > 0) {
                    $objZip->addFromString('word/_rels/' . $_headerFile . '.xml.rels', $this->getWriterPart('documentrels')->writeHeaderFooterRels($_hdrMedia));
                    foreach ($_hdrMedia as $element) { // loop through header media elements
                        $this->addFileToPackage($objZip, $element);
                    }
                }
            }

            $_ftrElements = Media::getFooterMediaElements();
            foreach ($_ftrElements as $_footerFile => $_ftrMedia) { // loop through footers
                if (count($_ftrMedia) > 0) {
                    $objZip->addFromString('word/_rels/' . $_footerFile . '.xml.rels', $this->getWriterPart('documentrels')->writeHeaderFooterRels($_ftrMedia));
                    foreach ($_ftrMedia as $element) { // loop through footers media elements
                        $this->addFileToPackage($objZip, $element);
                    }
                }
            }

            $footnoteLinks = array();
            $_footnoteElements = Footnote::getFootnoteLinkElements();
            // loop through footnote link elements
            foreach ($_footnoteElements as $element) {
                $footnoteLinks[] = $element;
            }

            $_cHdrs = 0;
            $_cFtrs = 0;
            $rID = Media::countSectionMediaElements() + 6;
            $_sections = $this->phpWord->getSections();

            $footers = array();
            foreach ($_sections as $section) {
                $_headers = $section->getHeaders();
                foreach ($_headers as $index => &$_header) {
                    $_cHdrs++;
                    $_header->setRelationId(++$rID);
                    $_headerFile = 'header' . $_cHdrs . '.xml';
                    $sectionElements[] = array('target' => $_headerFile, 'type' => 'header', 'rID' => $rID);
                    $objZip->addFromString('word/' . $_headerFile, $this->getWriterPart('header')->writeHeader($_header));
                }

                $_footer = $section->getFooter();
                $footers[++$_cFtrs] = $_footer;
                if (!is_null($_footer)) {
                    $_footer->setRelationId(++$rID);
                    $_footerCount = $_footer->getFooterCount();
                    $_footerFile = 'footer' . $_footerCount . '.xml';
                    $sectionElements[] = array('target' => $_footerFile, 'type' => 'footer', 'rID' => $rID);
                    $objZip->addFromString('word/' . $_footerFile, $this->getWriterPart('footer')->writeFooter($_footer));
                }
            }

            if (Footnote::countFootnoteElements() > 0) {
                $_allFootnotesCollection = Footnote::getFootnoteElements();
                $_footnoteFile = 'footnotes.xml';
                $sectionElements[] = array('target'=>$_footnoteFile, 'type'=>'footnotes', 'rID'=>++$rID);
                $objZip->addFromString('word/'.$_footnoteFile, $this->getWriterPart('footnotes')->writeFootnotes($_allFootnotesCollection));
                if (count($footnoteLinks) > 0) {
                    $objZip->addFromString('word/_rels/footnotes.xml.rels', $this->getWriterPart('footnotesrels')->writeFootnotesRels($footnoteLinks));
                }
            }

            // build docx file
            // Write dynamic files
            $objZip->addFromString(
                '[Content_Types].xml',
                $this->getWriterPart('contenttypes')->writeContentTypes(
                    $this->imageTypes,
                    $this->objectTypes,
                    $_cHdrs,
                    $footers
                )
            );
            $objZip->addFromString('_rels/.rels', $this->getWriterPart('rels')->writeRelationships($this->phpWord));
            $objZip->addFromString('docProps/app.xml', $this->getWriterPart('docprops')->writeDocPropsApp($this->phpWord));
            $objZip->addFromString('docProps/core.xml', $this->getWriterPart('docprops')->writeDocPropsCore($this->phpWord));
            $objZip->addFromString('word/document.xml', $this->getWriterPart('document')->writeDocument($this->phpWord));
            $objZip->addFromString('word/_rels/document.xml.rels', $this->getWriterPart('documentrels')->writeDocumentRels($sectionElements));
            $objZip->addFromString('word/styles.xml', $this->getWriterPart('styles')->writeStyles($this->phpWord));

            // Write static files
            $objZip->addFile(__DIR__ . '/../_staticDocParts/numbering.xml', 'word/numbering.xml');
            $objZip->addFile(__DIR__ . '/../_staticDocParts/settings.xml', 'word/settings.xml');
            $objZip->addFile(__DIR__ . '/../_staticDocParts/theme1.xml', 'word/theme/theme1.xml');
            $objZip->addFile(__DIR__ . '/../_staticDocParts/webSettings.xml', 'word/webSettings.xml');
            $objZip->addFile(__DIR__ . '/../_staticDocParts/fontTable.xml', 'word/fontTable.xml');

            // Close file
            if ($objZip->close() === false) {
                throw new Exception("Could not close zip file $pFilename.");
            }

            $this->cleanupTempFile();
        } else {
            throw new Exception("PhpWord object unassigned.");
        }
    }

    /**
     * Check content types
     *
     * @param string $src
     */
    private function checkContentTypes($src)
    {
        $extension = null;
        if (stripos(strrev($src), strrev('.php')) === 0) {
            $extension = 'php';
        } else {
            if (function_exists('exif_imagetype')) {
                $imageType = exif_imagetype($src);
            } else {
                $tmp = getimagesize($src);
                $imageType = $tmp[2];
            }
            if ($imageType === \IMAGETYPE_JPEG) {
                $extension = 'jpg';
            } elseif ($imageType === \IMAGETYPE_GIF) {
                $extension = 'gif';
            } elseif ($imageType === \IMAGETYPE_PNG) {
                $extension = 'png';
            } elseif ($imageType === \IMAGETYPE_BMP) {
                $extension = 'bmp';
            } elseif ($imageType === \IMAGETYPE_TIFF_II || $imageType === \IMAGETYPE_TIFF_MM) {
                $extension = 'tif';
            }
        }

        if (isset($extension)) {
            $imageData = getimagesize($src);
            $imageType = image_type_to_mime_type($imageData[2]);
            $imageExtension = str_replace('.', '', image_type_to_extension($imageData[2]));
            if ($imageExtension === 'jpeg') {
                $imageExtension = 'jpg';
            }
            if (!in_array($imageType, $this->imageTypes)) {
                $this->imageTypes[$imageExtension] = $imageType;
            }
        } else {
            if (!in_array($extension, $this->objectTypes)) {
                $this->objectTypes[] = $extension;
            }
        }
    }

    /**
     * Check content types
     *
     * @param mixed $objZip
     * @param mixed $element
     */
    private function addFileToPackage($objZip, $element)
    {
        if (isset($element['isMemImage']) && $element['isMemImage']) {
            $image = call_user_func($element['createfunction'], $element['source']);
            ob_start();
            call_user_func($element['imagefunction'], $image);
            $imageContents = ob_get_contents();
            ob_end_clean();
            $objZip->addFromString('word/' . $element['target'], $imageContents);
            imagedestroy($image);

            $this->checkContentTypes($element['source']);
        } else {
            $objZip->addFile($element['source'], 'word/' . $element['target']);
            $this->checkContentTypes($element['source']);
        }
    }
}
