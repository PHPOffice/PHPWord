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

namespace PhpOffice\PhpWordTests\Writer\RTF;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Writer\RTF;

/**
 * Test class for PhpOffice\PhpWord\Writer\RTF\Style subnamespace.
 */
class Sample11Test extends \PHPUnit\Framework\TestCase
{
    public function testSample11(): void
    {
        $source = 'samples/resources/Sample_11_ReadWord2007.docx';
        $phpWord = IOFactory::load($source);
        $writer = new RTF($phpWord);
        $content = $writer->getContent();
        $expected = '{\colortbl;\red255\green0\blue0;\red0\green0\blue0;\red0\green0\blue255;\red0\green176\blue80;\red255\green255\blue0;}';
        self::assertStringContainsString($expected, $content);
        $expected = '\highlight5 highlighted';
        self::assertStringContainsString($expected, $content);
        $expected = '\strike even ';
        self::assertStringContainsString($expected, $content);
    }
}
