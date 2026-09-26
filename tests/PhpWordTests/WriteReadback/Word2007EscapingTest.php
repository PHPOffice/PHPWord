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
use PhpOffice\PhpWord\Writer\Word2007;

/**
 * Test class for PhpOffice\PhpWord\Reader\Word2007 and PhpOffice\PhpWord\Writer\Word2007.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Reader\Word2007
 */
class Word2007EscapingTest extends \PHPUnit\Framework\TestCase
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

        $writer = new Word2007($phpWordWriter);
        $this->fileName = PHPWORD_TEST_TEMP_DIR . DIRECTORY_SEPARATOR . 'escaping`.docx';
        $writer->save($this->fileName);
        self::assertFileExists($this->fileName);

        $phpWordReader = IOFactory::load($this->fileName, 'Word2007');

        self::assertCount(1, $phpWordReader->getSections());
        self::assertCount(1, $phpWordReader->getSections()[0]->getElements());
        self::assertInstanceOf(TextRun::class, $phpWordReader->getSections()[0]->getElements()[0]);
        // we had been getting 6+5 &lt; 12, but that's a bug
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

        $writer = new Word2007($phpWordWriter);
        $this->fileName = PHPWORD_TEST_TEMP_DIR . DIRECTORY_SEPARATOR . 'escaping2.docx';
        $writer->save($this->fileName);

        self::assertFileExists($this->fileName);

        IOFactory::load($this->fileName, 'Word2007');
    }

    /**
     * Test a document with one section and text.
     */
    public function testNoEscapingOkay(): void
    {
        Settings::setOutputEscapingEnabled(false);
        $phpWordWriter = new PhpWord();
        $testText = '6+5 &lt; 12';
        $sectionWriter = $phpWordWriter->addSection();
        $sectionWriter->addText($testText);

        $writer = new Word2007($phpWordWriter);
        $this->fileName = PHPWORD_TEST_TEMP_DIR . DIRECTORY_SEPARATOR . 'escaping3.docx';
        $writer->save($this->fileName);

        self::assertFileExists($this->fileName);

        $phpWordReader = IOFactory::load($this->fileName, 'Word2007');

        self::assertCount(1, $phpWordReader->getSections());
        self::assertCount(1, $phpWordReader->getSections()[0]->getElements());
        self::assertInstanceOf(TextRun::class, $phpWordReader->getSections()[0]->getElements()[0]);
        // we would like 6+5 < 12, but that's a BC break
        self::assertEquals($testText, $phpWordReader->getSections()[0]->getElements()[0]->getText());
    }
}
