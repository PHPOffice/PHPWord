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

/**
 * RTF indentation style writer.
 *
 * @since 0.11.0
 */
class Indentation extends AbstractStyle
{
    /**
     * Write style.
     *
     * @return string
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Indentation) {
            return '';
        }

        $content = '';

        $content .= $this->getValueIf($style->getFirstLine() != 0, '\fi' . round($style->getFirstLine()));
        $content .= $this->getValueIf($style->getFirstLineChars() != 0, '\cufi' . round($style->getFirstLineChars()));
        $content .= $this->getValueIf($style->getHanging() != 0, '\fi-' . round($style->getHanging()));
        $content .= $this->getValueIf($style->getLeft() != 0, '\li' . round($style->getLeft()));
        $content .= $this->getValueIf($style->getRight() != 0, '\ri' . round($style->getRight()));

        return $content . ' ';
    }
}
