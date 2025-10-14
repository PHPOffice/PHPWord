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

use PhpOffice\PhpWord\SimpleType\LineSpacingRule;

/**
 * RTF Spacing style writer.
 *
 * @since 0.11.0
 */
class Spacing extends AbstractStyle
{
    /**
     * Write style.
     *
     * @return string
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Spacing) {
            return '';
        }

        $spacingRules = [
            LineSpacingRule::AUTO => '\slmult1',
            LineSpacingRule::EXACT => '\slmult0',
            LineSpacingRule::AT_LEAST => '\slmult0',
        ];

        $content = '';

        // Space Before and After
        $content .= $this->getValueIf($style->getBefore() !== null, '\sb' . round($style->getBefore() ?? 0));
        $content .= $this->getValueIf($style->getAfter() !== null, '\sa' . round($style->getAfter() ?? 0));

        // Space Between Lines
        $line = $style->getLine();
        if (null !== $line && $style->getLineRule() === LineSpacingRule::AUTO) {
            $line += \PhpOffice\PhpWord\Style\Paragraph::LINE_HEIGHT;
        }
        // Exact is specified by using negative numbers
        if ($style->getLineRule() === LineSpacingRule::EXACT && $line > 0) {
            $line = $line * -1;
        }
        $content .= $this->getValueIf($line !== null, '\sl' . round($line ?? 0));

        // Spacing Multiple
        if ($line !== null) {
            $content .= $this->getValueIf(isset($spacingRules[$style->getLineRule()]), $spacingRules[$style->getLineRule()]);
        }

        return $content;
    }
}
