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

namespace PhpOffice\PhpWord\Writer\RTF\Style;

use PhpOffice\PhpWord\SimpleType\VerticalJc;
use PhpOffice\PhpWord\Style\Section as SectionStyle;

/**
 * RTF section style writer.
 *
 * @since 0.12.0
 */
class Section extends AbstractStyle
{
    /**
     * Write style.
     *
     * @return string
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof SectionStyle) {
            return '';
        }

        $content = '';

        $content .= '\sectd ';

        // Size & margin
        if ($this->getParentWriter() !== null && $this->getParentWriter()->getPhpWord()->getSettings()->hasBookFoldPrinting()) {
            $style->setOrientation(SectionStyle::ORIENTATION_LANDSCAPE);
            $content .= $this->getValueIf($style->getPageSizeW() !== null, '\pgwsxn' . round($style->getPageSizeW()) / 2);
            $content .= $this->getValueIf($style->getPageSizeH() !== null, '\pghsxn' . round($style->getPageSizeH()));
        } else {
            $content .= $this->getValueIf($style->getPageSizeW() !== null, '\pgwsxn' . round($style->getPageSizeW()));
            $content .= $this->getValueIf($style->getPageSizeH() !== null, '\pghsxn' . round($style->getPageSizeH()));
        }
        $content .= ' ';

        $content .= $this->getValueIf($style->getMarginTop() !== null, '\margtsxn' . round($style->getMarginTop()));
        $content .= $this->getValueIf($style->getMarginRight() !== null, '\margrsxn' . round($style->getMarginRight()));
        $content .= $this->getValueIf($style->getMarginBottom() !== null, '\margbsxn' . round($style->getMarginBottom()));
        $content .= $this->getValueIf($style->getMarginLeft() !== null, '\marglsxn' . round($style->getMarginLeft()));
        $content .= $this->getValueIf($style->getHeaderHeight() !== null, '\headery' . round($style->getHeaderHeight()));
        $content .= $this->getValueIf($style->getFooterHeight() !== null, '\footery' . round($style->getFooterHeight()));
        $content .= $this->getValueIf($style->getGutter() !== null && $style->getGutter() !== SectionStyle::DEFAULT_GUTTER, '\guttersxn' . round($style->getGutter()));
        $content .= $this->getValueIf($style->getPageNumberingStart() !== null, '\pgnstarts' . $style->getPageNumberingStart() . '\pgnrestart');

        $content .= $this->getValueIf($style->getColsNum() !== null && $style->getColsNum() !== SectionStyle::DEFAULT_COLUMN_COUNT, '\cols' . $style->getColsNum());
        $content .= $this->getValueIf($style->getColsSpace() !== null && $style->getColsNum() !== SectionStyle::DEFAULT_COLUMN_COUNT, '\colsx' . round($style->getColsSpace()));

        // Break Type
        $breakTypes = [
            'nextPage' => '\sbkpage',
            'nextColumn' => '\sbkcol',
            'continuous' => '\sbknone',
            'evenPage' => '\sbkeven',
            'oddPage' => '\sbkodd',
        ];
        $content .= $breakTypes[(string) $style->getBreakType()] ?? '';

        // Vertical Align
        $verticalAlign = [
            VerticalJc::TOP => '\vertalt',
            VerticalJc::CENTER => '\vertalc',
            VerticalJc::BOTH => '\vertalj',
            VerticalJc::BOTTOM => '\vertalb',
        ];
        $content .= $verticalAlign[(string) $style->getVAlign()] ?? '';
        $content .= ' ';

        // Borders
        if ($style->hasBorder()) {
            $styleWriter = new Border($style);
            $styleWriter->setParentWriter($this->getParentWriter());
            $styleWriter->setSizes($style->getBorderSize());
            $styleWriter->setColors($style->getBorderColor());
            $content .= $styleWriter->write();
        }

        return $content . PHP_EOL;
    }
}
