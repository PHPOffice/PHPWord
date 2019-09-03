<?php
declare(strict_types=1);
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

namespace PhpOffice\PhpWord\Writer\RTF\Style;

use PhpOffice\PhpWord\Style\Section as SectionStyle;

/**
 * RTF section style writer
 *
 * @since 0.12.0
 */
class Section extends AbstractStyle
{
    /**
     * Write style
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
        $content .= $this->getValueIf($style->getPageSizeW()->toInt('twip') !== null, '\pgwsxn' . $style->getPageSizeW()->toInt('twip'));
        $content .= $this->getValueIf($style->getPageSizeH()->toInt('twip') !== null, '\pghsxn' . $style->getPageSizeH()->toInt('twip'));
        $content .= ' ';
        $content .= $this->getValueIf($style->getMarginTop()->toInt('twip') !== null, '\margtsxn' . $style->getMarginTop()->toInt('twip'));
        $content .= $this->getValueIf($style->getMarginRight()->toInt('twip') !== null, '\margrsxn' . $style->getMarginRight()->toInt('twip'));
        $content .= $this->getValueIf($style->getMarginBottom()->toInt('twip') !== null, '\margbsxn' . $style->getMarginBottom()->toInt('twip'));
        $content .= $this->getValueIf($style->getMarginLeft()->toInt('twip') !== null, '\marglsxn' . $style->getMarginLeft()->toInt('twip'));
        $content .= $this->getValueIf($style->getHeaderHeight()->toInt('twip') !== null, '\headery' . $style->getHeaderHeight()->toInt('twip'));
        $content .= $this->getValueIf($style->getFooterHeight()->toInt('twip') !== null, '\footery' . $style->getFooterHeight()->toInt('twip'));
        $content .= $this->getValueIf($style->getGutter()->toInt('twip') !== null, '\guttersxn' . $style->getGutter()->toInt('twip'));
        $content .= ' ';

        // Borders
        if ($style->hasBorder()) {
            $styleWriter = new Border($style);
            $styleWriter->setParentWriter($this->getParentWriter());
            $styleWriter->setBorders($style->getBorders());
            $content .= $styleWriter->write();
        }

        return $content . PHP_EOL;
    }
}
