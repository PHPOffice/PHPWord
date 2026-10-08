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
use PhpOffice\PhpWord\Exception\Exception as WordException;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\ODText;

/**
 * Test class for PhpOffice\PhpWord\Reader\ODText and PhpOffice\PhpWord\Writer\ODText.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Reader\ODText
 */
class ODTextEscapingTest extends \PHPUnit\Framework\TestCase
{
    /** @var string */
    private $fileName = '';

    /**
     * Executed after each method of the class.
     */
    protected function tearDown(): void
    {
        if ($this->fileName !== '') {
            unlink($this->fileName);
            $this->fileName = '';
        }
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

        $writer = new ODText($phpWordWriter);
        $this->fileName = PHPWORD_TEST_TEMP_DIR . DIRECTORY_SEPARATOR . 'escaping1.odt';
        $writer->save($this->fileName);

        self::assertFileExists($this->fileName);

        $phpWordReader = IOFactory::load($this->fileName, 'ODText');

        self::assertCount(1, $phpWordReader->getSections());
        self::assertCount(1, $phpWordReader->getSections()[0]->getElements());
        self::assertInstanceOf(TextRun::class, $phpWordReader->getSections()[0]->getElements()[0]);
        self::assertEquals($testText, $phpWordReader->getSections()[0]->getElements()[0]->getText());
    }

    /**
     * Test a document with one section and text.
     */
    public function testNoEscapingBad(): void
    {
        $this->expectException(WordException::class);
        $this->expectExceptionMessage('StartTag');
        Settings::setOutputEscapingEnabled(false);
        $phpWordWriter = new PhpWord();
        $testText = '6+5 < 12';
        $sectionWriter = $phpWordWriter->addSection();
        $sectionWriter->addText($testText);

        $writer = new ODText($phpWordWriter);
        $this->fileName = PHPWORD_TEST_TEMP_DIR . DIRECTORY_SEPARATOR . 'escaping2.odt';
        $writer->save($this->fileName);

        self::assertFileExists($this->fileName);

        IOFactory::load($this->fileName, 'ODText');
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

        $writer = new ODText($phpWordWriter);
        $this->fileName = PHPWORD_TEST_TEMP_DIR . DIRECTORY_SEPARATOR . 'temp.docx';
        $writer->save($this->fileName);

        self::assertFileExists($this->fileName);

        $phpWordReader = IOFactory::load($this->fileName, 'ODText');

        self::assertCount(1, $phpWordReader->getSections());
        self::assertCount(1, $phpWordReader->getSections()[0]->getElements());
        self::assertInstanceOf(TextRun::class, $phpWordReader->getSections()[0]->getElements()[0]);
        self::assertEquals($testTextOut, $phpWordReader->getSections()[0]->getElements()[0]->getText());
    }
}
