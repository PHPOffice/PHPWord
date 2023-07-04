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

namespace PhpOffice\PhpWord\Writer\HTML\Style;

use PhpOffice\PhpWord\SimpleType\Jc;

/**
 * Paragraph style HTML writer.
 *
 * @since 0.10.0
 */
class Paragraph extends AbstractStyle
{
    /**
     * Write style.
     *
     * @return string
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Paragraph) {
            return '';
        }
        $css = [];

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

        //　Indentation
        $indentation = $style->getIndentation();
        if (null !== $indentation) {
            $firstLine = $indentation->getFirstLine();
            $hanging = $indentation->getHanging();
            $left = $indentation->getLeft();
            $right = $indentation->getRight();
            if ($firstLine !== null) {
                $css['text-indent'] = ($firstLine / 20) . 'pt';
            }
            if ($hanging !== null) {
                $css['text-indent'] = '-'.($hanging / 20) . 'pt';
            }
            if ($left !== null) {
                $css['margin-left'] = ($left / 20) . 'pt';
            }
            if ($right !== null) {
                $css['margin-right'] = ($right / 20) . 'pt';
            }
        }

        // Spacing
        $spacing = $style->getSpace();

        if (null !== $spacing) {
            $line = $spacing->getLine();
            $before = $spacing->getBefore();
            $after = $spacing->getAfter();

            if ($line !== null) {
                $lineRule = $spacing->getLineRule();
                switch ($lineRule) {
                    case 'auto' :
                        $css['line-height'] = number_format(($line / 20), 2) . 'pt';
                        break;
                    case 'atLeast' :
                        $css['line-height'] = 'calc(100%+' . ($line / 20) . 'pt)';
                        break;
                    case 'auto' :
                        $css['line-height'] = $css["min-height"]  = ($line / 20). 'pt';
                        break;

                }
            }

            $css['margin-top'] = $this->getValueIf(null !== $before, ($before / 20) . 'pt');
            $css['margin-bottom'] = $this->getValueIf(null !== $after, ($after / 20) . 'pt');
        } else {
            $css['margin-top'] = '0';
            $css['margin-bottom'] = '0';
        }

        return $this->assembleCss($css);
    }
}
