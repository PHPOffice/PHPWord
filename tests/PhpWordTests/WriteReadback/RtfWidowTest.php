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

/**
 * Test class for PhpOffice\PhpWord\Reader\RTF and PhpOffice\PhpWord\Writer\RTF.
 */
class RtfWidowTest extends \PHPUnit\Framework\TestCase
{
    public function testTrueWidowControl(): void
    {
        $phpWordWriter = new PhpWord();
        $phpWordWriter->getSettings()->setRtfWidowControl(true);
        $testText = 'Hello World!';
        $sectionWriter = $phpWordWriter->addSection();
        $sectionWriter->addText($testText);

        $writer = new RTF($phpWordWriter);
        $file = __DIR__ . '/../_files/temp.rtf';
        $writer->save($file);

        self::assertFileExists($file);

        $phpWordReader = IOFactory::load($file, 'RTF');
        unlink($file);

        self::assertTrue($phpWordReader->getSettings()->hasRtfWidowControl());
    }

    public function testDefaultWidowControl(): void
    {
        $phpWordWriter = new PhpWord();
        $testText = 'Hello World!';
        $sectionWriter = $phpWordWriter->addSection();
        $sectionWriter->addText($testText);

        $writer = new RTF($phpWordWriter);
        $file = __DIR__ . '/../_files/temp.rtf';
        $writer->save($file);

        self::assertFileExists($file);

        $phpWordReader = IOFactory::load($file, 'RTF');
        unlink($file);

        self::assertCount(1, $phpWordReader->getSections());
        self::assertCount(2, $phpWordReader->getSections()[0]->getElements());
        self::assertInstanceOf(TextRun::class, $phpWordReader->getSections()[0]->getElements()[0]);
        self::assertEquals($testText, $phpWordReader->getSections()[0]->getElements()[0]->getText());
        self::assertFalse($phpWordReader->getSettings()->hasRtfWidowControl());
    }
}
