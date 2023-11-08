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

use PhpOffice\PhpWord\Element\ListItemRun;

/**
 * Test class for PhpOffice\PhpWord\Element\ListItemRun.
 *
 * @runTestsInSeparateProcesses
 */
class ListItemRunTest extends \PHPUnit\Framework\TestCase
{
    /**
     * New instance.
     */
    public function testConstruct(): void
    {
        $oListItemRun = new ListItemRun();

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\ListItemRun', $oListItemRun);
        self::assertCount(0, $oListItemRun->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oListItemRun->getParagraphStyle());
    }

    /**
     * New instance with string.
     */
    public function testConstructString(): void
    {
        $oListItemRun = new ListItemRun(0, null, 'pStyle');

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\ListItemRun', $oListItemRun);
        self::assertCount(0, $oListItemRun->getElements());
        self::assertEquals('pStyle', $oListItemRun->getParagraphStyle());
    }

    /**
     * New instance with string.
     */
    public function testConstructListString(): void
    {
        $oListItemRun = new ListItemRun(0, 'numberingStyle');

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\ListItemRun', $oListItemRun);
        self::assertCount(0, $oListItemRun->getElements());
    }

    /**
     * New instance with array.
     */
    public function testConstructArray(): void
    {
        $oListItemRun = new ListItemRun(0, null, ['spacing' => 100]);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\ListItemRun', $oListItemRun);
        self::assertCount(0, $oListItemRun->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oListItemRun->getParagraphStyle());
    }

    /**
     * Get style.
     */
    public function testStyle(): void
    {
        $oListItemRun = new ListItemRun(1, ['listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER]);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\ListItem', $oListItemRun->getStyle());
        self::assertEquals(\PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER, $oListItemRun->getStyle()->getListType());
    }

    /**
     * getDepth.
     */
    public function testDepth(): void
    {
        $iVal = mt_rand(1, 1000);
        $oListItemRun = new ListItemRun($iVal);

        self::assertEquals($iVal, $oListItemRun->getDepth());
    }

    /**
     * Add text.
     */
    public function testAddText(): void
    {
        $oListItemRun = new ListItemRun();
        $element = $oListItemRun->addText('text');

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        self::assertCount(1, $oListItemRun->getElements());
        self::assertEquals('text', $element->getText());
    }

    /**
     * Add text non-UTF8.
     */
    public function testAddTextNotUTF8(): void
    {
        $oListItemRun = new ListItemRun();
        $element = $oListItemRun->addText(utf8decode('ééé'));

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        self::assertCount(1, $oListItemRun->getElements());
        self::assertEquals('ééé', $element->getText());
    }

    /**
     * Add link.
     */
    public function testAddLink(): void
    {
        $oListItemRun = new ListItemRun();
        $element = $oListItemRun->addLink('https://github.com/PHPOffice/PHPWord');

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $element);
        self::assertCount(1, $oListItemRun->getElements());
        self::assertEquals('https://github.com/PHPOffice/PHPWord', $element->getSource());
    }

    /**
     * Add link with name.
     */
    public function testAddLinkWithName(): void
    {
        $oListItemRun = new ListItemRun();
        $element = $oListItemRun->addLink('https://github.com/PHPOffice/PHPWord', 'PHPWord on GitHub');

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $element);
        self::assertCount(1, $oListItemRun->getElements());
        self::assertEquals('https://github.com/PHPOffice/PHPWord', $element->getSource());
        self::assertEquals('PHPWord on GitHub', $element->getText());
    }

    /**
     * Add text break.
     */
    public function testAddTextBreak(): void
    {
        $oListItemRun = new ListItemRun();
        $oListItemRun->addTextBreak(2);

        self::assertCount(2, $oListItemRun->getElements());
    }

    /**
     * Add image.
     */
    public function testAddImage(): void
    {
        $src = __DIR__ . '/../_files/images/earth.jpg';

        $oListItemRun = new ListItemRun();
        $element = $oListItemRun->addImage($src);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
        self::assertCount(1, $oListItemRun->getElements());
    }
}
