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
                    $textAlign = ($style->isBidi()) ? 'left' : 'right';

                    break;
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
                case Jc::LEFT:
                    $textAlign = 'left';

                    break;

                default: //all others, including Jc::START
                    $textAlign = ($style->isBidi()) ? 'right' : 'left';

                    break;
            }

            $css['text-align'] = $textAlign;
        }

        // Spacing
        $spacing = $style->getSpace();
        if (null !== $spacing) {
            $before = $spacing->getBefore();
            $after = $spacing->getAfter();
            $css['margin-top'] = $this->getValueIf(null !== $before, ($before / 20) . 'pt');
            $css['margin-bottom'] = $this->getValueIf(null !== $after, ($after / 20) . 'pt');
        }

        $lht = $style->getLineHeight();
        if (!empty($lht)) {
            $css['line-height'] = $lht;
        }
        $ind = $style->getIndentation();
        if ($ind != null) {
            $tcpdf = $this->getParentWriter()->isTcpdf();
            $left = $ind->getLeft();
            $inches = $left * 1.0 / \PhpOffice\PhpWord\Shared\Converter::INCH_TO_TWIP;
            $css[$tcpdf ? 'text-indent' : 'margin-left'] = ((string) $inches) . 'in';
            $left = $ind->getRight();
            $inches = $left * 1.0 / \PhpOffice\PhpWord\Shared\Converter::INCH_TO_TWIP;
            $css['margin-right'] = ((string) $inches) . 'in';
        }
        if ($style->hasPageBreakBefore()) {
            $css['page-break-before'] = 'always';
        }
        if ($style->isBidi()) {
            $css['direction'] = 'rtl';
        }

        return $this->assembleCss($css);
    }
}
