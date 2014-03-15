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
 * @version    0.8.0
 */

/** PHPWord root directory */
if (!defined('PHPWORD_BASE_PATH')) {
    define('PHPWORD_BASE_PATH', dirname(__FILE__) . '/../../');
    require(PHPWORD_BASE_PATH . 'PHPWord/Autoloader.php');
}

/**
 * PHPWord_Reader_Word2007
 */
class PHPWord_Reader_Word2007 extends PHPWord_Reader_Abstract implements
    PHPWord_Reader_IReader
{
    /**
     * Create a new PHPWord_Reader_Word2007 instance
     */
    public function __construct()
    {
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
            throw new PHPWord_Exception(
                "Could not open {$pFilename} for reading! File does not exist."
            );
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
     * @param   bool        $removeNamespace
     */
    public function getFromZipArchive(
        $archive,
        $fileName = '',
        $removeNamespace = false
    ) {
        // Root-relative paths
        if (strpos($fileName, '//') !== false) {
            $fileName = substr($fileName, strpos($fileName, '//') + 1);
        }
        $fileName = PHPWord_Shared_File::realpath($fileName);

        // Apache POI fixes
        $contents = $archive->getFromName($fileName);
        if ($contents === false) {
            $contents = $archive->getFromName(substr($fileName, 1));
        }

        // Remove namespaces from elements and attributes name
        if ($removeNamespace) {
            $contents = preg_replace('~(</?|\s)w:~is', '$1', $contents);
        }

        return $contents;
    }


    /**
     * Loads PHPWord from file
     *
     * @param   string      $pFilename
     * @return  PHPWord|null
     */
    public function load($pFilename)
    {
        // Check if file exists and can be read
        if (!$this->canRead($pFilename)) {
            return;
        }

        // Initialisations
        $word = new PHPWord;
        $zip = new ZipArchive;
        $zip->open($pFilename);

        // Read properties and documents
        $rels = simplexml_load_string($this->getFromZipArchive($zip, "_rels/.rels"));
        foreach ($rels->Relationship as $rel) {
            switch ($rel["Type"]) {
                // Core properties
                case "http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties":
                    $xmlCore = simplexml_load_string($this->getFromZipArchive($zip, "{$rel['Target']}"));
                    if (is_object($xmlCore)) {
                        $xmlCore->registerXPathNamespace("dc", "http://purl.org/dc/elements/1.1/");
                        $xmlCore->registerXPathNamespace("dcterms", "http://purl.org/dc/terms/");
                        $xmlCore->registerXPathNamespace("cp", "http://schemas.openxmlformats.org/package/2006/metadata/core-properties");
                        $docProps = $word->getProperties();
                        $docProps->setCreator((string) self::arrayItem($xmlCore->xpath("dc:creator")));
                        $docProps->setLastModifiedBy((string) self::arrayItem($xmlCore->xpath("cp:lastModifiedBy")));
                        $docProps->setCreated(strtotime(self::arrayItem($xmlCore->xpath("dcterms:created"))));
                        $docProps->setModified(strtotime(self::arrayItem($xmlCore->xpath("dcterms:modified"))));
                        $docProps->setTitle((string) self::arrayItem($xmlCore->xpath("dc:title")));
                        $docProps->setDescription((string) self::arrayItem($xmlCore->xpath("dc:description")));
                        $docProps->setSubject((string) self::arrayItem($xmlCore->xpath("dc:subject")));
                        $docProps->setKeywords((string) self::arrayItem($xmlCore->xpath("cp:keywords")));
                        $docProps->setCategory((string) self::arrayItem($xmlCore->xpath("cp:category")));
                    }
                    break;
                // Extended properties
                case "http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties":
                    $xmlCore = simplexml_load_string($this->getFromZipArchive($zip, "{$rel['Target']}"));
                    if (is_object($xmlCore)) {
                        $docProps = $word->getProperties();
                        if (isset($xmlCore->Company)) {
                            $docProps->setCompany((string) $xmlCore->Company);
                        }
                        if (isset($xmlCore->Manager)) {
                            $docProps->setManager((string) $xmlCore->Manager);
                        }
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
                                $attributeValue = PHPWord_DocumentProperties::convertProperty($attributeValue, $attributeType);
                                $attributeType = PHPWord_DocumentProperties::convertPropertyType($attributeType);
                                $docProps->setCustomProperty($propertyName, $attributeValue, $attributeType);
                            }
                        }
                    }
                    break;
                // Document
                case "http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument":
                    $dir = dirname($rel["Target"]);
                    $archive = "$dir/_rels/" . basename($rel["Target"]) . ".rels";
                    $relsDoc = simplexml_load_string($this->getFromZipArchive($zip, $archive));
                    $relsDoc->registerXPathNamespace("rel", "http://schemas.openxmlformats.org/package/2006/relationships");
                    $xpath = self::arrayItem(
                        $relsDoc->xpath("rel:Relationship[@Type='http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles']")
                    );
                    $xmlDoc = simplexml_load_string($this->getFromZipArchive($zip, "{$rel['Target']}", true));
                    if (is_object($xmlDoc)) {
                        $section = $word->createSection();

                        foreach ($xmlDoc->body->children() as $elm) {
                            $elmName = $elm->getName();
                            if ($elmName == 'p') { // Paragraph/section
                                // Create new section if section setting found
                                if ($elm->pPr->sectPr) {
                                    $section->setSettings($this->loadSectionSettings($elm->pPr));
                                    $section = $word->createSection();
                                    continue;
                                }
                                // Has w:r? It's either text or textrun
                                if ($elm->r) {
                                    // w:r = 1? It's a plain paragraph
                                    if (count($elm->r) == 1) {
                                        $section->addText(
                                            $elm->r->t,
                                            $this->loadFontStyle($elm->r)
                                        );
                                    // w:r more than 1? It's a textrun
                                    } else {
                                        $textRun = $section->createTextRun();
                                        foreach ($elm->r as $r) {
                                            $textRun->addText(
                                                $r->t,
                                                $this->loadFontStyle($r)
                                            );
                                        }
                                    }
                                // No, it's a textbreak
                                } else {
                                    $section->addTextBreak();
                                }
                            } elseif ($elmName == 'sectPr') {
                                // Last section setting
                                $section->setSettings($this->loadSectionSettings($xmlDoc->body));
                            }
                        }
                    }
                    break;
            }
        }

        //  Read styles
        $docRels = simplexml_load_string($this->getFromZipArchive($zip, "word/_rels/document.xml.rels"));
        foreach ($docRels->Relationship as $rel) {
            switch ($rel["Type"]) {
                case "http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles":
                    $xmlStyle = simplexml_load_string($this->getFromZipArchive($zip, "word/{$rel['Target']}", true));
                    if (is_object($xmlStyle)) {
                        foreach ($xmlStyle->children() as $elm) {
                            if ($elm->getName() != 'style') {
                                continue;
                            }
                            $pStyle = null;
                            $fStyle = null;
                            $hasParagraphStyle = isset($elm->pPr);
                            $hasFontStyle = isset($elm->rPr);
                            $styleName = (string)$elm->name['val'];
                            if ($hasParagraphStyle) {
                                $pStyle = $this->loadParagraphStyle($elm);
                                if (!$hasFontStyle) {
                                    $word->addParagraphStyle($styleName, $pStyle);
                                }
                            }
                            if ($hasFontStyle) {
                                $fStyle = $this->loadFontStyle($elm);
                                $word->addFontStyle($styleName, $fStyle, $pStyle);
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
     * Load section settings from SimpleXMLElement
     *
     * @param   SimpleXMLElement    $elm
     * @return  array|string|null
     *
     * @todo    Implement gutter
     */
    private function loadSectionSettings($elm)
    {
        if ($xml = $elm->sectPr) {
            $setting = array();
            if ($xml->type) {
                $setting['breakType'] = (string)$xml->type['val'];
            }
            if ($xml->pgSz) {
                if (isset($xml->pgSz['w'])) {
                    $setting['pageSizeW'] = (int)$xml->pgSz['w'];
                }
                if (isset($xml->pgSz['h'])) {
                    $setting['pageSizeH'] = (int)$xml->pgSz['h'];
                }
                if (isset($xml->pgSz['orient'])) {
                    $setting['orientation'] = (string)$xml->pgSz['orient'];
                }
            }
            if ($xml->pgMar) {
                if (isset($xml->pgMar['top'])) {
                    $setting['topMargin'] = (int)$xml->pgMar['top'];
                }
                if (isset($xml->pgMar['left'])) {
                    $setting['leftMargin'] = (int)$xml->pgMar['left'];
                }
                if (isset($xml->pgMar['bottom'])) {
                    $setting['bottomMargin'] = (int)$xml->pgMar['bottom'];
                }
                if (isset($xml->pgMar['right'])) {
                    $setting['rightMargin'] = (int)$xml->pgMar['right'];
                }
                if (isset($xml->pgMar['header'])) {
                    $setting['headerHeight'] = (int)$xml->pgMar['header'];
                }
                if (isset($xml->pgMar['footer'])) {
                    $setting['footerHeight'] = (int)$xml->pgMar['footer'];
                }
                if (isset($xml->pgMar['gutter'])) {
                    // $setting['gutter'] = (int)$xml->pgMar['gutter'];
                }
            }
            if ($xml->cols) {
                if (isset($xml->cols['num'])) {
                    $setting['colsNum'] = (int)$xml->cols['num'];
                }
                if (isset($xml->cols['space'])) {
                    $setting['colsSpace'] = (int)$xml->cols['space'];
                }
            }
            return $setting;
        } else {
            return null;
        }
    }

    /**
     * Load paragraph style from SimpleXMLElement
     *
     * @param   SimpleXMLElement    $elm
     * @return  array|string|null
     */
    private function loadParagraphStyle($elm)
    {
        if ($xml = $elm->pPr) {
            if ($xml->pStyle) {
                return (string)$xml->pStyle['val'];
            }
            $style = array();
            if ($xml->jc) {
                $style['align'] = (string)$xml->jc['val'];
            }
            if ($xml->ind) {
                if (isset($xml->ind->left)) {
                    $style['indent'] = (int)$xml->ind->left;
                }
                if (isset($xml->ind->hanging)) {
                    $style['hanging'] = (int)$xml->ind->hanging;
                }
                if (isset($xml->ind->line)) {
                    $style['spacing'] = (int)$xml->ind->line;
                }
            }
            if ($xml->spacing) {
                if (isset($xml->spacing['after'])) {
                    $style['spaceAfter'] = (int)$xml->spacing['after'];
                }
                if (isset($xml->spacing['before'])) {
                    $style['spaceBefore'] = (int)$xml->spacing['before'];
                }
                if (isset($xml->spacing['line'])) {
                    $style['spacing'] = (int)$xml->spacing['line'];
                }
            }
            if ($xml->basedOn) {
                $style['basedOn'] = (string)$xml->basedOn['val'];
            }
            if ($xml->next) {
                $style['next'] = (string)$xml->next['val'];
            }
            if ($xml->widowControl) {
                $style['widowControl'] = false;
            }
            if ($xml->keepNext) {
                $style['keepNext'] = true;
            }
            if ($xml->keepLines) {
                $style['keepLines'] = true;
            }
            if ($xml->pageBreakBefore) {
                $style['pageBreakBefore'] = true;
            }
            return $style;
        } else {
            return null;
        }
    }

    /**
     * Load font style from SimpleXMLElement
     *
     * @param   SimpleXMLElement    $elm
     * @return  array|string|null
     */
    private function loadFontStyle($elm)
    {
        if ($xml = $elm->rPr) {
            if ($xml->rStyle) {
                return (string)$xml->rStyle['val'];
            }
            $style = array();
            if ($xml->rFonts) {
                $style['name'] = (string)$xml->rFonts['ascii'];
            }
            if ($xml->sz) {
                $style['size'] = (int)$xml->sz['val'] / 2;
            }
            if ($xml->color) {
                $style['color'] = (string)$xml->color['val'];
            }
            if ($xml->b) {
                $style['bold'] = true;
            }
            if ($xml->i) {
                $style['italic'] = true;
            }
            if ($xml->u) {
                $style['underline'] = (string)$xml->u['val'];
            }
            if ($xml->strike) {
                $style['strikethrough'] = true;
            }
            if ($xml->highlight) {
                $style['fgColor'] = (string)$xml->highlight['val'];
            }
            if ($xml->vertAlign) {
                if ($xml->vertAlign['val'] == 'superscript') {
                    $style['superScript'] = true;
                } else {
                    $style['subScript'] = true;
                }
            }
            return $style;
        } else {
            return null;
        }
    }

    /**
     * Get array item
     *
     * @param   array   $array
     * @param   mixed   $key
     * @return  mixed|null
     */
    private static function arrayItem($array, $key = 0)
    {
        return (isset($array[$key]) ? $array[$key] : null);
    }
}
