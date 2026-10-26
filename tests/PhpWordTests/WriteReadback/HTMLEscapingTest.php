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
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\HTML;

/**
 * Test class for PhpOffice\PhpWord\Reader\HTML and PhpOffice\PhpWord\Writer\HTML.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Reader\HTML
 */
class HTMLEscapingTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed after each method of the class.
     */
    protected function tearDown(): void
    {
        Settings::restoreDefaults();
    }

    /**
     * Test a document with one section and text.
     */
    public function testEscaping(): void
    {
        Settings::setOutputEscapingEnabled(true);
        $phpWordWriter = new PhpWord();
        $testText = '6+5 < 12';
        $sectionWriter = $phpWordWriter->addSection();
        $sectionWriter->addText($testText);

        $writer = new HTML($phpWordWriter);
        $file = PHPWORD_TEST_TEMP_DIR . DIRECTORY_SEPARATOR . 'temp.docx';
        $writer->save($file);

        self::assertFileExists($file);

        $phpWordReader = IOFactory::load($file, 'HTML');

        self::assertCount(1, $phpWordReader->getSections());
        self::assertCount(1, $phpWordReader->getSections()[0]->getElements());
        self::assertInstanceOf(TextRun::class, $phpWordReader->getSections()[0]->getElements()[0]);
        self::assertEquals($testText, $phpWordReader->getSections()[0]->getElements()[0]->getText());
        unlink($file);
    }

    /**
     * Test a document with one section and text.
     */
    public function testNoEscapingBad(): void
    {
        // Html Loader is very permissive, so this test,
        // unlike Word2007 and ODText, does not throw an exception.
        Settings::setOutputEscapingEnabled(false);
        $phpWordWriter = new PhpWord();
        $testText = '6+5 < 12';
        $sectionWriter = $phpWordWriter->addSection();
        $sectionWriter->addText($testText);

        $writer = new HTML($phpWordWriter);
        $file = PHPWORD_TEST_TEMP_DIR . DIRECTORY_SEPARATOR . 'temp.docx';
        $writer->save($file);

        self::assertFileExists($file);

        $phpWordReader = IOFactory::load($file, 'HTML');

        self::assertCount(1, $phpWordReader->getSections());
        self::assertCount(1, $phpWordReader->getSections()[0]->getElements());
        self::assertInstanceOf(TextRun::class, $phpWordReader->getSections()[0]->getElements()[0]);
        self::assertEquals($testText, $phpWordReader->getSections()[0]->getElements()[0]->getText());
        unlink($file);
    }

    /**
     * Test a document with one section and text.
     */
    public function testNoEscapingOkay(): void
    {
        Settings::setOutputEscapingEnabled(false);
        $phpWordWriter = new PhpWord();
        $testText = '6+5 &lt; 12';
        $testTextOut = '6+5 < 12';
        $sectionWriter = $phpWordWriter->addSection();
        $sectionWriter->addText($testText);

        $writer = new HTML($phpWordWriter);
        $file = PHPWORD_TEST_TEMP_DIR . DIRECTORY_SEPARATOR . 'temp.docx';
        $writer->save($file);

        self::assertFileExists($file);

        $phpWordReader = IOFactory::load($file, 'HTML');

        self::assertCount(1, $phpWordReader->getSections());
        self::assertCount(1, $phpWordReader->getSections()[0]->getElements());
        self::assertInstanceOf(TextRun::class, $phpWordReader->getSections()[0]->getElements()[0]);
        self::assertEquals($testTextOut, $phpWordReader->getSections()[0]->getElements()[0]->getText());
        unlink($file);
    }
}
