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
 * Line numbering style writer.
 *
 * @since 0.10.0
 */
class Tab extends AbstractStyle
{
    /**
     * Write style.
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Tab) {
            return;
        }
        $tabs = [
            \PhpOffice\PhpWord\Style\Tab::TAB_STOP_RIGHT => '\tqr',
            \PhpOffice\PhpWord\Style\Tab::TAB_STOP_CENTER => '\tqc',
            \PhpOffice\PhpWord\Style\Tab::TAB_STOP_DECIMAL => '\tqdec',
            \PhpOffice\PhpWord\Style\Tab::TAB_LEADER_DOT => '\tldot',
            \PhpOffice\PhpWord\Style\Tab::TAB_LEADER_HYPHEN => '\tlhyph',
            \PhpOffice\PhpWord\Style\Tab::TAB_LEADER_UNDERSCORE => '\tlul',
            \PhpOffice\PhpWord\Style\Tab::TAB_LEADER_HEAVY => '\tlth',
            \PhpOffice\PhpWord\Style\Tab::TAB_LEADER_MIDDLEDOT => '\tlmdot',
        ];
        $content = '';
        if (isset($tabs[$style->getType()])) {
            $content .= $tabs[$style->getType()];
        }
        if (isset($tabs[$style->getLeader()])) {
            $content .= $tabs[$style->getLeader()];
        }
        if ($style->getType() == \PhpOffice\PhpWord\Style\Tab::TAB_STOP_BAR) {
            $content .= '\tb' . round($style->getPosition());
        } else {
            $content .= '\tx' . round($style->getPosition());
        }
        return $content;
    }
}
