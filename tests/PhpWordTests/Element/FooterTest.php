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

use PhpOffice\PhpWord\Element\Footer;
use PhpOffice\PhpWordTests\AbstractWebServerEmbeddedTest;

/**
 * Test class for PhpOffice\PhpWord\Element\Footer.
 *
 * @runTestsInSeparateProcesses
 */
class FooterTest extends AbstractWebServerEmbeddedTest
{
    /**
     * New instance.
     */
    public function testConstruct(): void
    {
        $iVal = mt_rand(1, 1000);
        $oFooter = new Footer($iVal);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Footer', $oFooter);
        self::assertEquals($iVal, $oFooter->getSectionId());
    }

    /**
     * Add text.
     */
    public function testAddText(): void
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addText('text');

        self::assertCount(1, $oFooter->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
    }

    /**
     * Add text non-UTF8.
     */
    public function testAddTextNotUTF8(): void
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addText(utf8_decode('ééé'));

        self::assertCount(1, $oFooter->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        self::assertEquals('ééé', $element->getText());
    }

    /**
     * Add text break.
     */
    public function testAddTextBreak(): void
    {
        $oFooter = new Footer(1);
        $iVal = mt_rand(1, 1000);
        $oFooter->addTextBreak($iVal);

        self::assertCount($iVal, $oFooter->getElements());
    }

    /**
     * Add text run.
     */
    public function testCreateTextRun(): void
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addTextRun();

        self::assertCount(1, $oFooter->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $element);
    }

    /**
     * Add table.
     */
    public function testAddTable(): void
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addTable();

        self::assertCount(1, $oFooter->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Table', $element);
    }

    /**
     * Add image.
     */
    public function testAddImage(): void
    {
        $src = __DIR__ . '/../_files/images/earth.jpg';
        $oFooter = new Footer(1);
        $element = $oFooter->addImage($src);

        self::assertCount(1, $oFooter->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Add image by URL.
     */
    public function testAddImageByUrl(): void
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addImage(self::getRemoteGifImageUrl());

        self::assertCount(1, $oFooter->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Add preserve text.
     */
    public function testAddPreserveText(): void
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addPreserveText('text');

        self::assertCount(1, $oFooter->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\PreserveText', $element);
    }

    /**
     * Add preserve text non-UTF8.
     */
    public function testAddPreserveTextNotUTF8(): void
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addPreserveText(utf8_decode('ééé'));

        self::assertCount(1, $oFooter->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\PreserveText', $element);
        self::assertEquals(['ééé'], $element->getText());
    }

    /**
     * Get elements.
     */
    public function testGetElements(): void
    {
        $oFooter = new Footer(1);

        self::assertIsArray($oFooter->getElements());
    }

    /**
     * Set/get relation Id.
     */
    public function testRelationID(): void
    {
        $oFooter = new Footer(0);

        $iVal = mt_rand(1, 1000);
        $oFooter->setRelationId($iVal);

        self::assertEquals($iVal, $oFooter->getRelationId());
        self::assertEquals(Footer::AUTO, $oFooter->getType());
    }
}
