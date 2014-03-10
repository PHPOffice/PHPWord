<?php
/**
 * PHPWord
 *
 * Copyright (c) 2014 PHPWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.7.0
 */


/** PHPWord root directory */
if (!defined('PHPWORD_BASE_PATH')) {
    define('PHPWORD_BASE_PATH', dirname(__FILE__) . '/../../');
    require(PHPWORD_BASE_PATH . 'PHPWord/Autoloader.php');
}

/**
 * PHPWord_Reader_Word2007
 */
class PHPWord_Reader_Word2007 extends PHPWord_Reader_Abstract implements PHPWord_Reader_IReader
{

    /**
     * Create a new PHPWord_Reader_Word2007 instance
     */
    public function __construct() {
    }

    /**
     * Can the current PHPWord_Reader_IReader read the file?
     *
     * @param   string      $pFilename
     * @return  bool
     */
    public function canRead($pFilename)
    {
        // Check if file exists
        if (!file_exists($pFilename)) {
            throw new PHPWord_Exception("Could not open " . $pFilename . " for reading! File does not exist.");
        }

        $return = false;
        // Load file
        $zip = new ZipArchive;
        if ($zip->open($pFilename) === true) {
            // check if it is an OOXML archive
            $rels = simplexml_load_string($this->getFromZipArchive($zip, "_rels/.rels"));
            if ($rels !== false) {
                foreach ($rels->Relationship as $rel) {
                    switch ($rel["Type"]) {
                        case "http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument":
                            if (basename($rel["Target"]) == 'document.xml') {
                                $return = true;
                            }
                            break;

                    }
                }
            }
            $zip->close();
        }

        return $return;
    }

    /**
     * Get from zip archive
     *
     * @param   ZipArchive  $archive
     * @param   string      $fileName
     */
    public function getFromZipArchive($archive, $fileName = '')
    {
        // Root-relative paths
        if (strpos($fileName, '//') !== false)
        {
            $fileName = substr($fileName, strpos($fileName, '//') + 1);
        }
        $fileName = PHPWord_Shared_File::realpath($fileName);

        // Apache POI fixes
        $contents = $archive->getFromName($fileName);
        if ($contents === false)
        {
            $contents = $archive->getFromName(substr($fileName, 1));
        }

        // Stupid hack for namespace
        if ($contents != '' && $fileName = 'word/document.xml') {
            $contents = preg_replace('~(</?)w:~is', '$1', $contents);
        }

        return $contents;
    }


    /**
     * Loads PHPWord from file
     *
     * @param   string      $pFilename
     * @return  PHPWord
     */
    public function load($pFilename)
    {
        // Check if file exists
        if (!file_exists($pFilename)) {
            throw new PHPWord_Exception("Could not open " . $pFilename . " for reading! File does not exist.");
        }

        // Initialisations
        $word = new PHPWord;
        $zip = new ZipArchive;
        $zip->open($pFilename);

        // Read relationships
        $rels = simplexml_load_string($this->getFromZipArchive($zip, "_rels/.rels"));
        foreach ($rels->Relationship as $rel) {
            switch ($rel["Type"]) {
                // Core properties
                case "http://schemas.openxmlformats.org/package/2006//relationships/metadata/core-properties":
                    $xmlCore = simplexml_load_string($this->getFromZipArchive($zip, "{$rel['Target']}"));
                    if (is_object($xmlCore)) {
                        $xmlCore->registerXPathNamespace("dc", "http://purl.org/dc/elements/1.1/");
                        $xmlCore->registerXPathNamespace("dcterms", "http://purl.org/dc/terms/");
                        $xmlCore->registerXPathNamespace("cp", "http://schemas.openxmlformats.org/package/2006//metadata/core-properties");
                        $docProps = $word->getProperties();
                        $docProps->setCreator((string) self::array_item($xmlCore->xpath("dc:creator")));
                        $docProps->setLastModifiedBy((string) self::array_item($xmlCore->xpath("cp:lastModifiedBy")));
                        $docProps->setCreated(strtotime(self::array_item($xmlCore->xpath("dcterms:created"))));
                        $docProps->setModified(strtotime(self::array_item($xmlCore->xpath("dcterms:modified"))));
                        $docProps->setTitle((string) self::array_item($xmlCore->xpath("dc:title")));
                        $docProps->setDescription((string) self::array_item($xmlCore->xpath("dc:description")));
                        $docProps->setSubject((string) self::array_item($xmlCore->xpath("dc:subject")));
                        $docProps->setKeywords((string) self::array_item($xmlCore->xpath("cp:keywords")));
                        $docProps->setCategory((string) self::array_item($xmlCore->xpath("cp:category")));
                    }
                    break;
                // Extended properties
                case "http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties":
                    $xmlCore = simplexml_load_string($this->getFromZipArchive($zip, "{$rel['Target']}"));
                    if (is_object($xmlCore)) {
                        $docProps = $word->getProperties();
                        if (isset($xmlCore->Company))
                            $docProps->setCompany((string) $xmlCore->Company);
                        if (isset($xmlCore->Manager))
                            $docProps->setManager((string) $xmlCore->Manager);
                    }
                    break;
                // Custom properties
                case "http://schemas.openxmlformats.org/officeDocument/2006/relationships/custom-properties":
                    $xmlCore = simplexml_load_string($this->getFromZipArchive($zip, "{$rel['Target']}"));
                    if (is_object($xmlCore)) {
                        $docProps = $word->getProperties();
                        foreach ($xmlCore as $xmlProperty) {
                            $cellDataOfficeAttributes = $xmlProperty->attributes();
                            if (isset($cellDataOfficeAttributes['name'])) {
                                $propertyName = (string) $cellDataOfficeAttributes['name'];
                                $cellDataOfficeChildren = $xmlProperty->children("http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes");
                                $attributeType = $cellDataOfficeChildren->getName();
                                $attributeValue = (string) $cellDataOfficeChildren->{$attributeType};
                                $attributeValue = PHPWord_DocumentProperties::convertProperty($attributeValue,$attributeType);
                                $attributeType = PHPWord_DocumentProperties::convertPropertyType($attributeType);
                                $docProps->setCustomProperty($propertyName,$attributeValue,$attributeType);
                            }
                        }
                    }
                    break;
                // Document
                case "http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument":
                    $dir = dirname($rel["Target"]);
                    $archive = "$dir/_rels/" . basename($rel["Target"]) . ".rels";
                    $relsDoc = simplexml_load_string($this->getFromZipArchive($zip, $archive));
                    $relsDoc->registerXPathNamespace("rel", "http://schemas.openxmlformats.org/package/2006//relationships");
                    $xpath = self::array_item($relsDoc->xpath("rel:Relationship[@Type='" .
                        "http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles']"));
                    $xmlDoc = simplexml_load_string($this->getFromZipArchive($zip, "{$rel['Target']}"));
                    if ($xmlDoc->body) {
                        $section = $word->createSection();
                        foreach ($xmlDoc->body->children() as $element) {
                            switch ($element->getName()) {
                                case 'p':
                                    if ($element->pPr->sectPr) {
                                        $section = $word->createSection();
                                        continue;
                                    }
                                    if ($element->r) {
                                        if (count($element->r) == 1) {
                                            $section->addText($element->r->t);
                                        } else {
                                            $textRun = $section->createTextRun();
                                            foreach ($element->r as $r) {
                                                $textRun->addText($r->t);
                                            }
                                        }
                                    } else {
                                        $section->addTextBreak();
                                    }
                                    break;
                            }
                        }
                    }
                    break;
            }
        }
        $zip->close();

        return $word;
    }

    /**
     * Get array item
     *
     * @param   array   $array
     * @param   mixed   $key
     * @return  mixed|null
     */
    private static function array_item($array, $key = 0) {
        return (isset($array[$key]) ? $array[$key] : null);
    }

}
