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
        if (!($this->style instanceof \PhpOffice\PhpWord\Style\Font)) {
            return;
        }

        $css = array();
        if (PhpWord::DEFAULT_FONT_NAME != $this->style->getName()) {
            $css['font-family'] = "'" . $this->style->getName() . "'";
        }
        if (PhpWord::DEFAULT_FONT_SIZE != $this->style->getSize()) {
            $css['font-size'] = $this->style->getSize() . 'pt';
        }
        if (PhpWord::DEFAULT_FONT_COLOR != $this->style->getColor()) {
            $css['color'] = '#' . $this->style->getColor();
        }
        $css['background'] = $this->style->getFgColor();
        if ($this->style->isBold()) {
            $css['font-weight'] = 'bold';
        }
        if ($this->style->isItalic()) {
            $css['font-style'] = 'italic';
        }
        if ($this->style->isSuperScript()) {
            $css['vertical-align'] = 'super';
        } elseif ($this->style->isSubScript()) {
            $css['vertical-align'] = 'sub';
        }
        $css['text-decoration'] = '';
        if ($this->style->getUnderline() != FontStyle::UNDERLINE_NONE) {
            $css['text-decoration'] .= 'underline ';
        }
        if ($this->style->isStrikethrough()) {
            $css['text-decoration'] .= 'line-through ';
        }

        return $this->assembleCss($css);
    }
}
