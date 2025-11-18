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

use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\RTF;

/**
 * Test class for PhpOffice\PhpWord\Writer\RTF\Style subnamespace.
 */
class RtlTest extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        Settings::setDefaultRtl(null);
    }

    /**
     * @param RTF\Element\Text $field
     */
    public function removeCr($field): string
    {
        return str_replace("\r\n", "\n", $field->write());
    }

    public function testRTL(): void
    {
        $parentWriter = new RTF();
        $element = new \PhpOffice\PhpWord\Element\Text('אב גד', ['RTL' => true]);
        $text = new RTF\Element\Text($parentWriter, $element);
        $expect = "\\pard\\nowidctlpar {\\rtlch \\uc0\\u1488 \\uc0\\u1489  \\uc0\\u1490 \\uc0\\u1491 }\\par\n";
        self::assertEquals($expect, $this->removeCr($text));
    }

    public function testRTL2(): void
    {
        Settings::setDefaultRtl(true);
        $parentWriter = new RTF();
        $element = new \PhpOffice\PhpWord\Element\Text('אב גד');
        $text = new RTF\Element\Text($parentWriter, $element);
        $expect = "\\pard\\nowidctlpar \\qr{\\rtlch \\uc0\\u1488 \\uc0\\u1489  \\uc0\\u1490 \\uc0\\u1491 }\\par\n";
        self::assertEquals($expect, $this->removeCr($text));
    }

    public function testPageBreakLineHeight2(): void
    {
        Settings::setDefaultRtl(false);
        $parentWriter = new RTF();
        $element = new \PhpOffice\PhpWord\Element\Text('New page', null, ['lineHeight' => 1.08, 'pageBreakBefore' => true]);
        $text = new RTF\Element\Text($parentWriter, $element);
        $expect = "\\pard\\nowidctlpar \\ql\\sl259\\slmult1\\page{\\ltrch New page}\\par\n";
        self::assertEquals($expect, $this->removeCr($text));
    }
}
