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
class Word2007 extends AbstractWriter implements WriterInterface
{
    /**
     * Content types values
     *
     * @var array
     */
    private $cTypes = array('default' => array(), 'override' => array());

    /**
     * Document relationship
     *
     * @var array
     */
    private $docRels = array();

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

            // Content types
            $this->cTypes['default'] = array(
                'rels' => 'application/vnd.openxmlformats-package.relationships+xml',
                'xml'  => 'application/xml',
            );

            // Add section media files
            $sectionMedia = Media::getElements('section');
            if (!empty($sectionMedia)) {
                $this->addFilesToPackage($objZip, $sectionMedia);
                foreach ($sectionMedia as $element) {
                    $this->docRels[] = $element;
                }
            }

            // Add header/footer media files & relations
            $this->addHeaderFooterMedia($objZip, 'header');
            $this->addHeaderFooterMedia($objZip, 'footer');

            // Add header/footer contents
            $overrides = array();
            $rID = Media::countElements('section') + 6; // @see Rels::writeDocRels for 6 first elements
            $sections = $this->phpWord->getSections();
            foreach ($sections as $section) {
                $this->addHeaderFooterContent($section, $objZip, 'header', $rID);
                $this->addHeaderFooterContent($section, $objZip, 'footer', $rID);
            }

            // Add footnotes media files, relations, and contents
            if (Footnote::countFootnoteElements() > 0) {
                $footnoteMedia = Media::getElements('footnote');
                $this->addFilesToPackage($objZip, $footnoteMedia);
                if (!empty($footnoteMedia)) {
                    $objZip->addFromString('word/_rels/footnotes.xml.rels', $this->getWriterPart('rels')->writeMediaRels($footnoteMedia));
                }
                $objZip->addFromString('word/footnotes.xml', $this->getWriterPart('footnotes')->writeFootnotes(Footnote::getFootnoteElements()));
                $this->cTypes['override']["/word/footnotes.xml"] = 'footnotes';
                $this->docRels[] = array('target' => 'footnotes.xml', 'type' => 'footnotes', 'rID' => ++$rID);
            }

            // Write dynamic files
            $objZip->addFromString('[Content_Types].xml', $this->getWriterPart('contenttypes')->writeContentTypes($this->cTypes));
            $objZip->addFromString('_rels/.rels', $this->getWriterPart('rels')->writeMainRels());
            $objZip->addFromString('docProps/app.xml', $this->getWriterPart('docprops')->writeDocPropsApp($this->phpWord));
            $objZip->addFromString('docProps/core.xml', $this->getWriterPart('docprops')->writeDocPropsCore($this->phpWord));
            $objZip->addFromString('word/_rels/document.xml.rels', $this->getWriterPart('rels')->writeDocRels($this->docRels));
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
     * Add section files to package
     *
     * @param mixed $objZip
     * @param mixed $elements
     */
    private function addFilesToPackage($objZip, $elements)
    {
        foreach ($elements as $element) {
            // Do not add link
            if ($element['type'] == 'link') {
                continue;
            }
            // Retrieve remote image
            if (isset($element['isMemImage']) && $element['isMemImage']) {
                $image = call_user_func($element['createFunction'], $element['source']);
                ob_start();
                call_user_func($element['imageFunction'], $image);
                $imageContents = ob_get_contents();
                ob_end_clean();
                $objZip->addFromString('word/' . $element['target'], $imageContents);
                imagedestroy($image);
            } else {
                $objZip->addFile($element['source'], 'word/' . $element['target']);
            }
            // Register content types
            if ($element['type'] == 'image') {
                $imageExtension = $element['imageExtension'];
                $imageType = $element['imageType'];
                if (!array_key_exists($imageExtension, $this->cTypes['default'])) {
                    $this->cTypes['default'][$imageExtension] = $imageType;
                }
            } else {
                if (!array_key_exists('bin', $this->cTypes['default'])) {
                    $this->cTypes['default']['bin'] = 'application/vnd.openxmlformats-officedocument.oleObject';
                }
            }
        }
    }

    /**
     * Add header/footer media files
     *
     * @param mixed $objZip
     * @param string $docPart
     */
    private function addHeaderFooterMedia($objZip, $docPart)
    {
        $elements = Media::getElements($docPart);
        if (!empty($elements)) {
            foreach ($elements as $file => $media) {
                if (count($media) > 0) {
                    if (!empty($media)) {
                        $this->addFilesToPackage($objZip, $media);
                    }
                    $objZip->addFromString("word/_rels/{$file}.xml.rels", $this->getWriterPart('rels')->writeMediaRels($media));
                }
            }
        }
    }

    /**
     * Add header/footer content
     *
     * @param \PhpOffice\PhpWord\Element\Section $section
     * @param mixed $objZip
     * @param string $elmType
     * @param integer $rID
     */
    private function addHeaderFooterContent(&$section, $objZip, $elmType, &$rID)
    {
        $getFunction = $elmType == 'header' ? 'getHeaders' : 'getFooters';
        $writeFunction = $elmType == 'header' ? 'writeHeader' : 'writeFooter';
        $elmCount = ($section->getSectionId() - 1) * 3;
        $elmObjects = $section->$getFunction();
        foreach ($elmObjects as $index => &$elmObject) {
            $elmCount++;
            $elmObject->setRelationId(++$rID);
            $elmFile = "{$elmType}{$elmCount}.xml";
            $objZip->addFromString("word/$elmFile", $this->getWriterPart($elmType)->$writeFunction($elmObject));
            $this->cTypes['override']["/word/$elmFile"] = $elmType;
            $this->docRels[] = array('target' => $elmFile, 'type' => $elmType, 'rID' => $rID);
        }
    }
}
