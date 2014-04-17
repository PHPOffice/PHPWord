<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Writer\Word2007\ContentTypes;
use PhpOffice\PhpWord\Writer\Word2007\DocProps;
use PhpOffice\PhpWord\Writer\Word2007\Document;
use PhpOffice\PhpWord\Writer\Word2007\Footer;
use PhpOffice\PhpWord\Writer\Word2007\Header;
use PhpOffice\PhpWord\Writer\Word2007\Notes;
use PhpOffice\PhpWord\Writer\Word2007\Numbering;
use PhpOffice\PhpWord\Writer\Word2007\Rels;
use PhpOffice\PhpWord\Writer\Word2007\Settings;
use PhpOffice\PhpWord\Writer\Word2007\Styles;
use PhpOffice\PhpWord\Writer\Word2007\WebSettings;

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
     * @param \PhpOffice\PhpWord\PhpWord
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
        $this->writerParts['numbering'] = new Numbering();
        $this->writerParts['settings'] = new Settings();
        $this->writerParts['websettings'] = new WebSettings();
        $this->writerParts['header'] = new Header();
        $this->writerParts['footer'] = new Footer();
        $this->writerParts['footnotes'] = new Notes();
        $this->writerParts['endnotes'] = new Notes();
        foreach ($this->writerParts as $writer) {
            $writer->setParentWriter($this);
        }
    }

    /**
     * Save document by name
     *
     * @param string $filename
     */
    public function save($filename = null)
    {
        if (!is_null($this->phpWord)) {
            $filename = $this->getTempFile($filename);
            $objZip = $this->getZipArchive($filename);

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
            $rId = Media::countElements('section') + 6; // @see Rels::writeDocRels for 6 first elements
            $sections = $this->phpWord->getSections();
            foreach ($sections as $section) {
                $this->addHeaderFooterContent($section, $objZip, 'header', $rId);
                $this->addHeaderFooterContent($section, $objZip, 'footer', $rId);
            }

            $this->addNotes($objZip, $rId, 'footnote');
            $this->addNotes($objZip, $rId, 'endnote');

            // Write dynamic files
            $objZip->addFromString('[Content_Types].xml', $this->getWriterPart('contenttypes')->writeContentTypes($this->cTypes));
            $objZip->addFromString('_rels/.rels', $this->getWriterPart('rels')->writeMainRels());
            $objZip->addFromString('docProps/app.xml', $this->getWriterPart('docprops')->writeDocPropsApp($this->phpWord));
            $objZip->addFromString('docProps/core.xml', $this->getWriterPart('docprops')->writeDocPropsCore($this->phpWord));
            $objZip->addFromString('word/_rels/document.xml.rels', $this->getWriterPart('rels')->writeDocRels($this->docRels));
            $objZip->addFromString('word/document.xml', $this->getWriterPart('document')->writeDocument($this->phpWord));
            $objZip->addFromString('word/styles.xml', $this->getWriterPart('styles')->writeStyles($this->phpWord));
            $objZip->addFromString('word/numbering.xml', $this->getWriterPart('numbering')->writeNumbering());
            $objZip->addFromString('word/settings.xml', $this->getWriterPart('settings')->writeSettings());
            $objZip->addFromString('word/webSettings.xml', $this->getWriterPart('websettings')->writeWebSettings());

            // Write static files
            $objZip->addFile(__DIR__ . '/../_staticDocParts/theme1.xml', 'word/theme/theme1.xml');
            $objZip->addFile(__DIR__ . '/../_staticDocParts/fontTable.xml', 'word/fontTable.xml');

            // Close file
            if ($objZip->close() === false) {
                throw new Exception("Could not close zip file $filename.");
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
            // Skip link
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
                $this->addFileToPackage($objZip, $element['source'], $element['target']);
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
     * Add file to package
     *
     * Get the actual source from an archive image
     *
     * @param mixed $objZip
     * @param string $source
     * @param string $target
     */
    private function addFileToPackage($objZip, $source, $target)
    {
        $isArchive = strpos($source, 'zip://') !== false;
        $actualSource = null;
        if ($isArchive) {
            $source = substr($source, 6);
            list($zipFilename, $imageFilename) = explode('#', $source);

            $zipClass = \PhpOffice\PhpWord\Settings::getZipClass();
            $zip = new $zipClass();
            if ($zip->open($zipFilename) !== false) {
                if ($zip->locateName($imageFilename)) {
                    $zip->extractTo($this->getTempDir(), $imageFilename);
                    $actualSource = $this->getTempDir() . DIRECTORY_SEPARATOR . $imageFilename;
                }
            }
            $zip->close();
        } else {
            $actualSource = $source;
        }

        if (!is_null($actualSource)) {
            $objZip->addFile($actualSource, 'word/' . $target);
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
                    $relsFile = "word/_rels/{$file}.xml.rels";
                    $objZip->addFromString($relsFile, $this->getWriterPart('rels')->writeMediaRels($media));
                }
            }
        }
    }

    /**
     * Add header/footer content
     *
     * @param mixed $objZip
     * @param string $elmType
     * @param integer $rId
     */
    private function addHeaderFooterContent(Section &$section, $objZip, $elmType, &$rId)
    {
        $getFunction = $elmType == 'header' ? 'getHeaders' : 'getFooters';
        $writeFunction = $elmType == 'header' ? 'writeHeader' : 'writeFooter';
        $elmCount = ($section->getSectionId() - 1) * 3;
        $elmObjects = $section->$getFunction();
        foreach ($elmObjects as $index => &$elmObject) {
            $elmCount++;
            $elmObject->setRelationId(++$rId);
            $elmFile = "{$elmType}{$elmCount}.xml";
            $objZip->addFromString("word/$elmFile", $this->getWriterPart($elmType)->$writeFunction($elmObject));
            $this->cTypes['override']["/word/$elmFile"] = $elmType;
            $this->docRels[] = array('target' => $elmFile, 'type' => $elmType, 'rID' => $rId);
        }
    }

    /**
     * Add footnotes/endnotes
     *
     * @param mixed $objZip
     * @param integer $rId
     * @param string $notesType
     */
    private function addNotes($objZip, &$rId, $notesType = 'footnote')
    {
        $notesType = ($notesType == 'endnote') ? 'endnote' : 'footnote';
        $notesTypes = "{$notesType}s";
        $collection = 'PhpOffice\\PhpWord\\' . ucfirst($notesTypes);
        $xmlFile = "{$notesTypes}.xml";
        $relsFile = "word/_rels/{$xmlFile}.rels";
        $xmlPath = "word/{$xmlFile}";

        // Add footnotes media files, relations, and contents
        if ($collection::countElements() > 0) {
            $media = Media::getElements($notesType);
            $elements = $collection::getElements();
            $this->addFilesToPackage($objZip, $media);
            if (!empty($media)) {
                $objZip->addFromString($relsFile, $this->getWriterPart('rels')->writeMediaRels($media));
            }
            $objZip->addFromString($xmlPath, $this->getWriterPart($notesTypes)->writeNotes($elements, $notesTypes));
            $this->cTypes['override']["/{$xmlPath}"] = $notesTypes;
            $this->docRels[] = array('target' => $xmlFile, 'type' => $notesTypes, 'rID' => ++$rId);
        }
    }
}
