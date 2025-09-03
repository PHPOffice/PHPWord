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

namespace PhpOffice\PhpWordTests\Writer\HTML;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Writer\HTML;

/**
 * Test class for PhpOffice\PhpWord\Writer\RTF\Style subnamespace.
 */
class Sample11Test extends \PHPUnit\Framework\TestCase
{
    public function testSample11(): void
    {
        $source = 'samples/resources/Sample_11_ReadWord2007.docx';
        $phpWord = IOFactory::load($source);
        $writer = new HTML($phpWord);
        $content = $writer->getContent();
        $expected = '<span class="ChangedFontStyleChar">source file</span>';
        self::assertStringContainsString($expected, $content);
        $expected = '<span class="ChangedFontStyleChar">source file</span>';
        self::assertStringContainsString($expected, $content);
        $expected = 'even </del>';
        self::assertStringContainsString($expected, $content);
    }
}
