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

namespace PhpOffice\PhpWordTests\Element;

use BadMethodCallException;
use PhpOffice\PhpWord\Element\Header;
use PhpOffice\PhpWordTests\AbstractWebServerEmbeddedTest;

/**
 * Test class for PhpOffice\PhpWord\Element\Header.
 *
 * @runTestsInSeparateProcesses
 */
class HeaderTest extends AbstractWebServerEmbeddedTest
{
    /**
     * New instance.
     */
    public function testConstructDefault(): void
    {
        $iVal = mt_rand(1, 1000);
        $oHeader = new Header($iVal);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Header', $oHeader);
        self::assertEquals($iVal, $oHeader->getSectionId());
        self::assertEquals(Header::AUTO, $oHeader->getType());
    }

    /**
     * Add text.
     */
    public function testAddText(): void
    {
        $oHeader = new Header(1);
        $element = $oHeader->addText('text');

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        self::assertCount(1, $oHeader->getElements());
        self::assertEquals('text', $element->getText());
    }

    /**
     * Add text non-UTF8.
     */
    public function testAddTextNotUTF8(): void
    {
        $oHeader = new Header(1);
        $element = $oHeader->addText(utf8_decode('ééé'));

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        self::assertCount(1, $oHeader->getElements());
        self::assertEquals('ééé', $element->getText());
    }

    /**
     * Add text break.
     */
    public function testAddTextBreak(): void
    {
        $oHeader = new Header(1);
        $oHeader->addTextBreak();
        self::assertCount(1, $oHeader->getElements());
    }

    /**
     * Add text break with params.
     */
    public function testAddTextBreakWithParams(): void
    {
        $oHeader = new Header(1);
        $iVal = mt_rand(1, 1000);
        $oHeader->addTextBreak($iVal);
        self::assertCount($iVal, $oHeader->getElements());
    }

    /**
     * Add text run.
     */
    public function testCreateTextRun(): void
    {
        $oHeader = new Header(1);
        $element = $oHeader->addTextRun();
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $element);
        self::assertCount(1, $oHeader->getElements());
    }

    /**
     * Add table.
     */
    public function testAddTable(): void
    {
        $oHeader = new Header(1);
        $element = $oHeader->addTable();
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Table', $element);
        self::assertCount(1, $oHeader->getElements());
    }

    /**
     * Add image.
     */
    public function testAddImage(): void
    {
        $src = __DIR__ . '/../_files/images/earth.jpg';
        $oHeader = new Header(1);
        $element = $oHeader->addImage($src);

        self::assertCount(1, $oHeader->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Add image by URL.
     */
    public function testAddImageByUrl(): void
    {
        $oHeader = new Header(1);
        $element = $oHeader->addImage(self::getRemoteGifImageUrl());

        self::assertCount(1, $oHeader->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Add preserve text.
     */
    public function testAddPreserveText(): void
    {
        $oHeader = new Header(1);
        $element = $oHeader->addPreserveText('text');

        self::assertCount(1, $oHeader->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\PreserveText', $element);
    }

    /**
     * Add preserve text non-UTF8.
     */
    public function testAddPreserveTextNotUTF8(): void
    {
        $oHeader = new Header(1);
        $element = $oHeader->addPreserveText(utf8_decode('ééé'));

        self::assertCount(1, $oHeader->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\PreserveText', $element);
        self::assertEquals(['ééé'], $element->getText());
    }

    /**
     * Add watermark.
     */
    public function testAddWatermark(): void
    {
        $src = __DIR__ . '/../_files/images/earth.jpg';
        $oHeader = new Header(1);
        $element = $oHeader->addWatermark($src);

        self::assertCount(1, $oHeader->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Get elements.
     */
    public function testGetElements(): void
    {
        $oHeader = new Header(1);

        self::assertIsArray($oHeader->getElements());
    }

    /**
     * Set/get relation Id.
     */
    public function testRelationId(): void
    {
        $oHeader = new Header(1);

        $iVal = mt_rand(1, 1000);
        $oHeader->setRelationId($iVal);
        self::assertEquals($iVal, $oHeader->getRelationId());
    }

    /**
     * Reset type.
     */
    public function testResetType(): void
    {
        $oHeader = new Header(1);
        $oHeader->firstPage();
        $oHeader->resetType();

        self::assertEquals(Header::AUTO, $oHeader->getType());
    }

    /**
     * First page.
     */
    public function testFirstPage(): void
    {
        $oHeader = new Header(1);
        $oHeader->firstPage();

        self::assertEquals(Header::FIRST, $oHeader->getType());
    }

    /**
     * Even page.
     */
    public function testEvenPage(): void
    {
        $oHeader = new Header(1);
        $oHeader->evenPage();

        self::assertEquals(Header::EVEN, $oHeader->getType());
    }

    /**
     * Add footnote exception.
     */
    public function testAddFootnoteException(): void
    {
        $this->expectException(BadMethodCallException::class);
        $header = new Header(1);
        $header->addFootnote();
    }

    /**
     * Set/get type.
     */
    public function testSetGetType(): void
    {
        $object = new Header(1);
        self::assertEquals(Header::AUTO, $object->getType());

        $object->setType('ODD');
        self::assertEquals(Header::AUTO, $object->getType());
    }
}
