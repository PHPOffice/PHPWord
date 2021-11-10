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
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\HTML\Element;

use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Writer\HTML\Style\Table as TextStyleWriter;

/**
 * Table element HTML writer
 *
 * @since 0.10.0
 */
class Table extends AbstractElement
{
    /**
     * Write table
     *
     * @return string
     */
    public function write()
    {
        if (!$this->element instanceof \PhpOffice\PhpWord\Element\Table) {
            return '';
        }

        $content = '';
        $tableStyle = $this->element->getStyle();
        $rows = $this->element->getRows();
        $rowCount = count($rows);
        if ($rowCount > 0) {
            $content .= '<table' . $this->getTableStyle($tableStyle) . '>' . PHP_EOL;

            if (is_string($tableStyle)) {
                $customStyles = Style::getStyles();
                if (is_array($customStyles) && isset($customStyles[$tableStyle])) {
                    $tableStyle = $customStyles[$tableStyle];
                }
            }

            $borderStyleConverter = array(
                'single'=> 'solid',
                'none'  => 'none',
                'nil'   => 'hidden',
                'dotted'=> 'dotted',
                'dashed'=> 'dashed',
                'double'=> 'double',
                'inset' => 'inset',
                'outset'=> 'outset',
            );
            $cellStyles = array();
            if (is_object($tableStyle)) {
                $cellHBorderSize = $tableStyle->getBorderInsideHSize();
                $cellVBorderSize = $tableStyle->getBorderInsideVSize();
                if (!is_null($cellHBorderSize)) {
                    $style = ($cellHBorderSize / 8) . 'pt solid #' . $tableStyle->getBorderInsideHColor();
                    $cellStyles[] = 'border-top: ' . $style;
                    $cellStyles[] = 'border-bottom: ' . $style;
                }
                if (!is_null($cellVBorderSize)) {
                    $style = ($cellVBorderSize / 8) . 'pt solid #' . $tableStyle->getBorderInsideVColor();
                    $cellStyles[] = 'border-left: ' . $style;
                    $cellStyles[] = 'border-right: ' . $style;
                }
            }
            $cellStyleString = implode('; ', $cellStyles);

            for ($i = 0; $i < $rowCount; $i++) {
                /** @var $row \PhpOffice\PhpWord\Element\Row Type hint */
                $rowStyle = $rows[$i]->getStyle();
                // $height = $row->getHeight();
                $tblHeader = $rowStyle->isTblHeader();
                $content .= '<tr>' . PHP_EOL;
                $rowCells = $rows[$i]->getCells();
                $rowCellCount = count($rowCells);
                for ($j = 0; $j < $rowCellCount; $j++) {
                    $cellStyle = $rowCells[$j]->getStyle();
                    $cellBgColor = $cellStyle->getBgColor();
                    $cellBgColor === 'auto' && $cellBgColor = null; // auto cannot be parsed to hexadecimal number
                    $cellFgColor = null;
                    if ($cellBgColor) {
                        $red = hexdec(substr($cellBgColor, 0, 2));
                        $green = hexdec(substr($cellBgColor, 2, 2));
                        $blue = hexdec(substr($cellBgColor, 4, 2));
                        $cellFgColor = (($red * 0.299 + $green * 0.587 + $blue * 0.114) > 186) ? null : 'ffffff';
                    }
                    $cellColSpan = $cellStyle->getGridSpan();
                    $cellRowSpan = 1;
                    $cellVMerge = $cellStyle->getVMerge();
                    // If this is the first cell of the vertical merge, find out how man rows it spans
                    if ($cellVMerge === 'restart') {
                        for ($k = $i + 1; $k < $rowCount; $k++) {
                            $kRowCells = $rows[$k]->getCells();
                            if (isset($kRowCells[$j])) {
                                if ($kRowCells[$j]->getStyle()->getVMerge() === 'continue') {
                                    $cellRowSpan++;
                                } else {
                                    break;
                                }
                            } else {
                                break;
                            }
                        }
                    }
                    // Ignore cells that are merged vertically with previous rows
                    if ($cellVMerge !== 'continue') {
                        $cellTag = $tblHeader ? 'th' : 'td';
                        $cellColSpanAttr = (is_numeric($cellColSpan) && ($cellColSpan > 1) ? " colspan=\"{$cellColSpan}\"" : '');
                        $cellRowSpanAttr = ($cellRowSpan > 1 ? " rowspan=\"{$cellRowSpan}\"" : '');
                        $cellBgColorAttr = (is_null($cellBgColor) ? '' : " bgcolor=\"#{$cellBgColor}\"");
                        $cellFgColorAttr = (is_null($cellFgColor) ? '' : " color=\"#{$cellFgColor}\"");

                        $localBorderStyles = array();
                        $cellBorderSizes = $cellStyle->getBorderSize();
                        $cellBorderColors = $cellStyle->getBorderColor();
                        $cellBorderStyles = $cellStyle->getBorderStyle();
                        foreach (array('top', 'left', 'right', 'bottom') as $k => $name) {
                            if ($cellBorderSizes[$k] > 0) {
                                $borderColor = $cellBorderColors[$k];
                                if ($borderColor == 'auto') {
                                    $borderColor = '000';  // TODO: Detect better color
                                }
                                $borderStyle = $cellBorderStyles[$k];
                                if (isset($borderStyleConverter[$borderStyle])) {
                                    $borderStyle = $borderStyleConverter[$borderStyle];
                                } else {
                                    $borderStyle = 'solid';
                                }
                                $localBorderStyles[] = "border-{$name}:" . ($cellBorderSizes[$k] / 8) . 'pt ' . $borderStyle . ' #' . $borderColor;
                            }
                        }
                        $localCellStylesString = $cellStyleString;
                        if ($localBorderStyles) {
                            $localCellStylesString .= '; ' . implode('; ', $localBorderStyles);
                        }
                        $cellStyle = (empty($localCellStylesString) ? '' : " style=\"{$localCellStylesString}\"");

                        $content .= "<{$cellTag}{$cellColSpanAttr}{$cellRowSpanAttr}{$cellBgColorAttr}{$cellFgColorAttr}{$cellStyle}>" . PHP_EOL;
                        $writer = new Container($this->parentWriter, $rowCells[$j]);
                        $content .= $writer->write();
                        if ($cellRowSpan > 1) {
                            // There shouldn't be any content in the subsequent merged cells, but lets check anyway
                            for ($k = $i + 1; $k < $rowCount; $k++) {
                                $kRowCells = $rows[$k]->getCells();
                                if (isset($kRowCells[$j])) {
                                    if ($kRowCells[$j]->getStyle()->getVMerge() === 'continue') {
                                        $writer = new Container($this->parentWriter, $kRowCells[$j]);
                                        $content .= $writer->write();
                                    } else {
                                        break;
                                    }
                                } else {
                                    break;
                                }
                            }
                        }
                        $content .= "</{$cellTag}>" . PHP_EOL;
                    }
                }
                $content .= '</tr>' . PHP_EOL;
            }
            $content .= '</table>' . PHP_EOL;
        }

        return $content;
    }

    /**
     * Translates Table style in CSS equivalent
     *
     * @param string|\PhpOffice\PhpWord\Style\Table|null $tableStyle
     * @return string
     */
    private function getTableStyle($tableStyle = null)
    {
        if ($tableStyle == null) {
            return '';
        }
        if (is_string($tableStyle)) {
            $style = ' class="' . $tableStyle;
        } else {
            $style = ' style="';
            $styleWriter = new TextStyleWriter($tableStyle);
            $style .= $styleWriter->write();
        }

        return $style . '"';
    }
}
