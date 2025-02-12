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
use PhpOffice\PhpWord\Writer\ODText;

/**
 * Test class for PhpOffice\PhpWord\Reader\ODText and PhpOffice\PhpWord\Writer\ODText.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Reader\ODText
 *
 * @runTestsInSeparateProcesses
 */
class ODTextTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test a document with one section and text.
     */
    public function testOneSectionWithText(): void
    {
        $phpWordWriter = new PhpWord();
        $testText = 'Hello World!';
        $sectionWriter = $phpWordWriter->addSection();
        $sectionWriter->addText($testText);

        $writer = new ODText($phpWordWriter);
        $file = __DIR__ . '/../_files/temp.odt';
        $writer->save($file);

        self::assertFileExists($file);

        $phpWordReader = IOFactory::load($file, 'ODText');

        self::assertCount(1, $phpWordReader->getSections());
        self::assertCount(1, $phpWordReader->getSections()[0]->getElements());
        self::assertInstanceOf(TextRun::class, $phpWordReader->getSections()[0]->getElements()[0]);
        self::assertEquals($testText, $phpWordReader->getSections()[0]->getElements()[0]->getText());
        unlink($file);
    }
}
