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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\HTML\Style;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font as FontStyle;

/**
 * Font style HTML writer
 *
 * @since 0.10.0
 */
class Font extends AbstractStyle
{
    /**
     * Write style
     *
     * @return string
     */
    public function write()
    {
        if (!$this->style instanceof \PhpOffice\PhpWord\Style\Font) {
            return;
        }

        $font = $this->style->getName();
        $size = $this->style->getSize();
        $color = $this->style->getColor();
        $fgColor = $this->style->getFgColor();
        $underline = $this->style->getUnderline() != FontStyle::UNDERLINE_NONE;
        $lineThrough = $this->style->isStrikethrough() || $this->style->isDoubleStrikethrough();

        $css = array();

        $css['font-family'] = $this->getValueIf($font != PhpWord::DEFAULT_FONT_NAME, "'{$font}'");
        $css['font-size'] = $this->getValueIf($size != PhpWord::DEFAULT_FONT_SIZE, "{$size}pt");
        $css['color'] = $this->getValueIf($color != PhpWord::DEFAULT_FONT_COLOR, "#{$color}");
        $css['background'] = $this->getValueIf($fgColor != '', $fgColor);
        $css['font-weight'] = $this->getValueIf($this->style->isBold(), 'bold');
        $css['font-style'] = $this->getValueIf($this->style->isItalic(), 'italic');

        $css['text-decoration'] = '';
        $css['text-decoration'] .= $this->getValueIf($underline, 'underline ');
        $css['text-decoration'] .= $this->getValueIf($lineThrough, 'line-through ');

        if ($this->style->isSuperScript()) {
            $css['vertical-align'] = 'super';
        } elseif ($this->style->isSubScript()) {
            $css['vertical-align'] = 'sub';
        }

        return $this->assembleCss($css);
    }

    /**
     * Get value if ...
     *
     * @param bool $condition
     * @param string $value
     * @return string
     */
    private function getValueIf($condition, $value)
    {
        return $condition ? $value : '';
    }
}
