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

namespace PhpOffice\PhpWordTests\WriteReadback;

use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\RTF;

class RtfBackslashTest extends \PHPUnit\Framework\TestCase
{
    public function testBackslash(): void
    {
        $phpWordWriter = new PhpWord();
        $textWithBackslash = 'Hello\\World!';
        $sectionWriter = $phpWordWriter->addSection();
        $sectionWriter->addText($textWithBackslash);

        $writer = new RTF($phpWordWriter);
        $file = __DIR__ . '/../_files/temp.rtf';
        $writer->save($file);
        self::assertFileExists($file);
        $phpWordReader = IOFactory::load($file, 'RTF');
        unlink($file);

        $sections = $phpWordReader->getSections();
        self::assertCount(1, $sections);
        $elements = $sections[0]->getElements();
        $element = $elements[0];
        self::assertInstanceOf(TextRun::class, $element);
        self::assertSame($textWithBackslash, $element->getText());
    }
}
