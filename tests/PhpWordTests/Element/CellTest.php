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
use PhpOffice\PhpWord\Element\Cell;
use PhpOffice\PhpWordTests\AbstractWebServerEmbeddedTest;

/**
 * Test class for PhpOffice\PhpWord\Element\Cell.
 *
 * @runTestsInSeparateProcesses
 */
class CellTest extends AbstractWebServerEmbeddedTest
{
    /**
     * New instance.
     */
    public function testConstruct(): void
    {
        $oCell = new Cell();

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Cell', $oCell);
        self::assertNull($oCell->getWidth());
    }

    /**
     * New instance with array.
     */
    public function testConstructWithStyleArray(): void
    {
        $oCell = new Cell(null, ['valign' => 'center']);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Cell', $oCell->getStyle());
        self::assertNull($oCell->getWidth());
    }

    /**
     * Add text.
     */
    public function testAddText(): void
    {
        $oCell = new Cell();
        $element = $oCell->addText('text');

        self::assertCount(1, $oCell->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
    }

    /**
     * Add non-UTF8.
     */
    public function testAddTextNotUTF8(): void
    {
        $oCell = new Cell();
        $element = $oCell->addText(utf8_decode('ééé'));

        self::assertCount(1, $oCell->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        self::assertEquals('ééé', $element->getText());
    }

    /**
     * Add link.
     */
    public function testAddLink(): void
    {
        $oCell = new Cell();
        $element = $oCell->addLink(utf8_decode('ééé'), utf8_decode('ééé'));

        self::assertCount(1, $oCell->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $element);
    }

    /**
     * Add text break.
     */
    public function testAddTextBreak(): void
    {
        $oCell = new Cell();
        $oCell->addTextBreak();

        self::assertCount(1, $oCell->getElements());
    }

    /**
     * Add list item.
     */
    public function testAddListItem(): void
    {
        $oCell = new Cell();
        $element = $oCell->addListItem('text');

        self::assertCount(1, $oCell->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\ListItem', $element);
        self::assertEquals('text', $element->getTextObject()->getText());
    }

    /**
     * Add list item non-UTF8.
     */
    public function testAddListItemNotUTF8(): void
    {
        $oCell = new Cell();
        $element = $oCell->addListItem(utf8_decode('ééé'));

        self::assertCount(1, $oCell->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\ListItem', $element);
        self::assertEquals('ééé', $element->getTextObject()->getText());
    }

    /**
     * Add image section.
     */
    public function testAddImageSection(): void
    {
        $src = __DIR__ . '/../_files/images/earth.jpg';
        $oCell = new Cell();
        $element = $oCell->addImage($src);

        self::assertCount(1, $oCell->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Add image header.
     */
    public function testAddImageHeader(): void
    {
        $src = __DIR__ . '/../_files/images/earth.jpg';
        $oCell = new Cell('header', 1);
        $element = $oCell->addImage($src);

        self::assertCount(1, $oCell->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Add image footer.
     */
    public function testAddImageFooter(): void
    {
        $src = __DIR__ . '/../_files/images/earth.jpg';
        $oCell = new Cell('footer', 1);
        $element = $oCell->addImage($src);

        self::assertCount(1, $oCell->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Add image section by URL.
     */
    public function testAddImageSectionByUrl(): void
    {
        $oCell = new Cell();
        $element = $oCell->addImage(self::getRemoteGifImageUrl());

        self::assertCount(1, $oCell->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Add object.
     */
    public function testAddObjectXLS(): void
    {
        $src = __DIR__ . '/../_files/documents/sheet.xls';
        $oCell = new Cell();
        $element = $oCell->addObject($src);

        self::assertCount(1, $oCell->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\OLEObject', $element);
    }

    /**
     * Test add object exception.
     */
    public function testAddObjectException(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\InvalidObjectException::class);
        $src = __DIR__ . '/../_files/xsl/passthrough.xsl';
        $oCell = new Cell();
        $oCell->addObject($src);
    }

    /**
     * Add preserve text.
     */
    public function testAddPreserveText(): void
    {
        $oCell = new Cell();
        $oCell->setDocPart('Header', 1);
        $element = $oCell->addPreserveText('text');

        self::assertCount(1, $oCell->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\PreserveText', $element);
    }

    /**
     * Add preserve text non-UTF8.
     */
    public function testAddPreserveTextNotUTF8(): void
    {
        $oCell = new Cell();
        $oCell->setDocPart('Header', 1);
        $element = $oCell->addPreserveText(utf8_decode('ééé'));

        self::assertCount(1, $oCell->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\PreserveText', $element);
        self::assertEquals(['ééé'], $element->getText());
    }

    /**
     * Add preserve text exception.
     */
    public function testAddPreserveTextException(): void
    {
        $this->expectException(BadMethodCallException::class);
        $oCell = new Cell();
        $oCell->setDocPart('TextRun', 1);
        $oCell->addPreserveText('text');
    }

    /**
     * Add text run.
     */
    public function testCreateTextRun(): void
    {
        $oCell = new Cell();
        $element = $oCell->addTextRun();

        self::assertCount(1, $oCell->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $element);
    }

    /**
     * Add check box.
     */
    public function testAddCheckBox(): void
    {
        $oCell = new Cell();
        $element = $oCell->addCheckBox(utf8_decode('ééé'), utf8_decode('ééé'));

        self::assertCount(1, $oCell->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\CheckBox', $element);
    }

    /**
     * Get elements.
     */
    public function testGetElements(): void
    {
        $oCell = new Cell();

        self::assertIsArray($oCell->getElements());
    }
}
