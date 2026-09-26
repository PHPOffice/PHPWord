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

namespace PhpOffice\PhpWordTests\Writer\RTF\Style;

use PhpOffice\PhpWord\SimpleType\LineSpacingRule;
use PhpOffice\PhpWord\Style\Spacing as SpacingStyle;
use PhpOffice\PhpWord\Writer\RTF;
use PhpOffice\PhpWord\Writer\RTF\Style\Spacing as SpacingWriter;
use PHPUnit\Framework\TestCase;

class SpacingTest extends TestCase
{
    public function removeCr(SpacingWriter $field): string
    {
        return str_replace("\r\n", "\n", $field->write());
    }

    public function testSpacing(): void
    {
        $parentWriter = new RTF();
        $style = new SpacingStyle();
        $writer = new SpacingWriter($style);
        $writer->setParentWriter($parentWriter);

        $style->setLineRule(LineSpacingRule::EXACT);
        $style->setLine(5);
        $expect = '\sl-5\slmult0';
        self::assertEquals($expect, $this->removeCr($writer));
    }
}
