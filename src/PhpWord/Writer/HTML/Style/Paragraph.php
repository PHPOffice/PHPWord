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
 * Paragraph style HTML writer
 *
 * @since 0.10.0
 */
class Paragraph extends AbstractStyle
{
    /**
     * Write style
     *
     * @return string
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Paragraph) {
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

        // Spacing
        $spacing = $style->getSpace();
        if (!is_null($spacing)) {
            $before = $spacing->getBefore();
            $after = $spacing->getAfter();
            $css['margin-top'] = $this->getValueIf(!is_null($before), ($before / 20) . 'pt');
            $css['margin-bottom'] = $this->getValueIf(!is_null($after), ($after / 20) . 'pt');
        } else {
            $css['margin-top'] = '0';
            $css['margin-bottom'] = '0';
        }

        return $this->assembleCss($css);
    }
}
