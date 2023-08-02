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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\HTML\Element;

/**
 * Table element HTML writer.
 *
 * @since 0.10.0
 */
class Table extends AbstractElement
{
    /**
     * Write table.
     *
     * @return string
     */
    public function write()
    {
        if (!$this->element instanceof \PhpOffice\PhpWord\Element\Table) {
            return '';
        }

        $content = '';
        $rows = $this->element->getRows();
        $rowCount = count($rows);
        if ($rowCount > 0) {
            $content .= '<table' . self::getTableStyle($this->element->getStyle()) . '>' . PHP_EOL;

            for ($i = 0; $i < $rowCount; ++$i) {
                /** @var \PhpOffice\PhpWord\Element\Row $row Type hint */
                $rowStyle = $rows[$i]->getStyle();
                // $height = $row->getHeight();
                $tblHeader = $rowStyle->isTblHeader();
                $content .= '<tr>' . PHP_EOL;
                $rowCells = $rows[$i]->getCells();
                $rowCellCount = count($rowCells);
                for ($j = 0; $j < $rowCellCount; ++$j) {
                    $cellStyle = $rowCells[$j]->getStyle();
                    $cellStyleCss = self::getTableStyle($cellStyle);
                    $cellBgColor = $cellStyle->getBgColor();
                    $cellFgColor = null;
                    if ($cellBgColor && $cellBgColor !== 'auto') {
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
                        for ($k = $i + 1; $k < $rowCount; ++$k) {
                            $kRowCells = $rows[$k]->getCells();
                            if (isset($kRowCells[$j]) && $kRowCells[$j]->getStyle()->getVMerge() === 'continue') {
                                ++$cellRowSpan;
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
                        $cellBgColorAttr = (empty($cellBgColor) ? '' : " bgcolor=\"#{$cellBgColor}\"");
                        $cellFgColorAttr = (empty($cellFgColor) ? '' : " color=\"#{$cellFgColor}\"");
                        $content .= "<{$cellTag}{$cellStyleCss}{$cellColSpanAttr}{$cellRowSpanAttr}{$cellBgColorAttr}{$cellFgColorAttr}>" . PHP_EOL;
                        $writer = new Container($this->parentWriter, $rowCells[$j]);
                        $content .= $writer->write();
                        if ($cellRowSpan > 1) {
                            // There shouldn't be any content in the subsequent merged cells, but lets check anyway
                            for ($k = $i + 1; $k < $rowCount; ++$k) {
                                $kRowCells = $rows[$k]->getCells();
                                if (isset($kRowCells[$j]) && $kRowCells[$j]->getStyle()->getVMerge() === 'continue') {
                                    $writer = new Container($this->parentWriter, $kRowCells[$j]);
                                    $content .= $writer->write();
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
     * Translates Table style in CSS equivalent.
     *
     * @param null|\PhpOffice\PhpWord\Style\Cell|\PhpOffice\PhpWord\Style\Table|string $tableStyle
     *
     * @return string
     */
    private static function getTableStyle($tableStyle = null)
    {
        if ($tableStyle == null) {
            return '';
        }
        if (is_string($tableStyle)) {
            $style = ' class="' . $tableStyle;

            return $style . '"';
        }

        $style = self::getTableStyleString($tableStyle);
        if ($style === '') {
            return '';
        }

        return ' style="' . $style . '"';
    }

    /**
     * Translates Table style in CSS equivalent.
     *
     * @param \PhpOffice\PhpWord\Style\Cell|\PhpOffice\PhpWord\Style\Table|string $tableStyle
     *
     * @return string
     */
    public static function getTableStyleString($tableStyle)
    {
        $style = '';
        if (is_object($tableStyle) && method_exists($tableStyle, 'getLayout')) {
            if ($tableStyle->getLayout() == \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED) {
                $style .= 'table-layout: fixed;';
            } elseif ($tableStyle->getLayout() == \PhpOffice\PhpWord\Style\Table::LAYOUT_AUTO) {
                $style .= 'table-layout: auto;';
            }
        }
        if (is_object($tableStyle) && method_exists($tableStyle, 'isBidiVisual')) {
            if ($tableStyle->isBidiVisual()) {
                $style .= ' direction: rtl;';
            }
        }

        $dirs = ['Top', 'Left', 'Bottom', 'Right'];
        $testmethprefix = 'getBorder';
        foreach ($dirs as $dir) {
            $testmeth = $testmethprefix . $dir . 'Style';
            if (method_exists($tableStyle, $testmeth)) {
                $outval = $tableStyle->{$testmeth}();
                if ($outval === 'single') {
                    $outval = 'solid';
                }
                if (is_string($outval) && 1 == preg_match('/^[a-z]+$/', $outval)) {
                    $style .= ' border-' . lcfirst($dir) . '-style: ' . $outval . ';';
                }
            }
            $testmeth = $testmethprefix . $dir . 'Color';
            if (method_exists($tableStyle, $testmeth)) {
                $outval = $tableStyle->{$testmeth}();
                if (is_string($outval) && 1 == preg_match('/^[a-z]+$/', $outval)) {
                    $style .= ' border-' . lcfirst($dir) . '-color: ' . $outval . ';';
                }
            }
            $testmeth = $testmethprefix . $dir . 'Size';
            if (method_exists($tableStyle, $testmeth)) {
                $outval = $tableStyle->{$testmeth}();
                if (is_numeric($outval)) {
                    // size is in twips - divide by 20 to get points
                    $style .= ' border-' . lcfirst($dir) . '-width: ' . ((string) ($outval / 20)) . 'pt;';
                }
            }
        }

        return $style;
    }
}
