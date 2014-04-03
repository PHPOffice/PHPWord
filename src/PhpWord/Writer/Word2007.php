<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Footnote;
use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\Word2007\ContentTypes;
use PhpOffice\PhpWord\Writer\Word2007\Rels;
use PhpOffice\PhpWord\Writer\Word2007\DocProps;
use PhpOffice\PhpWord\Writer\Word2007\Document;
use PhpOffice\PhpWord\Writer\Word2007\Footer;
use PhpOffice\PhpWord\Writer\Word2007\Footnotes;
use PhpOffice\PhpWord\Writer\Word2007\Header;
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
     * @param PhpWord
     */
    public function __construct(PhpWord $phpWord = null)
    {
        // Assign PhpWord
        $this->setPhpWord($phpWord);

        // Set writer parts
        $this->writerParts['contenttypes'] = new ContentTypes();
        $this->writerParts['rels'] = new Rels();
        $this->writerParts['docprops'] = new DocProps();
        $this->writerParts['document'] = new Document();
        $this->writerParts['styles'] = new Styles();
        $this->writerParts['header'] = new Header();
        $this->writerParts['footer'] = new Footer();
        $this->writerParts['footnotes'] = new Footnotes();
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
            $zipClass = Settings::getZipClass();
            $objZip = new $zipClass();

            // Retrieve OVERWRITE and CREATE constants from the instantiated zip class
            // This method of accessing constant values from a dynamic class should work with all appropriate versions of PHP
            $ro = new \ReflectionObject($objZip);
            $zipOverWrite = $ro->getConstant('OVERWRITE');
            $zipCreate = $ro->getConstant('CREATE');

            // Remove any existing file
            if (file_exists($pFilename)) {
                unlink($pFilename);
            }

            // Try opening the ZIP file
            if ($objZip->open($pFilename, $zipOverWrite) !== true) {
                if ($objZip->open($pFilename, $zipCreate) !== true) {
                    throw new Exception("Could not open " . $pFilename . " for writing.");
                }
            }

            // Add section media files
            $sectionElements = array();

            $secElements = Media::getMediaElements('section');
            if (!empty($secElements)) {
                $this->addFilesToPackage($objZip, $secElements);
                foreach ($secElements as $element) {
                    $sectionElements[] = $element;
                }
            }

            // Add header/footer media files & relations
            $this->addHeaderFooterMedia($objZip, 'header');
            $this->addHeaderFooterMedia($objZip, 'footer');

            // Add header/footer contents
            $cHdrs = 0;
            $cFtrs = 0;
            $rID = Media::countMediaElements('section') + 6; // @see Rels::writeDocRels for 6 first elements
            $sections = $this->phpWord->getSections();
            $footers = array();
            foreach ($sections as $section) {
                $headers = $section->getHeaders();
                if (!empty($headers)) {
                    foreach ($headers as $index => &$header) {
                        $cHdrs++;
                        $header->setRelationId(++$rID);
                        $hdrFile = "header{$cHdrs}.xml";
                        $sectionElements[] = array('target' => $hdrFile, 'type' => 'header', 'rID' => $rID);
                        $objZip->addFromString(
                            "word/{$hdrFile}",
                            $this->getWriterPart('header')->writeHeader($header)
                        );
                    }
                }
                $footer = $section->getFooter();
                $footers[++$cFtrs] = $footer;
                if (!is_null($footer)) {
                    $footer->setRelationId(++$rID);
                    $footerCount = $footer->getSectionId();
                    $ftrFile = "footer{$footerCount}.xml";
                    $sectionElements[] = array('target' => $ftrFile, 'type' => 'footer', 'rID' => $rID);
                    $objZip->addFromString(
                        "word/{$ftrFile}",
                        $this->getWriterPart('footer')->writeFooter($footer)
                    );
                }
            }

            // Add footnotes media files, relations, and contents
            if (Footnote::countFootnoteElements() > 0) {
                $sectionElements[] = array('target' => 'footnotes.xml', 'type' => 'footnotes', 'rID' => ++$rID);
                $footnoteMedia = Media::getMediaElements('footnote');
                $this->addFilesToPackage($objZip, $footnoteMedia);
                if (!empty($footnoteMedia)) {
                    $objZip->addFromString(
                        'word/_rels/footnotes.xml.rels',
                        $this->getWriterPart('rels')->writeMediaRels($footnoteMedia)
                    );
                }
                $objZip->addFromString(
                    'word/footnotes.xml',
                    $this->getWriterPart('footnotes')->writeFootnotes(Footnote::getFootnoteElements())
                );
            }

            // Write dynamic files
            $objZip->addFromString(
                '[Content_Types].xml',
                $this->getWriterPart('contenttypes')->writeContentTypes(
                    $this->imageTypes,
                    $this->objectTypes,
                    $cHdrs,
                    $footers
                )
            );
            $objZip->addFromString('_rels/.rels', $this->getWriterPart('rels')->writeMainRels());
            $objZip->addFromString('docProps/app.xml', $this->getWriterPart('docprops')->writeDocPropsApp($this->phpWord));
            $objZip->addFromString('docProps/core.xml', $this->getWriterPart('docprops')->writeDocPropsCore($this->phpWord));
            $objZip->addFromString('word/_rels/document.xml.rels', $this->getWriterPart('rels')->writeDocRels($sectionElements));
            $objZip->addFromString('word/document.xml', $this->getWriterPart('document')->writeDocument($this->phpWord));
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
    private function addFilesToPackage($objZip, $elements)
    {
        foreach ($elements as $element) {
            if ($element['type'] == 'link') {
                continue;
            }
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

    /**
     * Add header/footer media elements
     */
    private function addHeaderFooterMedia($objZip, $docPart)
    {
        $elements = Media::getMediaElements($docPart);
        if (!empty($elements)) {
            foreach ($elements as $file => $media) {
                if (count($media) > 0) {
                    $objZip->addFromString(
                        'word/_rels/' . $file . '.xml.rels',
                        $this->getWriterPart('rels')->writeMediaRels($media)
                    );
                    if (!empty($media)) {
                        $this->addFilesToPackage($objZip, $media);
                    }
                }
            }
        }
    }
}
