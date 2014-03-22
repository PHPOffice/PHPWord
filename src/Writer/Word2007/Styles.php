<?php
/**
 * PhpWord
 *
 * Copyright (c) 2014 PhpWord
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
 * @category   PhpWord
 * @package    PhpWord
 * @copyright  Copyright (c) 2014 PhpWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.8.0
 */

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Style\TableFull;

class Styles extends Base
{
    private $_document;

    public function writeStyles(PhpWord $phpWord = null)
    {
        // Create XML writer
        $xmlWriter = null;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $xmlWriter = new XMLWriter(XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $xmlWriter = new XMLWriter(XMLWriter::STORAGE_MEMORY);
        }

        $this->_document = $phpWord;

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');

        $xmlWriter->startElement('w:styles');

        $xmlWriter->writeAttribute('xmlns:r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $xmlWriter->writeAttribute('xmlns:w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

        // Write DocDefaults
        $this->_writeDocDefaults($xmlWriter);


        // Write Style Definitions
        $styles = Style::getStyles();

        // Write normal paragraph style
        $normalStyle = null;
        if (array_key_exists('Normal', $styles)) {
            $normalStyle = $styles['Normal'];
        }
        $xmlWriter->startElement('w:style');
        $xmlWriter->writeAttribute('w:type', 'paragraph');
        $xmlWriter->writeAttribute('w:default', '1');
        $xmlWriter->writeAttribute('w:styleId', 'Normal');
        $xmlWriter->startElement('w:name');
        $xmlWriter->writeAttribute('w:val', 'Normal');
        $xmlWriter->endElement();
        if (!is_null($normalStyle)) {
            $this->_writeParagraphStyle($xmlWriter, $normalStyle);
        }
        $xmlWriter->endElement();

        // Write other styles
        if (count($styles) > 0) {
            foreach ($styles as $styleName => $style) {
                if ($styleName == 'Normal') {
                    continue;
                }
                if ($style instanceof Font) {

                    $paragraphStyle = $style->getParagraphStyle();
                    $styleType = $style->getStyleType();

                    $type = ($styleType == 'title') ? 'paragraph' : 'character';

                    if (!is_null($paragraphStyle)) {
                        $type = 'paragraph';
                    }

                    $xmlWriter->startElement('w:style');
                    $xmlWriter->writeAttribute('w:type', $type);

                    if ($styleType == 'title') {
                        $arrStyle = explode('_', $styleName);
                        $styleId = 'Heading' . $arrStyle[1];
                        $styleName = 'heading ' . $arrStyle[1];
                        $styleLink = 'Heading' . $arrStyle[1] . 'Char';
                        $xmlWriter->writeAttribute('w:styleId', $styleId);

                        $xmlWriter->startElement('w:link');
                        $xmlWriter->writeAttribute('w:val', $styleLink);
                        $xmlWriter->endElement();
                    }

                    $xmlWriter->startElement('w:name');
                    $xmlWriter->writeAttribute('w:val', $styleName);
                    $xmlWriter->endElement();

                    if (!is_null($paragraphStyle)) {
                        // Point parent style to Normal
                        $xmlWriter->startElement('w:basedOn');
                        $xmlWriter->writeAttribute('w:val', 'Normal');
                        $xmlWriter->endElement();
                        $this->_writeParagraphStyle($xmlWriter, $paragraphStyle);
                    }

                    $this->_writeTextStyle($xmlWriter, $style);

                    $xmlWriter->endElement();

                } elseif ($style instanceof Paragraph) {
                    $xmlWriter->startElement('w:style');
                    $xmlWriter->writeAttribute('w:type', 'paragraph');
                    $xmlWriter->writeAttribute('w:customStyle', '1');
                    $xmlWriter->writeAttribute('w:styleId', $styleName);

                    $xmlWriter->startElement('w:name');
                    $xmlWriter->writeAttribute('w:val', $styleName);
                    $xmlWriter->endElement();

                    // Parent style
                    $basedOn = $style->getBasedOn();
                    if (!is_null($basedOn)) {
                        $xmlWriter->startElement('w:basedOn');
                        $xmlWriter->writeAttribute('w:val', $basedOn);
                        $xmlWriter->endElement();
                    }

                    // Next paragraph style
                    $next = $style->getNext();
                    if (!is_null($next)) {
                        $xmlWriter->startElement('w:next');
                        $xmlWriter->writeAttribute('w:val', $next);
                        $xmlWriter->endElement();
                    }

                    $this->_writeParagraphStyle($xmlWriter, $style);
                    $xmlWriter->endElement();

                } elseif ($style instanceof TableFull) {
                    $xmlWriter->startElement('w:style');
                    $xmlWriter->writeAttribute('w:type', 'table');
                    $xmlWriter->writeAttribute('w:customStyle', '1');
                    $xmlWriter->writeAttribute('w:styleId', $styleName);

                    $xmlWriter->startElement('w:name');
                    $xmlWriter->writeAttribute('w:val', $styleName);
                    $xmlWriter->endElement();

                    $xmlWriter->startElement('w:uiPriority');
                    $xmlWriter->writeAttribute('w:val', '99');
                    $xmlWriter->endElement();

                    $this->_writeFullTableStyle($xmlWriter, $style);

                    $xmlWriter->endElement();
                }
            }
        }

        $xmlWriter->endElement(); // w:styles

        // Return
        return $xmlWriter->getData();
    }

    private function _writeFullTableStyle(XMLWriter $xmlWriter = null, TableFull $style)
    {

        $brdSz = $style->getBorderSize();
        $brdCol = $style->getBorderColor();
        $bgColor = $style->getBgColor();
        $cellMargin = $style->getCellMargin();

        $bTop = (!is_null($brdSz[0])) ? true : false;
        $bLeft = (!is_null($brdSz[1])) ? true : false;
        $bRight = (!is_null($brdSz[2])) ? true : false;
        $bBottom = (!is_null($brdSz[3])) ? true : false;
        $bInsH = (!is_null($brdSz[4])) ? true : false;
        $bInsV = (!is_null($brdSz[5])) ? true : false;
        $borders = ($bTop || $bLeft || $bRight || $bBottom || $bInsH || $bInsV) ? true : false;

        $mTop = (!is_null($cellMargin[0])) ? true : false;
        $mLeft = (!is_null($cellMargin[1])) ? true : false;
        $mRight = (!is_null($cellMargin[2])) ? true : false;
        $mBottom = (!is_null($cellMargin[3])) ? true : false;
        $margins = ($mTop || $mLeft || $mRight || $mBottom) ? true : false;

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

        $xmlWriter->endElement();

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

    private function _writeRowStyle(XMLWriter $xmlWriter = null, $type, TableFull $style)
    {
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


    private function _writeDocDefaults(XMLWriter $xmlWriter = null)
    {
        $fontName = $this->_document->getDefaultFontName();
        $fontSize = $this->_document->getDefaultFontSize();

        $xmlWriter->startElement('w:docDefaults');
        $xmlWriter->startElement('w:rPrDefault');
        $xmlWriter->startElement('w:rPr');

        $xmlWriter->startElement('w:rFonts');
        $xmlWriter->writeAttribute('w:ascii', $fontName);
        $xmlWriter->writeAttribute('w:hAnsi', $fontName);
        $xmlWriter->writeAttribute('w:eastAsia', $fontName);
        $xmlWriter->writeAttribute('w:cs', $fontName);
        $xmlWriter->endElement();

        $xmlWriter->startElement('w:sz');
        $xmlWriter->writeAttribute('w:val', $fontSize * 2);
        $xmlWriter->endElement();

        $xmlWriter->startElement('w:szCs');
        $xmlWriter->writeAttribute('w:val', $fontSize * 2);
        $xmlWriter->endElement();

        $xmlWriter->endElement();
        $xmlWriter->endElement();
        $xmlWriter->endElement();
    }
}
