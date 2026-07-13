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

namespace PhpOffice\PhpWordTests\Writer\RTF\Element;

use PhpOffice\PhpWord\Element\TextBreak as TextBreakElement;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\RTF;
use PhpOffice\PhpWord\Writer\RTF\Element\TextBreak as TextBreakWriter;
use PHPUnit\Framework\TestCase;

class TextBreakTest extends TestCase
{
    protected function tearDown(): void
    {
        Settings::setDefaultRtl(null);
    }

    /**
     * @param TextBreakWriter $field
     */
    public function removeCr($field): string
    {
        return str_replace("\r\n", "\n", $field->write());
    }

    /**
     * Test a normal textBreak.
     * See page 142-143 of RTF Specification 1.9.1.
     */
    public function testTextBreakParagraph(): void
    {
        $parentWriter = new RTF();
        $element = new TextBreakElement();
        $writer = new TextBreakWriter($parentWriter, $element);
        $expect = "\\pard\\par\n";
        self::assertEquals($expect, $this->removeCr($writer));
    }

    /**
     * Test a textBreak as a line break.
     * See page 142-143 of RTF Specification 1.9.1.
     */
    public function testTextBreakLine(): void
    {
        $parentWriter = new RTF();
        $element = new TextBreakElement();
        $writer = new TextBreakWriter($parentWriter, $element, true);
        $expect = "\\line\n";
        self::assertEquals($expect, $this->removeCr($writer));
    }
}
