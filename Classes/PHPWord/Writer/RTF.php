<?php
/**
 * PHPWord
 *
 * Copyright (c) 2013 PHPWord
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
 * @copyright  Copyright (c) 2013 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    0.7.0
 */

/**
 * Class PHPWord_Writer_RTF
 */
class PHPWord_Writer_RTF implements PHPWord_Writer_IWriter
{
    /**
     * Private PHPWord
     *
     * @var PHPWord
     */
    private $_document;

    /**
     * Private unique PHPWord_Worksheet_BaseDrawing HashTable
     *
     * @var PHPWord_HashTable
     */
    private $_drawingHashTable;

    private $_colorTable;
    private $_fontTable;
    private $_lastParagraphStyle;

    /**
     * Create a new PHPWord_Writer_ODText
     *
     * @param    PHPWord $pPHPWord
     */
    public function __construct(PHPWord $pPHPWord = null)
    {
        // Assign PHPWord
        $this->setPHPWord($pPHPWord);

        // Set HashTable variables
        $this->_drawingHashTable = new PHPWord_HashTable();
    }

    /**
     * Save PHPWord to file
     *
     * @param    string $pFileName
     * @throws    Exception
     */
    public function save($pFilename = null)
    {
        if (!is_null($this->_document)) {
            // If $pFilename is php://output or php://stdout, make it a temporary file...
            $originalFilename = $pFilename;
            if (strtolower($pFilename) == 'php://output' || strtolower($pFilename) == 'php://stdout') {
                $pFilename = @tempnam('./', 'phppttmp');
                if ($pFilename == '') {
                    $pFilename = $originalFilename;
                }
            }

            $hFile = fopen($pFilename, 'w') or die("can't open file");
            fwrite($hFile, $this->_getData());
            fclose($hFile);

            // If a temporary file was used, copy it to the correct file stream
            if ($originalFilename != $pFilename) {
                if (copy($pFilename, $originalFilename) === false) {
                    throw new Exception("Could not copy temporary zip file $pFilename to $originalFilename.");
                }
                @unlink($pFilename);
            }

        } else {
            throw new Exception("PHPWord object unassigned.");
        }
    }

    /**
     * Get PHPWord object
     *
     * @return PHPWord
     * @throws Exception
     */
    public function getPHPWord()
    {
        if (!is_null($this->_document)) {
            return $this->_document;
        } else {
            throw new Exception("No PHPWord assigned.");
        }
    }

    /**
     * Get PHPWord object
     *
     * @param    PHPWord $pPHPWord PHPWord object
     * @throws    Exception
     * @return PHPWord_Writer_PowerPoint2007
     */
    public function setPHPWord(PHPWord $pPHPWord = null)
    {
        $this->_document = $pPHPWord;
        return $this;
    }

    /**
     * Get PHPWord_Worksheet_BaseDrawing HashTable
     *
     * @return PHPWord_HashTable
     */
    public function getDrawingHashTable()
    {
        return $this->_drawingHashTable;
    }

    private function _getData()
    {
        // PHPWord object : $this->_document
        $this->_fontTable = $this->_getDataFont();
        $this->_colorTable = $this->_getDataColor();

        $sRTFContent = '{\rtf1';
        // Set the default character set
        $sRTFContent .= '\ansi\ansicpg1252';
        // Set the default font (the first one)
        $sRTFContent .= '\deff0';
        // Set the default tab size (720 twips)
        $sRTFContent .= '\deftab720';
        // Set the font tbl group
        $sRTFContent .= '{\fonttbl';
        foreach ($this->_fontTable as $idx => $font) {
            $sRTFContent .= '{\f' . $idx . '\fnil\fcharset0 ' . $font . ';}';
        }
        $sRTFContent .= '}' . PHP_EOL;
        // Set the color tbl group
        $sRTFContent .= '{\colortbl ';
        foreach ($this->_colorTable as $idx => $color) {
            $arrColor = PHPWord_Shared_Drawing::htmlToRGB($color);
            $sRTFContent .= ';\red' . $arrColor[0] . '\green' . $arrColor[1] . '\blue' . $arrColor[2] . '';
        }
        $sRTFContent .= ';}' . PHP_EOL;
        // Set the generator
        $sRTFContent .= '{\*\generator PHPWord;}';
        // Set the view mode of the document
        $sRTFContent .= '\viewkind4';
        // Set the numberof bytes that follows a unicode character
        $sRTFContent .= '\uc1';
        // Resets to default paragraph properties.
        $sRTFContent .= '\pard';
        // No widow/orphan control
        $sRTFContent .= '\nowidctlpar';
        // Applies a language to a text run (1036 : French (France))
        $sRTFContent .= '\lang1036';
        // Point size (in half-points) above which to kern character pairs
        $sRTFContent .= '\kerning1';
        // Set the font size in half-points
        $sRTFContent .= '\fs20';
        // Body
        $sRTFContent .= $this->_getDataContent();


        $sRTFContent .= '}';

        return $sRTFContent;
    }

    private function _getDataFont()
    {
        $pPHPWord = $this->_document;

        $arrFonts = array();
        // Default font : Arial
        $arrFonts[] = 'Arial';
        // PHPWord object : $this->_document

        // Browse styles
        $styles = PHPWord_Style::getStyles();
        $numPStyles = 0;
        if (count($styles) > 0) {
            foreach ($styles as $styleName => $style) {
                // PHPWord_Style_Font
                if ($style instanceof PHPWord_Style_Font) {
                    if (in_array($style->getName(), $arrFonts) == FALSE) {
                        $arrFonts[] = $style->getName();
                    }
                }
            }
        }

        // Search all fonts used
        $_sections = $pPHPWord->getSections();
        $countSections = count($_sections);
        if ($countSections > 0) {
            $pSection = 0;

            foreach ($_sections as $section) {
                $pSection++;
                $_elements = $section->getElements();

                foreach ($_elements as $element) {
                    if ($element instanceof PHPWord_Section_Text) {
                        $fStyle = $element->getFontStyle();

                        if ($fStyle instanceof PHPWord_Style_Font) {
                            if (in_array($fStyle->getName(), $arrFonts) == FALSE) {
                                $arrFonts[] = $fStyle->getName();
                            }
                        }
                    }
                }
            }
        }

        return $arrFonts;
    }

    private function _getDataColor()
    {
        $pPHPWord = $this->_document;

        $arrColors = array();
        // PHPWord object : $this->_document

        // Browse styles
        $styles = PHPWord_Style::getStyles();
        $numPStyles = 0;
        if (count($styles) > 0) {
            foreach ($styles as $styleName => $style) {
                // PHPWord_Style_Font
                if ($style instanceof PHPWord_Style_Font) {
                    $color = $style->getColor();
                    $fgcolor = $style->getFgColor();
                    if (in_array($color, $arrColors) == FALSE && $color != '000000' && !empty($color)) {
                        $arrColors[] = $color;
                    }
                    if (in_array($fgcolor, $arrColors) == FALSE && $fgcolor != '000000' && !empty($fgcolor)) {
                        $arrColors[] = $fgcolor;
                    }
                }
            }
        }

        // Search all fonts used
        $_sections = $pPHPWord->getSections();
        $countSections = count($_sections);
        if ($countSections > 0) {
            $pSection = 0;

            foreach ($_sections as $section) {
                $pSection++;
                $_elements = $section->getElements();

                foreach ($_elements as $element) {
                    if ($element instanceof PHPWord_Section_Text) {
                        $fStyle = $element->getFontStyle();

                        if ($fStyle instanceof PHPWord_Style_Font) {
                            if (in_array($fStyle->getColor(), $arrColors) == FALSE) {
                                $arrColors[] = $fStyle->getColor();
                            }
                            if (in_array($fStyle->getFgColor(), $arrColors) == FALSE) {
                                $arrColors[] = $fStyle->getFgColor();
                            }
                        }
                    }
                }
            }
        }

        return $arrColors;
    }

    private function _getDataContent()
    {
        $pPHPWord = $this->_document;
        $sRTFBody = '';

        $_sections = $pPHPWord->getSections();
        $countSections = count($_sections);
        $pSection = 0;

        if ($countSections > 0) {
            foreach ($_sections as $section) {
                $pSection++;
                $_elements = $section->getElements();
                foreach ($_elements as $element) {
                    if ($element instanceof PHPWord_Section_Text) {
                        $sRTFBody .= $this->_getDataContent_writeText($element);
                    } /* elseif($element instanceof PHPWord_Section_TextRun) {
					$this->_writeTextRun($objWriter, $element);
					} elseif($element instanceof PHPWord_Section_Link) {
					$this->_writeLink($objWriter, $element);
					} elseif($element instanceof PHPWord_Section_Title) {
					$this->_writeTitle($objWriter, $element);
					}*/
                    elseif ($element instanceof PHPWord_Section_TextBreak) {
                        $sRTFBody .= $this->_getDataContent_writeTextBreak();
                    } /* elseif($element instanceof PHPWord_Section_PageBreak) {
					$this->_writePageBreak($objWriter);
					} elseif($element instanceof PHPWord_Section_Table) {
					$this->_writeTable($objWriter, $element);
					} elseif($element instanceof PHPWord_Section_ListItem) {
					$this->_writeListItem($objWriter, $element);
					} elseif($element instanceof PHPWord_Section_Image ||
					$element instanceof PHPWord_Section_MemoryImage) {
					$this->_writeImage($objWriter, $element);
					} elseif($element instanceof PHPWord_Section_Object) {
					$this->_writeObject($objWriter, $element);
					} elseif($element instanceof PHPWord_TOC) {
					$this->_writeTOC($objWriter);
					}*/
                    else {
                        print_r($element);
                        echo '<br />';
                    }
                }
            }
        }
        return $sRTFBody;
    }

    private function _getDataContent_writeText(PHPWord_Section_Text $text)
    {
        $sRTFText = '';

        $styleFont = $text->getFontStyle();
        $SfIsObject = ($styleFont instanceof PHPWord_Style_Font) ? true : false;
        if (!$SfIsObject) {
            $styleFont = PHPWord_Style::getStyle($styleFont);
        }

        $styleParagraph = $text->getParagraphStyle();
        $SpIsObject = ($styleParagraph instanceof PHPWord_Style_Paragraph) ? true : false;
        if (!$SpIsObject) {
            $styleParagraph = PHPWord_Style::getStyle($styleParagraph);
        }

        if ($styleParagraph) {
            if ($this->_lastParagraphStyle != $text->getParagraphStyle()) {
                $sRTFText .= '\pard\nowidctlpar';
                if ($styleParagraph->getSpaceAfter() != null) {
                    $sRTFText .= '\sa' . $styleParagraph->getSpaceAfter();
                }
                if ($styleParagraph->getAlign() != null) {
                    if ($styleParagraph->getAlign() == 'center') {
                        $sRTFText .= '\qc';
                    }
                }
                $this->_lastParagraphStyle = $text->getParagraphStyle();
            } else {
                $this->_lastParagraphStyle = '';
            }
        } else {
            $this->_lastParagraphStyle = '';
        }

        if ($styleFont) {
            if ($styleFont->getColor() != null) {
                $idxColor = array_search($styleFont->getColor(), $this->_colorTable);
                if ($idxColor !== FALSE) {
                    $sRTFText .= '\cf' . ($idxColor + 1);
                }
            } else {
                $sRTFText .= '\cf0';
            }
            if ($styleFont->getName() != null) {
                $idxFont = array_search($styleFont->getName(), $this->_fontTable);
                if ($idxFont !== FALSE) {
                    $sRTFText .= '\f' . $idxFont;
                }
            } else {
                $sRTFText .= '\f0';
            }
            if ($styleFont->getBold()) {
                $sRTFText .= '\b';
            }
            if ($styleFont->getBold()) {
                $sRTFText .= '\i';
            }
            if ($styleFont->getSize()) {
                $sRTFText .= '\fs' . $styleFont->getSize();
            }
        }
        if ($this->_lastParagraphStyle != '' || $styleFont) {
            $sRTFText .= ' ';
        }
        $sRTFText .= $text->getText();

        if ($styleFont) {
            $sRTFText .= '\cf0';
            $sRTFText .= '\f0';

            if ($styleFont->getBold()) {
                $sRTFText .= '\b0';
            }
            if ($styleFont->getItalic()) {
                $sRTFText .= '\i0';
            }
            if ($styleFont->getSize()) {
                $sRTFText .= '\fs20';
            }
        }

        $sRTFText .= '\par' . PHP_EOL;
        return $sRTFText;
    }

    private function _getDataContent_writeTextBreak()
    {
        $this->_lastParagraphStyle = '';

        return '\par' . PHP_EOL;
    }


}