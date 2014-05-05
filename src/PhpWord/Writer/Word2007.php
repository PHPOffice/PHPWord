<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer;

use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\PhpWord;

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
    private $contentTypes = array('default' => array(), 'override' => array());

    /**
     * Document relationship
     *
     * @var array
     */
    private $relationships = array();

    /**
     * Create new Word2007 writer
     *
     * @param \PhpOffice\PhpWord\PhpWord
     */
    public function __construct(PhpWord $phpWord = null)
    {
        // Assign PhpWord
        $this->setPhpWord($phpWord);

        // Create parts
        $parts = array('ContentTypes', 'RelsMain', 'RelsDocument', 'DocPropsApp', 'DocPropsCore', 'Document', 'Styles',
            'Numbering', 'Settings', 'WebSettings', 'Header', 'Footer', 'Footnotes',
            'Endnotes', 'FontTable', 'Theme');
        foreach ($parts as $part) {
            $partName = strtolower($part);
            $partClass = 'PhpOffice\\PhpWord\\Writer\\Word2007\\Part\\' . $part;
            if (class_exists($partClass)) {
                $partObject = new $partClass();
                $partObject->setParentWriter($this);
                $this->writerParts[$partName] = $partObject;
            }
        }

        // Set package paths
        $this->mediaPaths = array('image' => 'word/media/', 'object' => 'word/embeddings/');
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
            $this->contentTypes['default'] = array(
                'rels' => 'application/vnd.openxmlformats-package.relationships+xml',
                'xml'  => 'application/xml',
            );

            // Add section media files
            $sectionMedia = Media::getElements('section');
            if (!empty($sectionMedia)) {
                $this->addFilesToPackage($objZip, $sectionMedia);
                $this->registerContentTypes($sectionMedia);
                foreach ($sectionMedia as $element) {
                    $this->relationships[] = $element;
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

            // Write parts
            $objZip->addFromString('[Content_Types].xml', $this->getWriterPart('contenttypes')->write());
            $objZip->addFromString('_rels/.rels', $this->getWriterPart('relsmain')->write());
            $objZip->addFromString('docProps/app.xml', $this->getWriterPart('docpropsapp')->write());
            $objZip->addFromString('docProps/core.xml', $this->getWriterPart('docpropscore')->write());
            $objZip->addFromString('word/_rels/document.xml.rels', $this->getWriterPart('relsdocument')->write());
            $objZip->addFromString('word/document.xml', $this->getWriterPart('document')->write());
            $objZip->addFromString('word/styles.xml', $this->getWriterPart('styles')->write());
            $objZip->addFromString('word/numbering.xml', $this->getWriterPart('numbering')->write());
            $objZip->addFromString('word/settings.xml', $this->getWriterPart('settings')->write());
            $objZip->addFromString('word/webSettings.xml', $this->getWriterPart('websettings')->write());
            $objZip->addFromString('word/fontTable.xml', $this->getWriterPart('fonttable')->write());
            $objZip->addFromString('word/theme/theme1.xml', $this->getWriterPart('theme')->write());

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
     * Get content types
     *
     * @return array
     */
    public function getContentTypes()
    {
        return $this->contentTypes;
    }

    /**
     * Get content types
     *
     * @return array
     */
    public function getRelationships()
    {
        return $this->relationships;
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
                        $this->registerContentTypes($media);
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
        foreach ($elmObjects as &$elmObject) {
            $elmCount++;
            $elmObject->setRelationId(++$rId);
            $elmFile = "{$elmType}{$elmCount}.xml";
            $objZip->addFromString("word/$elmFile", $this->getWriterPart($elmType)->$writeFunction($elmObject));
            $this->contentTypes['override']["/word/$elmFile"] = $elmType;
            $this->relationships[] = array('target' => $elmFile, 'type' => $elmType, 'rID' => $rId);
        }
    }

    /**
     * Add footnotes/endnotes
     *
     * @param mixed $objZip
     * @param integer $rId
     * @param string $noteType
     */
    private function addNotes($objZip, &$rId, $noteType = 'footnote')
    {
        $noteType = ($noteType == 'endnote') ? 'endnote' : 'footnote';
        $partName = "{$noteType}s";
        $method = 'get' . $partName;
        $collection = $this->phpWord->$method();

        // Add footnotes media files, relations, and contents
        if ($collection->countItems() > 0) {
            $media = Media::getElements($noteType);
            $this->addFilesToPackage($objZip, $media);
            $this->registerContentTypes($media);
            if (!empty($media)) {
                $objZip->addFromString("word/_rels/{$partName}.xml.rels", $this->getWriterPart('rels')->writeMediaRels($media));
            }
            $elements = $collection->getItems();
            $objZip->addFromString("word/{$partName}.xml", $this->getWriterPart($partName)->write($elements));
            $this->contentTypes['override']["/word/{$partName}.xml"] = $partName;
            $this->relationships[] = array('target' => "{$partName}.xml", 'type' => $partName, 'rID' => ++$rId);
        }
    }

    /**
     * Register content types for each media
     *
     * @param array $media
     */
    private function registerContentTypes($media)
    {
        foreach ($media as $medium) {
            $mediumType = $medium['type'];
            if ($mediumType == 'image') {
                $extension = $medium['imageExtension'];
                if (!array_key_exists($extension, $this->contentTypes['default'])) {
                    $this->contentTypes['default'][$extension] = $medium['imageType'];
                }
            } elseif ($mediumType == 'object') {
                if (!array_key_exists('bin', $this->contentTypes['default'])) {
                    $this->contentTypes['default']['bin'] = 'application/vnd.openxmlformats-officedocument.oleObject';
                }
            }
        }
    }
}
