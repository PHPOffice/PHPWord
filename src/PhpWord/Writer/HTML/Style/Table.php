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

namespace PhpOffice\PhpWord\Writer\HTML\Style;

use PhpOffice\PhpWord\SimpleType\Jc;

/**
 * Table style HTML writer
 *
 * @since 0.17.0
 */
class Table extends AbstractStyle
{
    /**
     * Write style
     *
     * @return string
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Table) {
            return '';
        }
        $css = array();

        // Alignment
        if ('' !== $style->getAlignment()) {
            $textAlign = '';

            switch ($style->getAlignment()) {
                case Jc::CENTER:
                    $textAlign = 'center';
                    break;
                case Jc::END:
                case Jc::MEDIUM_KASHIDA:
                case Jc::HIGH_KASHIDA:
                case Jc::LOW_KASHIDA:
                case Jc::RIGHT:
                    $textAlign = 'right';
                    break;
                case Jc::BOTH:
                case Jc::DISTRIBUTE:
                case Jc::THAI_DISTRIBUTE:
                case Jc::JUSTIFY:
                    $textAlign = 'justify';
                    break;
                default: //all others, align left
                    $textAlign = 'left';
                    break;
            }

            $css['text-align'] = $textAlign;
        }

        $bgColor = $style->getBgColor();
        if ($bgColor) {
            $css['background-color'] = $bgColor;
        }

        $sideIdx = array(
            'top'   => 0,
            'right' => 2,
            'bottom'=> 3,
            'left'  => 1,
        );

        $borderSizes = $style->getBorderSize();
        $borderColors = $style->getBorderColor();
        foreach ($sideIdx as $side => $idx) {
            if (!is_null($borderSizes[$idx])) {
                $css['border-' . $side] = ($borderSizes[$idx] / 8) . 'pt solid #' . $borderColors[$idx];
            }
        }

        /*
        $cellMargins = $style->getCellMargin();
        foreach ($sideIdx as $side => $idx) {
            if (!is_null($cellMargins[$idx])) {
                $css['margin-'.$side] = ($cellMargins[$idx]/20).'pt';
            }
        }
        */
        //$cellPadding = $style->getCellPadding();
        //$columnWidths = $style->getColumnWidths();

        $layout = $style->getLayout();
        if ($layout == $style::LAYOUT_FIXED) {
            $css['table-layout'] = 'fixed';
        }

        $width = $style->getWidth();
        if (!is_null($width) && $width > 0) {
            $css['width'] = $width . ($width > 0 ? $style->getUnit() : '');
        }

        return $this->assembleCss($css);
    }
}
