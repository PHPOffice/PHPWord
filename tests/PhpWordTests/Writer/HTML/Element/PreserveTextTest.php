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

namespace PhpOffice\PhpWordTests\Writer\HTML\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\HTML;
use PHPUnit\Framework\TestCase;

class PreserveTextTest extends TestCase
{
    public function testPreserveText(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $text1 = 'This text is missing in the HTML output.';
        $section->addPreserveText($text1);
        $text2 = 'Likewise page {PAGE} of {NUMPAGES}';
        $section->addPreserveText($text2);
        $writer = new HTML($phpWord);
        $content = $writer->getContent();
        $expected = "<p>$text1</p>";
        self::assertStringContainsString($expected, $content);
        $expected = "<p>$text2</p>";
        self::assertStringContainsString($expected, $content);
    }
}
