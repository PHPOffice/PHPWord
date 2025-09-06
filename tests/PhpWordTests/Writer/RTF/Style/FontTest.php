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
 * Test class for PhpOffice\PhpWord\Writer\RTF\Style\Font.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\RTF\Style\Font
 *
 * @runTestsInSeparateProcesses
 */
class FontTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        Settings::setDefaultRtl(null);
    }

    /** @param WriterTextRun|WriterTitle $field */
    public function removeCr($field): string
    {
        return str_replace("\r\n", "\n", $field->write());
    }

    /**
     * Test basic font settings.
     */
    public function testFontBasics(): void
    {
        $font = new \PhpOffice\PhpWord\Style\Font();
        $font->setName('Times New Roman');
        $font->setSize(22);
        $font->setColor('yellow');
        // $font->setHint('test');

        $writer = new RTF\Style\Font($font);
        $writer->setParentWriter(new RTF());
        $result = $writer->write();

        Assert::assertEquals('\f0\fs22\c0', $result);
    }
}
