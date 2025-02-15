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
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Writer\Word2007;

/**
 * Test class for PhpOffice\PhpWord\Reader\Word2007 and PhpOffice\PhpWord\Writer\Word2007.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Reader\Word2007
 *
 * @runTestsInSeparateProcesses
 */
class Word2007Test extends \PHPUnit\Framework\TestCase
{
    /**
     * Test default font name.
     */
    public function testDefaultFontName(): void
    {
        $phpWordWriter = new PhpWord();
        $testDefaultFontName = 'Times New Roman';
        $phpWordWriter->setDefaultFontName($testDefaultFontName);

        $writer = new Word2007($phpWordWriter);
        $file = __DIR__ . '/../_files/temp.docx';
        $writer->save($file);

        self::assertFileExists($file);

        $phpWordReader = IOFactory::load($file, 'Word2007');

        self::assertEquals($testDefaultFontName, $phpWordReader->getDefaultFontName());

        unlink($file);
    }

    /**
     * Test default Asian font name.
     */
    public function testDefaultAsianFontName(): void
    {
        $phpWordWriter = new PhpWord();
        $testDefaultFontName = '標楷體';
        $phpWordWriter->setDefaultAsianFontName($testDefaultFontName);

        $writer = new Word2007($phpWordWriter);
        $file = __DIR__ . '/../_files/temp.docx';
        $writer->save($file);

        self::assertFileExists($file);

        $phpWordReader = IOFactory::load($file, 'Word2007');

        self::assertEquals($testDefaultFontName, $phpWordReader->getDefaultAsianFontName());

        unlink($file);
    }

    /**
     * Test default font size.
     */
    public function testDefaulFontSize(): void
    {
        $phpWordWriter = new PhpWord();
        $testDefaultFontSize = 144;
        $phpWordWriter->setDefaultFontSize($testDefaultFontSize);

        $writer = new Word2007($phpWordWriter);
        $file = __DIR__ . '/../_files/temp.docx';
        $writer->save($file);

        self::assertFileExists($file);

        $phpWordReader = IOFactory::load($file, 'Word2007');

        self::assertEquals($testDefaultFontSize, $phpWordReader->getDefaultFontSize());

        unlink($file);
    }

    /**
     * Test default font color.
     */
    public function testDefaultFontColor(): void
    {
        $phpWordWriter = new PhpWord();
        $testDefaultFontColor = '00FF00';
        $phpWordWriter->setDefaultFontColor($testDefaultFontColor);

        $writer = new Word2007($phpWordWriter);
        $file = __DIR__ . '/../_files/temp.docx';
        $writer->save($file);

        self::assertFileExists($file);

        $phpWordReader = IOFactory::load($file, 'Word2007');

        self::assertEquals($testDefaultFontColor, $phpWordReader->getDefaultFontColor());

        unlink($file);
    }

    /**
     * Test Zoom.
     */
    public function testZoom(): void
    {
        $phpWordWriter = new PhpWord();
        $zoomLevel = 75;
        $phpWordWriter->getSettings()->setZoom($zoomLevel);

        $writer = new Word2007($phpWordWriter);
        $file = __DIR__ . '/../_files/temp.docx';
        $writer->save($file);

        self::assertFileExists($file);

        $phpWordReader = IOFactory::load($file, 'Word2007');

        self::assertEquals($zoomLevel, $phpWordReader->getSettings()->getZoom());

        unlink($file);
    }

    /**
     * Test a document with one section and text.
     */
    public function testOneSectionWithText(): void
    {
        $phpWordWriter = new PhpWord();
        $testText = 'Hello World!';
        $sectionWriter = $phpWordWriter->addSection();
        $sectionWriter->addText($testText);

        $writer = new Word2007($phpWordWriter);
        $file = __DIR__ . '/../_files/temp.docx';
        $writer->save($file);

        self::assertFileExists($file);

        $phpWordReader = IOFactory::load($file, 'Word2007');

        self::assertCount(1, $phpWordReader->getSections());
        self::assertCount(1, $phpWordReader->getSections()[0]->getElements());
        self::assertInstanceOf(TextRun::class, $phpWordReader->getSections()[0]->getElements()[0]);
        self::assertEquals($testText, $phpWordReader->getSections()[0]->getElements()[0]->getText());
        unlink($file);
    }
}
