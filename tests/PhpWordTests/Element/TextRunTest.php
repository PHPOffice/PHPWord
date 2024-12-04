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

use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Test class for PhpOffice\PhpWord\Element\TextRun.
 *
 * @runTestsInSeparateProcesses
 */
class TextRunTest extends \PHPUnit\Framework\TestCase
{
    /**
     * New instance.
     */
    public function testConstruct(): void
    {
        $oTextRun = new TextRun();

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $oTextRun);
        self::assertCount(0, $oTextRun->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oTextRun->getParagraphStyle());
    }

    /**
     * New instance with string.
     */
    public function testConstructString(): void
    {
        $oTextRun = new TextRun('pStyle');

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $oTextRun);
        self::assertCount(0, $oTextRun->getElements());
        self::assertEquals('pStyle', $oTextRun->getParagraphStyle());
    }

    /**
     * New instance with array.
     */
    public function testConstructArray(): void
    {
        $oTextRun = new TextRun(['spacing' => 100]);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $oTextRun);
        self::assertCount(0, $oTextRun->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oTextRun->getParagraphStyle());
    }

    /**
     * New instance with object.
     */
    public function testConstructObject(): void
    {
        $oParagraphStyle = new Paragraph();
        $oParagraphStyle->setAlignment(Jc::BOTH);
        $oTextRun = new TextRun($oParagraphStyle);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $oTextRun);
        self::assertCount(0, $oTextRun->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oTextRun->getParagraphStyle());
        self::assertEquals(Jc::BOTH, $oTextRun->getParagraphStyle()->getAlignment());
    }

    /**
     * Add text.
     */
    public function testAddText(): void
    {
        $oTextRun = new TextRun();
        $element = $oTextRun->addText('text');

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        self::assertCount(1, $oTextRun->getElements());
        self::assertEquals('text', $element->getText());
    }

    /**
     * Add text non-UTF8.
     */
    public function testAddTextNotUTF8(): void
    {
        $oTextRun = new TextRun();
        $element = $oTextRun->addText(utf8decode('ééé'));

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        self::assertCount(1, $oTextRun->getElements());
        self::assertEquals('ééé', $element->getText());
    }

    /**
     * Add link.
     */
    public function testAddLink(): void
    {
        $oTextRun = new TextRun();
        $element = $oTextRun->addLink('https://github.com/PHPOffice/PHPWord');

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $element);
        self::assertCount(1, $oTextRun->getElements());
        self::assertEquals('https://github.com/PHPOffice/PHPWord', $element->getSource());
    }

    /**
     * Add link with name.
     */
    public function testAddLinkWithName(): void
    {
        $oTextRun = new TextRun();
        $element = $oTextRun->addLink('https://github.com/PHPOffice/PHPWord', 'PHPWord on GitHub');

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $element);
        self::assertCount(1, $oTextRun->getElements());
        self::assertEquals('https://github.com/PHPOffice/PHPWord', $element->getSource());
        self::assertEquals('PHPWord on GitHub', $element->getText());
    }

    /**
     * Add text break.
     */
    public function testAddTextBreak(): void
    {
        $oTextRun = new TextRun();
        $oTextRun->addTextBreak(2);

        self::assertCount(2, $oTextRun->getElements());
    }

    /**
     * Add image.
     */
    public function testAddImage(): void
    {
        $src = __DIR__ . '/../_files/images/earth.jpg';

        $oTextRun = new TextRun();
        $element = $oTextRun->addImage($src);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
        self::assertCount(1, $oTextRun->getElements());
    }

    /**
     * Add footnote.
     */
    public function testCreateFootnote(): void
    {
        $oTextRun = new TextRun();
        $oTextRun->setPhpWord(new PhpWord());
        $element = $oTextRun->addFootnote();

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Footnote', $element);
        self::assertCount(1, $oTextRun->getElements());
    }

    /**
     * Get paragraph style.
     */
    public function testParagraph(): void
    {
        $oText = new TextRun('paragraphStyle');
        self::assertEquals('paragraphStyle', $oText->getParagraphStyle());

        $oText->setParagraphStyle(['alignment' => Jc::CENTER, 'spaceAfter' => 100]);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oText->getParagraphStyle());
    }
}
