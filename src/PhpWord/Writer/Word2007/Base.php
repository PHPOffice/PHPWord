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
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.9.0
 */

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Section\Footer\PreserveText;
use PhpOffice\PhpWord\Section\Footnote;
use PhpOffice\PhpWord\Section\Image;
use PhpOffice\PhpWord\Section\Link;
use PhpOffice\PhpWord\Section\ListItem;
use PhpOffice\PhpWord\Section\Object;
use PhpOffice\PhpWord\Section\Table;
use PhpOffice\PhpWord\Section\Text;
use PhpOffice\PhpWord\Section\TextBreak;
use PhpOffice\PhpWord\Section\TextRun;
use PhpOffice\PhpWord\Section\Title;
use PhpOffice\PhpWord\Shared\String;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style\Cell;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Word2007 base part writer
 *
 * Write common parts of document.xml, headerx.xml, and footerx.xml
 */
class Base extends WriterPart
{
    /**
     * Write text element
     *
     * @param PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param PhpOffice\PhpWord\Section\Text $text
     * @param boolean $withoutP
     */
    protected function _writeText(XMLWriter $xmlWriter, Text $text, $withoutP = false)
    {
        $styleFont = $text->getFontStyle();

        $SfIsObject = ($styleFont instanceof Font) ? true : false;

        if (!$withoutP) {
            $xmlWriter->startElement('w:p');

            $styleParagraph = $text->getParagraphStyle();
            $SpIsObject = ($styleParagraph instanceof Paragraph) ? true : false;

            if ($SpIsObject) {
                $this->_writeParagraphStyle($xmlWriter, $styleParagraph);
            } elseif (!$SpIsObject && !is_null($styleParagraph)) {
                $xmlWriter->startElement('w:pPr');
                $xmlWriter->startElement('w:pStyle');
                $xmlWriter->writeAttribute('w:val', $styleParagraph);
                $xmlWriter->endElement();
                $xmlWriter->endElement();
            }
        }

        $strText = htmlspecialchars($text->getText());
        $strText = String::controlCharacterPHP2OOXML($strText);

        $xmlWriter->startElement('w:r');

        if ($SfIsObject) {
            $this->_writeTextStyle($xmlWriter, $styleFont);
        } elseif (!$SfIsObject && !is_null($styleFont)) {
            $xmlWriter->startElement('w:rPr');
            $xmlWriter->startElement('w:rStyle');
            $xmlWriter->writeAttribute('w:val', $styleFont);
            $xmlWriter->endElement();
            $xmlWriter->endElement();
        }

        $xmlWriter->startElement('w:t');
        $xmlWriter->writeAttribute('xml:space', 'preserve'); // needed because of drawing spaces before and after text
        $xmlWriter->writeRaw($strText);
        $xmlWriter->endElement();

        $xmlWriter->endElement(); // w:r

        if (!$withoutP) {
            $xmlWriter->endElement(); // w:p
        }
    }

    /**
     * Write textrun  element
     *
     * @param PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param PhpOffice\PhpWord\Section $section
     */
    protected function _writeTextRun(XMLWriter $xmlWriter, TextRun $textrun)
    {
        $elements = $textrun->getElements();
        $styleParagraph = $textrun->getParagraphStyle();

        $SpIsObject = ($styleParagraph instanceof Paragraph) ? true : false;

        $xmlWriter->startElement('w:p');

        if ($SpIsObject) {
            $this->_writeParagraphStyle($xmlWriter, $styleParagraph);
        } elseif (!$SpIsObject && !is_null($styleParagraph)) {
            $xmlWriter->startElement('w:pPr');
            $xmlWriter->startElement('w:pStyle');
            $xmlWriter->writeAttribute('w:val', $styleParagraph);
            $xmlWriter->endElement();
            $xmlWriter->endElement();
        }

        if (count($elements) > 0) {
            foreach ($elements as $element) {
                if ($element instanceof Text) {
                    $this->_writeText($xmlWriter, $element, true);
                } elseif ($element instanceof Link) {
                    $this->_writeLink($xmlWriter, $element, true);
                } elseif ($element instanceof Image) {
                    $this->_writeImage($xmlWriter, $element, true);
                } elseif ($element instanceof Footnote) {
                    $this->_writeFootnoteReference($xmlWriter, $element, true);
                } elseif ($element instanceof TextBreak) {
                    $xmlWriter->writeElement('w:br');
                }
            }
        }

        $xmlWriter->endElement();
    }

    /**
     * Write paragraph style
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Paragraph $style
     * @param bool $withoutPPR
     */
    protected function _writeParagraphStyle(
        XMLWriter $xmlWriter,
        Paragraph $style,
        $withoutPPR = false
    ) {

        $align = $style->getAlign();
        $spacing = $style->getSpacing();
        $spaceBefore = $style->getSpaceBefore();
        $spaceAfter = $style->getSpaceAfter();
        $indent = $style->getIndent();
        $hanging = $style->getHanging();
        $tabs = $style->getTabs();
        $widowControl = $style->getWidowControl();
        $keepNext = $style->getKeepNext();
        $keepLines = $style->getKeepLines();
        $pageBreakBefore = $style->getPageBreakBefore();

        if (!is_null($align) || !is_null($spacing) || !is_null($spaceBefore) ||
                !is_null($spaceAfter) || !is_null($indent) || !is_null($hanging) ||
                !is_null($tabs) || !is_null($widowControl) || !is_null($keepNext) ||
                !is_null($keepLines) || !is_null($pageBreakBefore)) {
            if (!$withoutPPR) {
                $xmlWriter->startElement('w:pPr');
            }

            // Alignment
            if (!is_null($align)) {
                $xmlWriter->startElement('w:jc');
                $xmlWriter->writeAttribute('w:val', $align);
                $xmlWriter->endElement();
            }

            // Indentation
            if (!is_null($indent) || !is_null($hanging)) {
                $xmlWriter->startElement('w:ind');
                $xmlWriter->writeAttribute('w:firstLine', 0);
                if (!is_null($indent)) {
                    $xmlWriter->writeAttribute('w:left', $indent);
                }
                if (!is_null($hanging)) {
                    $xmlWriter->writeAttribute('w:hanging', $hanging);
                }
                $xmlWriter->endElement();
            }

            // Spacing
            if (!is_null($spaceBefore) || !is_null($spaceAfter) ||
                    !is_null($spacing)) {
                $xmlWriter->startElement('w:spacing');
                if (!is_null($spaceBefore)) {
                    $xmlWriter->writeAttribute('w:before', $spaceBefore);
                }
                if (!is_null($spaceAfter)) {
                    $xmlWriter->writeAttribute('w:after', $spaceAfter);
                }
                if (!is_null($spacing)) {
                    $xmlWriter->writeAttribute('w:line', $spacing);
                    $xmlWriter->writeAttribute('w:lineRule', 'auto');
                }
                $xmlWriter->endElement();
            }

            // Pagination
            if (!$widowControl) {
                $xmlWriter->startElement('w:widowControl');
                $xmlWriter->writeAttribute('w:val', '0');
                $xmlWriter->endElement();
            }
            if ($keepNext) {
                $xmlWriter->startElement('w:keepNext');
                $xmlWriter->writeAttribute('w:val', '1');
                $xmlWriter->endElement();
            }
            if ($keepLines) {
                $xmlWriter->startElement('w:keepLines');
                $xmlWriter->writeAttribute('w:val', '1');
                $xmlWriter->endElement();
            }
            if ($pageBreakBefore) {
                $xmlWriter->startElement('w:pageBreakBefore');
                $xmlWriter->writeAttribute('w:val', '1');
                $xmlWriter->endElement();
            }

            // Tabs
            if (!is_null($tabs)) {
                $tabs->toXml($xmlWriter);
            }

            if (!$withoutPPR) {
                $xmlWriter->endElement(); // w:pPr
            }
        }
    }

    /**
     * Write link element
     *
     * @param PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param PhpOffice\PhpWord\Section\Link $link
     * @param boolean $withoutP
     */
    protected function _writeLink(XMLWriter $xmlWriter, Link $link, $withoutP = false)
    {
        $rID = $link->getRelationId();
        $linkName = $link->getLinkName();
        if (is_null($linkName)) {
            $linkName = $link->getLinkSrc();
        }

        $styleFont = $link->getFontStyle();
        $SfIsObject = ($styleFont instanceof Font) ? true : false;

        if (!$withoutP) {
            $xmlWriter->startElement('w:p');

            $styleParagraph = $link->getParagraphStyle();
            $SpIsObject = ($styleParagraph instanceof Paragraph) ? true : false;

            if ($SpIsObject) {
                $this->_writeParagraphStyle($xmlWriter, $styleParagraph);
            } elseif (!$SpIsObject && !is_null($styleParagraph)) {
                $xmlWriter->startElement('w:pPr');
                $xmlWriter->startElement('w:pStyle');
                $xmlWriter->writeAttribute('w:val', $styleParagraph);
                $xmlWriter->endElement();
                $xmlWriter->endElement();
            }
        }

        $xmlWriter->startElement('w:hyperlink');
        $xmlWriter->writeAttribute('r:id', 'rId' . $rID);
        $xmlWriter->writeAttribute('w:history', '1');

        $xmlWriter->startElement('w:r');
        if ($SfIsObject) {
            $this->_writeTextStyle($xmlWriter, $styleFont);
        } elseif (!$SfIsObject && !is_null($styleFont)) {
            $xmlWriter->startElement('w:rPr');
            $xmlWriter->startElement('w:rStyle');
            $xmlWriter->writeAttribute('w:val', $styleFont);
            $xmlWriter->endElement();
            $xmlWriter->endElement();
        }

        $xmlWriter->startElement('w:t');
        $xmlWriter->writeAttribute('xml:space', 'preserve'); // needed because of drawing spaces before and after text
        $xmlWriter->writeRaw($linkName);
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->endElement();

        if (!$withoutP) {
            $xmlWriter->endElement(); // w:p
        }
    }

    /**
     * Write preserve text element
     *
     * @param PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param PhpOffice\PhpWord\Section\TextRun $textrun
     */
    protected function _writePreserveText(XMLWriter $xmlWriter, PreserveText $textrun)
    {
        $styleFont = $textrun->getFontStyle();
        $styleParagraph = $textrun->getParagraphStyle();

        $SfIsObject = ($styleFont instanceof Font) ? true : false;
        $SpIsObject = ($styleParagraph instanceof Paragraph) ? true : false;

        $arrText = $textrun->getText();
        if (!is_array($arrText)) {
            $arrText = array($arrText);
        }

        $xmlWriter->startElement('w:p');

        if ($SpIsObject) {
            $this->_writeParagraphStyle($xmlWriter, $styleParagraph);
        } elseif (!$SpIsObject && !is_null($styleParagraph)) {
            $xmlWriter->startElement('w:pPr');
            $xmlWriter->startElement('w:pStyle');
            $xmlWriter->writeAttribute('w:val', $styleParagraph);
            $xmlWriter->endElement();
            $xmlWriter->endElement();
        }

        foreach ($arrText as $text) {

            if (substr($text, 0, 1) == '{') {
                $text = substr($text, 1, -1);

                $xmlWriter->startElement('w:r');
                $xmlWriter->startElement('w:fldChar');
                $xmlWriter->writeAttribute('w:fldCharType', 'begin');
                $xmlWriter->endElement();
                $xmlWriter->endElement();

                $xmlWriter->startElement('w:r');

                if ($SfIsObject) {
                    $this->_writeTextStyle($xmlWriter, $styleFont);
                } elseif (!$SfIsObject && !is_null($styleFont)) {
                    $xmlWriter->startElement('w:rPr');
                    $xmlWriter->startElement('w:rStyle');
                    $xmlWriter->writeAttribute('w:val', $styleFont);
                    $xmlWriter->endElement();
                    $xmlWriter->endElement();
                }

                $xmlWriter->startElement('w:instrText');
                $xmlWriter->writeAttribute('xml:space', 'preserve');
                $xmlWriter->writeRaw($text);
                $xmlWriter->endElement();
                $xmlWriter->endElement();

                $xmlWriter->startElement('w:r');
                $xmlWriter->startElement('w:fldChar');
                $xmlWriter->writeAttribute('w:fldCharType', 'separate');
                $xmlWriter->endElement();
                $xmlWriter->endElement();

                $xmlWriter->startElement('w:r');
                $xmlWriter->startElement('w:fldChar');
                $xmlWriter->writeAttribute('w:fldCharType', 'end');
                $xmlWriter->endElement();
                $xmlWriter->endElement();
            } else {
                $text = htmlspecialchars($text);
                $text = String::controlCharacterPHP2OOXML($text);

                $xmlWriter->startElement('w:r');

                if ($SfIsObject) {
                    $this->_writeTextStyle($xmlWriter, $styleFont);
                } elseif (!$SfIsObject && !is_null($styleFont)) {
                    $xmlWriter->startElement('w:rPr');
                    $xmlWriter->startElement('w:rStyle');
                    $xmlWriter->writeAttribute('w:val', $styleFont);
                    $xmlWriter->endElement();
                    $xmlWriter->endElement();
                }

                $xmlWriter->startElement('w:t');
                $xmlWriter->writeAttribute('xml:space', 'preserve');
                $xmlWriter->writeRaw($text);
                $xmlWriter->endElement();
                $xmlWriter->endElement();
            }
        }

        $xmlWriter->endElement(); // p
    }

    /**
     * Write footnote reference element
     *
     * @param PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param PhpOffice\PhpWord\Section $section
     */
    protected function _writeTextStyle(XMLWriter $xmlWriter, Font $style)
    {
        $font = $style->getName();
        $bold = $style->getBold();
        $italic = $style->getItalic();
        $color = $style->getColor();
        $size = $style->getSize();
        $fgColor = $style->getFgColor();
        $strikethrough = $style->getStrikethrough();
        $underline = $style->getUnderline();
        $superscript = $style->getSuperScript();
        $subscript = $style->getSubScript();
        $hint = $style->getHint();

        $xmlWriter->startElement('w:rPr');

        // Font
        if ($font != PhpWord::DEFAULT_FONT_NAME) {
            $xmlWriter->startElement('w:rFonts');
            $xmlWriter->writeAttribute('w:ascii', $font);
            $xmlWriter->writeAttribute('w:hAnsi', $font);
            $xmlWriter->writeAttribute('w:eastAsia', $font);
            $xmlWriter->writeAttribute('w:cs', $font);
            //Font Content Type
            if ($hint != PhpWord::DEFAULT_FONT_CONTENT_TYPE) {
                $xmlWriter->writeAttribute('w:hint', $hint);
            }
            $xmlWriter->endElement();
        }


        // Color
        if ($color != PhpWord::DEFAULT_FONT_COLOR) {
            $xmlWriter->startElement('w:color');
            $xmlWriter->writeAttribute('w:val', $color);
            $xmlWriter->endElement();
        }

        // Size
        if ($size != PhpWord::DEFAULT_FONT_SIZE) {
            $xmlWriter->startElement('w:sz');
            $xmlWriter->writeAttribute('w:val', $size * 2);
            $xmlWriter->endElement();
            $xmlWriter->startElement('w:szCs');
            $xmlWriter->writeAttribute('w:val', $size * 2);
            $xmlWriter->endElement();
        }

        // Bold
        if ($bold) {
            $xmlWriter->writeElement('w:b', null);
        }

        // Italic
        if ($italic) {
            $xmlWriter->writeElement('w:i', null);
            $xmlWriter->writeElement('w:iCs', null);
        }

        // Underline
        if (!is_null($underline) && $underline != 'none') {
            $xmlWriter->startElement('w:u');
            $xmlWriter->writeAttribute('w:val', $underline);
            $xmlWriter->endElement();
        }

        // Strikethrough
        if ($strikethrough) {
            $xmlWriter->writeElement('w:strike', null);
        }

        // Foreground-Color
        if (!is_null($fgColor)) {
            $xmlWriter->startElement('w:highlight');
            $xmlWriter->writeAttribute('w:val', $fgColor);
            $xmlWriter->endElement();
        }

        // Superscript/subscript
        if ($superscript || $subscript) {
            $xmlWriter->startElement('w:vertAlign');
            $xmlWriter->writeAttribute('w:val', $superscript ? 'superscript' : 'subscript');
            $xmlWriter->endElement();
        }

        $xmlWriter->endElement();
    }

    /**
     * Write text break element
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Section\TextBreak $element
     */
    protected function _writeTextBreak($xmlWriter, $element = null)
    {
        $hasStyle = false;
        if (!is_null($element)) {
            $fontStyle = $element->getFontStyle();
            $sfIsObject = ($fontStyle instanceof Font) ? true : false;
            $paragraphStyle = $element->getParagraphStyle();
            $spIsObject = ($paragraphStyle instanceof Paragraph) ? true : false;
            $hasStyle = !is_null($fontStyle) || !is_null($paragraphStyle);
        }
        if ($hasStyle) {
            // Paragraph style
            $xmlWriter->startElement('w:p');
            if ($spIsObject) {
                $this->_writeParagraphStyle($xmlWriter, $paragraphStyle);
            } elseif (!$spIsObject && !is_null($paragraphStyle)) {
                $xmlWriter->startElement('w:pPr');
                $xmlWriter->startElement('w:pStyle');
                $xmlWriter->writeAttribute('w:val', $paragraphStyle);
                $xmlWriter->endElement(); // w:pStyle
                $xmlWriter->endElement(); // w:pPr
            }
            // Font style
            if (!is_null($fontStyle)) {
                $xmlWriter->startElement('w:pPr');
                if ($sfIsObject) {
                    $this->_writeTextStyle($xmlWriter, $fontStyle);
                } elseif (!$sfIsObject && !is_null($fontStyle)) {
                    $xmlWriter->startElement('w:rPr');
                    $xmlWriter->startElement('w:rStyle');
                    $xmlWriter->writeAttribute('w:val', $fontStyle);
                    $xmlWriter->endElement(); // w:rStyle
                    $xmlWriter->endElement(); // w:rPr
                }
                $xmlWriter->endElement(); // w:pPr
            }
            $xmlWriter->endElement(); // w:p
        } else {
            // Null element. No paragraph nor font style
            $xmlWriter->writeElement('w:p', null);
        }
    }

    /**
     * Write footnote reference element
     *
     * @param PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param PhpOffice\PhpWord\Section\Table $table
     */
    protected function _writeTable(XMLWriter $xmlWriter, Table $table)
    {
        $_rows = $table->getRows();
        $_cRows = count($_rows);

        if ($_cRows > 0) {
            $xmlWriter->startElement('w:tbl');
            $tblStyle = $table->getStyle();
            $tblWidth = $table->getWidth();
            if ($tblStyle instanceof PhpOffice\PhpWord\Style\Table) {
                $this->_writeTableStyle($xmlWriter, $tblStyle, false);
            } else {
                if (!empty($tblStyle)) {
                    $xmlWriter->startElement('w:tblPr');
                    $xmlWriter->startElement('w:tblStyle');
                    $xmlWriter->writeAttribute('w:val', $tblStyle);
                    $xmlWriter->endElement();
                    if (!is_null($tblWidth)) {
                        $xmlWriter->startElement('w:tblW');
                        $xmlWriter->writeAttribute('w:w', $tblWidth);
                        $xmlWriter->writeAttribute('w:type', 'pct');
                        $xmlWriter->endElement();
                    }
                    $xmlWriter->endElement();
                }
            }

            for ($i = 0; $i < $_cRows; $i++) {
                $row = $_rows[$i];
                $height = $row->getHeight();
                $rowStyle = $row->getStyle();
                $tblHeader = $rowStyle->getTblHeader();
                $cantSplit = $rowStyle->getCantSplit();

                $xmlWriter->startElement('w:tr');

                if (!is_null($height) || !is_null($tblHeader) || !is_null($cantSplit)) {
                    $xmlWriter->startElement('w:trPr');
                    if (!is_null($height)) {
                        $xmlWriter->startElement('w:trHeight');
                        $xmlWriter->writeAttribute('w:val', $height);
                        $xmlWriter->endElement();
                    }
                    if ($tblHeader) {
                        $xmlWriter->startElement('w:tblHeader');
                        $xmlWriter->writeAttribute('w:val', '1');
                        $xmlWriter->endElement();
                    }
                    if ($cantSplit) {
                        $xmlWriter->startElement('w:cantSplit');
                        $xmlWriter->writeAttribute('w:val', '1');
                        $xmlWriter->endElement();
                    }
                    $xmlWriter->endElement();
                }

                foreach ($row->getCells() as $cell) {
                    $xmlWriter->startElement('w:tc');

                    $cellStyle = $cell->getStyle();
                    $width = $cell->getWidth();

                    $xmlWriter->startElement('w:tcPr');
                    $xmlWriter->startElement('w:tcW');
                    $xmlWriter->writeAttribute('w:w', $width);
                    $xmlWriter->writeAttribute('w:type', 'dxa');
                    $xmlWriter->endElement();

                    if ($cellStyle instanceof Cell) {
                        $this->_writeCellStyle($xmlWriter, $cellStyle);
                    }

                    $xmlWriter->endElement();

                    $_elements = $cell->getElements();
                    if (count($_elements) > 0) {
                        foreach ($_elements as $element) {
                            if ($element instanceof Text) {
                                $this->_writeText($xmlWriter, $element);
                            } elseif ($element instanceof TextRun) {
                                $this->_writeTextRun($xmlWriter, $element);
                            } elseif ($element instanceof Link) {
                                $this->_writeLink($xmlWriter, $element);
                            } elseif ($element instanceof TextBreak) {
                                $this->_writeTextBreak($xmlWriter, $element);
                            } elseif ($element instanceof ListItem) {
                                $this->_writeListItem($xmlWriter, $element);
                            } elseif ($element instanceof Image) {
                                $this->_writeImage($xmlWriter, $element);
                            } elseif ($element instanceof Object) {
                                $this->_writeObject($xmlWriter, $element);
                            } elseif ($element instanceof PreserveText) {
                                $this->_writePreserveText($xmlWriter, $element);
                            }
                        }
                    } else {
                        $this->_writeTextBreak($xmlWriter);
                    }

                    $xmlWriter->endElement();
                }
                $xmlWriter->endElement();
            }
            $xmlWriter->endElement();
        }
    }

    /**
     * Write table style
     *
     * @param PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param PhpOffice\PhpWord\Style\Table $style
     * @param boolean $isFullStyle
     */
    protected function _writeTableStyle(
        XMLWriter $xmlWriter,
        \PhpOffice\PhpWord\Style\Table $style,
        $isFullStyle = true
    ) {
        $bgColor = $style->getBgColor();
        $brdCol = $style->getBorderColor();

        $brdSz = $style->getBorderSize();
        $bTop = (!is_null($brdSz[0])) ? true : false;
        $bLeft = (!is_null($brdSz[1])) ? true : false;
        $bRight = (!is_null($brdSz[2])) ? true : false;
        $bBottom = (!is_null($brdSz[3])) ? true : false;
        $bInsH = (!is_null($brdSz[4])) ? true : false;
        $bInsV = (!is_null($brdSz[5])) ? true : false;
        $borders = ($bTop || $bLeft || $bRight || $bBottom || $bInsH || $bInsV) ? true : false;

        $cellMargin = $style->getCellMargin();
        $mTop = (!is_null($cellMargin[0])) ? true : false;
        $mLeft = (!is_null($cellMargin[1])) ? true : false;
        $mRight = (!is_null($cellMargin[2])) ? true : false;
        $mBottom = (!is_null($cellMargin[3])) ? true : false;
        $margins = ($mTop || $mLeft || $mRight || $mBottom) ? true : false;

        if ($margins || $borders) {
            $xmlWriter->startElement('w:tblPr');
            if ($margins) {
                $xmlWriter->startElement('w:tblCellMar');
                if ($mTop) {
                    echo $margins[0];
                    $xmlWriter->startElement('w:top');
                    $xmlWriter->writeAttribute('w:w', $cellMargin[0]);
                    $xmlWriter->writeAttribute('w:type', 'dxa');
                    $xmlWriter->endElement();
                }
                if ($mLeft) {
                    $xmlWriter->startElement('w:left');
                    $xmlWriter->writeAttribute('w:w', $cellMargin[1]);
                    $xmlWriter->writeAttribute('w:type', 'dxa');
                    $xmlWriter->endElement();
                }
                if ($mRight) {
                    $xmlWriter->startElement('w:right');
                    $xmlWriter->writeAttribute('w:w', $cellMargin[2]);
                    $xmlWriter->writeAttribute('w:type', 'dxa');
                    $xmlWriter->endElement();
                }
                if ($mBottom) {
                    $xmlWriter->startElement('w:bottom');
                    $xmlWriter->writeAttribute('w:w', $cellMargin[3]);
                    $xmlWriter->writeAttribute('w:type', 'dxa');
                    $xmlWriter->endElement();
                }
                $xmlWriter->endElement();
            }
            if ($borders) {
                $xmlWriter->startElement('w:tblBorders');
                if ($bTop) {
                    $xmlWriter->startElement('w:top');
                    $xmlWriter->writeAttribute('w:val', 'single');
                    $xmlWriter->writeAttribute('w:sz', $brdSz[0]);
                    $xmlWriter->writeAttribute('w:color', $brdCol[0]);
                    $xmlWriter->endElement();
                }
                if ($bLeft) {
                    $xmlWriter->startElement('w:left');
                    $xmlWriter->writeAttribute('w:val', 'single');
                    $xmlWriter->writeAttribute('w:sz', $brdSz[1]);
                    $xmlWriter->writeAttribute('w:color', $brdCol[1]);
                    $xmlWriter->endElement();
                }
                if ($bRight) {
                    $xmlWriter->startElement('w:right');
                    $xmlWriter->writeAttribute('w:val', 'single');
                    $xmlWriter->writeAttribute('w:sz', $brdSz[2]);
                    $xmlWriter->writeAttribute('w:color', $brdCol[2]);
                    $xmlWriter->endElement();
                }
                if ($bBottom) {
                    $xmlWriter->startElement('w:bottom');
                    $xmlWriter->writeAttribute('w:val', 'single');
                    $xmlWriter->writeAttribute('w:sz', $brdSz[3]);
                    $xmlWriter->writeAttribute('w:color', $brdCol[3]);
                    $xmlWriter->endElement();
                }
                if ($bInsH) {
                    $xmlWriter->startElement('w:insideH');
                    $xmlWriter->writeAttribute('w:val', 'single');
                    $xmlWriter->writeAttribute('w:sz', $brdSz[4]);
                    $xmlWriter->writeAttribute('w:color', $brdCol[4]);
                    $xmlWriter->endElement();
                }
                if ($bInsV) {
                    $xmlWriter->startElement('w:insideV');
                    $xmlWriter->writeAttribute('w:val', 'single');
                    $xmlWriter->writeAttribute('w:sz', $brdSz[5]);
                    $xmlWriter->writeAttribute('w:color', $brdCol[5]);
                    $xmlWriter->endElement();
                }
                $xmlWriter->endElement();
            }
            $xmlWriter->endElement(); // w:tblPr
        }
        // Only write background color and first row for full style
        if ($isFullStyle) {
            // Background color
            if (!is_null($bgColor)) {
                $xmlWriter->startElement('w:tcPr');
                $xmlWriter->startElement('w:shd');
                $xmlWriter->writeAttribute('w:val', 'clear');
                $xmlWriter->writeAttribute('w:color', 'auto');
                $xmlWriter->writeAttribute('w:fill', $bgColor);
                $xmlWriter->endElement();
                $xmlWriter->endElement();
            }
            // First Row
            $firstRow = $style->getFirstRow();
            if (!is_null($firstRow)) {
                $this->_writeRowStyle($xmlWriter, 'firstRow', $firstRow);
            }
        }
    }

    /**
     * Write row style
     *
     * @param PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param string $type
     * @param PhpOffice\PhpWord\Style\Table $style
     */
    protected function _writeRowStyle(
        XMLWriter $xmlWriter,
        $type,
        \PhpOffice\PhpWord\Style\Table $style
    ) {
        $brdSz = $style->getBorderSize();
        $brdCol = $style->getBorderColor();
        $bgColor = $style->getBgColor();

        $bTop = (!is_null($brdSz[0])) ? true : false;
        $bLeft = (!is_null($brdSz[1])) ? true : false;
        $bRight = (!is_null($brdSz[2])) ? true : false;
        $bBottom = (!is_null($brdSz[3])) ? true : false;
        $borders = ($bTop || $bLeft || $bRight || $bBottom) ? true : false;

        $xmlWriter->startElement('w:tblStylePr');
        $xmlWriter->writeAttribute('w:type', $type);

        $xmlWriter->startElement('w:tcPr');
        if (!is_null($bgColor)) {
            $xmlWriter->startElement('w:shd');
            $xmlWriter->writeAttribute('w:val', 'clear');
            $xmlWriter->writeAttribute('w:color', 'auto');
            $xmlWriter->writeAttribute('w:fill', $bgColor);
            $xmlWriter->endElement();
        }

        $xmlWriter->startElement('w:tcBorders');
        if ($bTop) {
            $xmlWriter->startElement('w:top');
            $xmlWriter->writeAttribute('w:val', 'single');
            $xmlWriter->writeAttribute('w:sz', $brdSz[0]);
            $xmlWriter->writeAttribute('w:color', $brdCol[0]);
            $xmlWriter->endElement();
        }
        if ($bLeft) {
            $xmlWriter->startElement('w:left');
            $xmlWriter->writeAttribute('w:val', 'single');
            $xmlWriter->writeAttribute('w:sz', $brdSz[1]);
            $xmlWriter->writeAttribute('w:color', $brdCol[1]);
            $xmlWriter->endElement();
        }
        if ($bRight) {
            $xmlWriter->startElement('w:right');
            $xmlWriter->writeAttribute('w:val', 'single');
            $xmlWriter->writeAttribute('w:sz', $brdSz[2]);
            $xmlWriter->writeAttribute('w:color', $brdCol[2]);
            $xmlWriter->endElement();
        }
        if ($bBottom) {
            $xmlWriter->startElement('w:bottom');
            $xmlWriter->writeAttribute('w:val', 'single');
            $xmlWriter->writeAttribute('w:sz', $brdSz[3]);
            $xmlWriter->writeAttribute('w:color', $brdCol[3]);
            $xmlWriter->endElement();
        }
        $xmlWriter->endElement();

        $xmlWriter->endElement();

        $xmlWriter->endElement();
    }

    /**
     * Write footnote reference element
     *
     * @param PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param PhpOffice\PhpWord\Style\Cell $style
     */
    protected function _writeCellStyle(XMLWriter $xmlWriter, Cell $style = null)
    {
        $bgColor = $style->getBgColor();
        $valign = $style->getVAlign();
        $textDir = $style->getTextDirection();
        $brdSz = $style->getBorderSize();
        $brdCol = $style->getBorderColor();

        $bTop = (!is_null($brdSz[0])) ? true : false;
        $bLeft = (!is_null($brdSz[1])) ? true : false;
        $bRight = (!is_null($brdSz[2])) ? true : false;
        $bBottom = (!is_null($brdSz[3])) ? true : false;
        $borders = ($bTop || $bLeft || $bRight || $bBottom) ? true : false;

        $styles = (!is_null($bgColor) || !is_null($valign) || !is_null($textDir) || $borders) ? true : false;

        if ($styles) {
            if (!is_null($textDir)) {
                $xmlWriter->startElement('w:textDirection');
                $xmlWriter->writeAttribute('w:val', $textDir);
                $xmlWriter->endElement();
            }

            if (!is_null($bgColor)) {
                $xmlWriter->startElement('w:shd');
                $xmlWriter->writeAttribute('w:val', 'clear');
                $xmlWriter->writeAttribute('w:color', 'auto');
                $xmlWriter->writeAttribute('w:fill', $bgColor);
                $xmlWriter->endElement();
            }

            if (!is_null($valign)) {
                $xmlWriter->startElement('w:vAlign');
                $xmlWriter->writeAttribute('w:val', $valign);
                $xmlWriter->endElement();
            }

            if ($borders) {
                $_defaultColor = $style->getDefaultBorderColor();

                $xmlWriter->startElement('w:tcBorders');
                if ($bTop) {
                    if (is_null($brdCol[0])) {
                        $brdCol[0] = $_defaultColor;
                    }
                    $xmlWriter->startElement('w:top');
                    $xmlWriter->writeAttribute('w:val', 'single');
                    $xmlWriter->writeAttribute('w:sz', $brdSz[0]);
                    $xmlWriter->writeAttribute('w:color', $brdCol[0]);
                    $xmlWriter->endElement();
                }

                if ($bLeft) {
                    if (is_null($brdCol[1])) {
                        $brdCol[1] = $_defaultColor;
                    }
                    $xmlWriter->startElement('w:left');
                    $xmlWriter->writeAttribute('w:val', 'single');
                    $xmlWriter->writeAttribute('w:sz', $brdSz[1]);
                    $xmlWriter->writeAttribute('w:color', $brdCol[1]);
                    $xmlWriter->endElement();
                }

                if ($bRight) {
                    if (is_null($brdCol[2])) {
                        $brdCol[2] = $_defaultColor;
                    }
                    $xmlWriter->startElement('w:right');
                    $xmlWriter->writeAttribute('w:val', 'single');
                    $xmlWriter->writeAttribute('w:sz', $brdSz[2]);
                    $xmlWriter->writeAttribute('w:color', $brdCol[2]);
                    $xmlWriter->endElement();
                }

                if ($bBottom) {
                    if (is_null($brdCol[3])) {
                        $brdCol[3] = $_defaultColor;
                    }
                    $xmlWriter->startElement('w:bottom');
                    $xmlWriter->writeAttribute('w:val', 'single');
                    $xmlWriter->writeAttribute('w:sz', $brdSz[3]);
                    $xmlWriter->writeAttribute('w:color', $brdCol[3]);
                    $xmlWriter->endElement();
                }

                $xmlWriter->endElement();
            }
        }
        $gridSpan = $style->getGridSpan();
        if (!is_null($gridSpan)) {
            $xmlWriter->startElement('w:gridSpan');
            $xmlWriter->writeAttribute('w:val', $gridSpan);
            $xmlWriter->endElement();
        }

        $vMerge = $style->getVMerge();
        if (!is_null($vMerge)) {
            $xmlWriter->startElement('w:vMerge');
            $xmlWriter->writeAttribute('w:val', $vMerge);
            $xmlWriter->endElement();
        }
    }

    /**
     * Write image element
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param mixed $image
     * @param boolean $withoutP
     */
    protected function _writeImage(XMLWriter $xmlWriter, $image, $withoutP = false)
    {
        $rId = $image->getRelationId();

        $style = $image->getStyle();
        $width = $style->getWidth();
        $height = $style->getHeight();
        $align = $style->getAlign();
        $marginTop = $style->getMarginTop();
        $marginLeft = $style->getMarginLeft();
        $wrappingStyle = $style->getWrappingStyle();

        if (!$withoutP) {
            $xmlWriter->startElement('w:p');

            if (!is_null($align)) {
                $xmlWriter->startElement('w:pPr');
                $xmlWriter->startElement('w:jc');
                $xmlWriter->writeAttribute('w:val', $align);
                $xmlWriter->endElement();
                $xmlWriter->endElement();
            }
        }

        $xmlWriter->startElement('w:r');

        $xmlWriter->startElement('w:pict');

        $xmlWriter->startElement('v:shape');
        $xmlWriter->writeAttribute('type', '#_x0000_t75');

        $imgStyle = '';
        if (null !== $width) {
            $imgStyle .= 'width:' . $width . 'px;';
        }
        if (null !== $height) {
            $imgStyle .= 'height:' . $height . 'px;';
        }
        if (null !== $marginTop) {
            $imgStyle .= 'margin-top:' . $marginTop . 'in;';
        }
        if (null !== $marginLeft) {
            $imgStyle .= 'margin-left:' . $marginLeft . 'in;';
        }

        switch ($wrappingStyle) {
            case \PhpOffice\PhpWord\Style\Image::WRAPPING_STYLE_BEHIND:
                $imgStyle .= 'position:absolute;z-index:-251658752;';
                break;
            case \PhpOffice\PhpWord\Style\Image::WRAPPING_STYLE_SQUARE:
                $imgStyle .= 'position:absolute;z-index:251659264;mso-position-horizontal:absolute;mso-position-vertical:absolute;';
                break;
            case \PhpOffice\PhpWord\Style\Image::WRAPPING_STYLE_TIGHT:
                $imgStyle .= 'position:absolute;z-index:251659264;mso-wrap-edited:f;mso-position-horizontal:absolute;mso-position-vertical:absolute';
                break;
            case \PhpOffice\PhpWord\Style\Image::WRAPPING_STYLE_INFRONT:
                $imgStyle .= 'position:absolute;zz-index:251659264;mso-position-horizontal:absolute;mso-position-vertical:absolute;';
                break;
        }

        $xmlWriter->writeAttribute('style', $imgStyle);

        $xmlWriter->startElement('v:imagedata');
        $xmlWriter->writeAttribute('r:id', 'rId' . $rId);
        $xmlWriter->writeAttribute('o:title', '');
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->endElement();

        $xmlWriter->endElement();

        if (!$withoutP) {
            $xmlWriter->endElement(); // w:p
        }
    }

    /**
     * Write footnote reference element
     *
     * @param PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param mixed $image
     */
    protected function _writeWatermark(XMLWriter $xmlWriter, $image)
    {
        $rId = $image->getRelationId();

        $style = $image->getStyle();
        $width = $style->getWidth();
        $height = $style->getHeight();
        $marginLeft = $style->getMarginLeft();
        $marginTop = $style->getMarginTop();

        $xmlWriter->startElement('w:p');

        $xmlWriter->startElement('w:r');

        $xmlWriter->startElement('w:pict');

        $xmlWriter->startElement('v:shape');
        $xmlWriter->writeAttribute('type', '#_x0000_t75');

        $strStyle = 'position:absolute;';
        $strStyle .= ' width:' . $width . 'px;';
        $strStyle .= ' height:' . $height . 'px;';
        if (!is_null($marginTop)) {
            $strStyle .= ' margin-top:' . $marginTop . 'px;';
        }
        if (!is_null($marginLeft)) {
            $strStyle .= ' margin-left:' . $marginLeft . 'px;';
        }

        $xmlWriter->writeAttribute('style', $strStyle);

        $xmlWriter->startElement('v:imagedata');
        $xmlWriter->writeAttribute('r:id', 'rId' . $rId);
        $xmlWriter->writeAttribute('o:title', '');
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->endElement();

        $xmlWriter->endElement();

        $xmlWriter->endElement();
    }

    /**
     * Write title element
     *
     * @param PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param PhpOffice\PhpWord\Section\Title $title
     */
    protected function _writeTitle(XMLWriter $xmlWriter, Title $title)
    {
        $text = htmlspecialchars($title->getText());
        $text = String::controlCharacterPHP2OOXML($text);
        $anchor = $title->getAnchor();
        $bookmarkId = $title->getBookmarkId();
        $style = $title->getStyle();

        $xmlWriter->startElement('w:p');

        if (!empty($style)) {
            $xmlWriter->startElement('w:pPr');
            $xmlWriter->startElement('w:pStyle');
            $xmlWriter->writeAttribute('w:val', $style);
            $xmlWriter->endElement();
            $xmlWriter->endElement();
        }

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'end');
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->startElement('w:bookmarkStart');
        $xmlWriter->writeAttribute('w:id', $bookmarkId);
        $xmlWriter->writeAttribute('w:name', $anchor);
        $xmlWriter->endElement();

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:t');
        $xmlWriter->writeRaw($text);
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->startElement('w:bookmarkEnd');
        $xmlWriter->writeAttribute('w:id', $bookmarkId);
        $xmlWriter->endElement();

        $xmlWriter->endElement();
    }

    /**
     * Write footnote element
     *
     * @param PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param PhpOffice\PhpWord\Section\Footnote $footnote
     */
    protected function _writeFootnote(XMLWriter $xmlWriter, Footnote $footnote)
    {
        $xmlWriter->startElement('w:footnote');
        $xmlWriter->writeAttribute('w:id', $footnote->getReferenceId());

        $styleParagraph = $footnote->getParagraphStyle();
        $SpIsObject = ($styleParagraph instanceof Paragraph) ? true : false;

        $xmlWriter->startElement('w:p');

        if ($SpIsObject) {
            $this->_writeParagraphStyle($xmlWriter, $styleParagraph);
        } elseif (!$SpIsObject && !is_null($styleParagraph)) {
            $xmlWriter->startElement('w:pPr');
            $xmlWriter->startElement('w:pStyle');
            $xmlWriter->writeAttribute('w:val', $styleParagraph);
            $xmlWriter->endElement();
            $xmlWriter->endElement();
        }

        $elements = $footnote->getElements();
        if (count($elements) > 0) {
            foreach ($elements as $element) {
                if ($element instanceof Text) {
                    $this->_writeText($xmlWriter, $element, true);
                } elseif ($element instanceof Link) {
                    $this->_writeLink($xmlWriter, $element, true);
                }
            }
        }

        $xmlWriter->endElement(); // w:p
        $xmlWriter->endElement(); // w:footnote
    }

    /**
     * Write footnote reference element
     *
     * @param PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param PhpOffice\PhpWord\Section\Footnote $footnote
     * @param boolean $withoutP
     */
    protected function _writeFootnoteReference(XMLWriter $xmlWriter, Footnote $footnote, $withoutP = false)
    {
        if (!$withoutP) {
            $xmlWriter->startElement('w:p');
        }

        $xmlWriter->startElement('w:r');

        $xmlWriter->startElement('w:footnoteReference');
        $xmlWriter->writeAttribute('w:id', $footnote->getReferenceId());
        $xmlWriter->endElement(); // w:footnoteReference

        $xmlWriter->endElement(); // w:r

        if (!$withoutP) {
            $xmlWriter->endElement(); // w:p
        }
    }
}
