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
            fwrite($hFile, $this->getData());
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
     * @return PHPWord_Writer_RTF
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

    private function getData()
    {
        // PHPWord object : $this->_document
        $this->_fontTable = $this->getDataFont();
        $this->_colorTable = $this->getDataColor();

        $sRTFContent = '{\rtf1';
        // Set the default character set
        $sRTFContent .= '\ansi\ansicpg1252';
        // Set the default font (the first one)
        $sRTFContent .= '\deff0';
        // Set the default tab size (720 twips)
        $sRTFContent .= '\deftab720';
        $sRTFContent .= PHP_EOL;
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
        $sRTFContent .= '{\*\generator PHPWord;}' . PHP_EOL;
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
        $sRTFContent .= '\fs' . (PHPWord::DEFAULT_FONT_SIZE * 2);
        $sRTFContent .= PHP_EOL;
        // Body
        $sRTFContent .= $this->getDataContent();


        $sRTFContent .= '}';

        return $sRTFContent;
    }

    private function getDataFont()
    {
        $pPHPWord = $this->_document;

        $arrFonts = array();
        // Default font : PHPWord::DEFAULT_FONT_NAME
        $arrFonts[] = PHPWord::DEFAULT_FONT_NAME;
        // PHPWord object : $this->_document

        // Browse styles
        $styles = PHPWord_Style::getStyles();
        $numPStyles = 0;
        if (count($styles) > 0) {
            foreach ($styles as $styleName => $style) {
                // PHPWord_Style_Font
                if ($style instanceof PHPWord_Style_Font) {
                    if (in_array($style->getName(), $arrFonts) == false) {
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
                            if (in_array($fStyle->getName(), $arrFonts) == false) {
                                $arrFonts[] = $fStyle->getName();
                            }
                        }
                    }
                }
            }
        }

        return $arrFonts;
    }

    private function getDataColor()
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
                    if (in_array($color, $arrColors) == false && $color != PHPWord::DEFAULT_FONT_COLOR && !empty($color)) {
                        $arrColors[] = $color;
                    }
                    if (in_array($fgcolor, $arrColors) == false && $fgcolor != PHPWord::DEFAULT_FONT_COLOR && !empty($fgcolor)) {
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
                            if (in_array($fStyle->getColor(), $arrColors) == false) {
                                $arrColors[] = $fStyle->getColor();
                            }
                            if (in_array($fStyle->getFgColor(), $arrColors) == false) {
                                $arrColors[] = $fStyle->getFgColor();
                            }
                        }
                    }
                }
            }
        }

        return $arrColors;
    }

    private function getDataContent()
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
                        $sRTFBody .= $this->getDataContentText($element);
                    } elseif ($element instanceof PHPWord_Section_TextBreak) {
                        $sRTFBody .= $this->getDataContentTextBreak();
                    } elseif ($element instanceof PHPWord_Section_TextRun) {
                        $sRTFBody .= $this->getDataContentTextRun($element);
                    } elseif ($element instanceof PHPWord_Section_Link) {
                        $sRTFBody .= $this->getDataContentUnsupportedElement('Link');
                    } elseif ($element instanceof PHPWord_Section_Title) {
                        $sRTFBody .= $this->getDataContentUnsupportedElement('Title');
                    } elseif ($element instanceof PHPWord_Section_PageBreak) {
                        $sRTFBody .= $this->getDataContentUnsupportedElement('Page Break');
                    } elseif ($element instanceof PHPWord_Section_Table) {
                        $sRTFBody .= $this->getDataContentUnsupportedElement('Table');
                    } elseif ($element instanceof PHPWord_Section_ListItem) {
                        $sRTFBody .= $this->getDataContentUnsupportedElement('List Item');
                    } elseif ($element instanceof PHPWord_Section_Image ||
                        $element instanceof PHPWord_Section_MemoryImage) {
                        $sRTFBody .= $this->getDataContentUnsupportedElement('Image');
                    } elseif ($element instanceof PHPWord_Section_Object) {
                        $sRTFBody .= $this->getDataContentUnsupportedElement('Object');
                    } elseif ($element instanceof PHPWord_TOC) {
                        $sRTFBody .= $this->getDataContentUnsupportedElement('TOC');
                    } else {
                        $sRTFBody .= $this->getDataContentUnsupportedElement('Other');
                    }
                }
            }
        }
        return $sRTFBody;
    }

    /**
     * Get text
     */
    private function getDataContentText(PHPWord_Section_Text $text, $withoutP = false)
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

        if ($styleParagraph && !$withoutP) {
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

        if ($styleFont instanceof PHPWord_Style_Font) {
            if ($styleFont->getColor() != null) {
                $idxColor = array_search($styleFont->getColor(), $this->_colorTable);
                if ($idxColor !== false) {
                    $sRTFText .= '\cf' . ($idxColor + 1);
                }
            } else {
                $sRTFText .= '\cf0';
            }
            if ($styleFont->getName() != null) {
                $idxFont = array_search($styleFont->getName(), $this->_fontTable);
                if ($idxFont !== false) {
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
                $sRTFText .= '\fs' . ($styleFont->getSize() * 2);
            }
        }
        if ($this->_lastParagraphStyle != '' || $styleFont) {
            $sRTFText .= ' ';
        }
        $sRTFText .= $text->getText();

        if ($styleFont instanceof PHPWord_Style_Font) {
            $sRTFText .= '\cf0';
            $sRTFText .= '\f0';

            if ($styleFont->getBold()) {
                $sRTFText .= '\b0';
            }
            if ($styleFont->getItalic()) {
                $sRTFText .= '\i0';
            }
            if ($styleFont->getSize()) {
                $sRTFText .= '\fs' . (PHPWord::DEFAULT_FONT_SIZE * 2);
            }
        }

        if (!$withoutP) {
            $sRTFText .= '\par' . PHP_EOL;
        }
        return $sRTFText;
    }

    /**
     * Get text run content
     */
    private function getDataContentTextRun(PHPWord_Section_TextRun $textrun)
    {
        $sRTFText = '';
        $elements = $textrun->getElements();
        if (count($elements) > 0) {
            $sRTFText .= '\pard\nowidctlpar' . PHP_EOL;
            foreach ($elements as $element) {
                if ($element instanceof PHPWord_Section_Text) {
                    $sRTFText .= '{';
                    $sRTFText .= $this->getDataContentText($element, true);
                    $sRTFText .= '}' . PHP_EOL;
                }
            }
            $sRTFText .= '\par' . PHP_EOL;
        }
        return $sRTFText;
    }

    private function getDataContentTextBreak()
    {
        $this->_lastParagraphStyle = '';

        return '\par' . PHP_EOL;
    }

    /**
     * Write unsupported element
     *
     * @param   string  $element
     */
    private function getDataContentUnsupportedElement($element)
    {
        $sRTFText = '';
        $sRTFText .= '\pard\nowidctlpar' . PHP_EOL;
        $sRTFText .= "{$element}";
        $sRTFText .= '\par' . PHP_EOL;

        return $sRTFText;
    }
}
