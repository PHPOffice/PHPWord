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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Test class for PhpOffice\PhpWord\Element\TextRun
 *
 * @runTestsInSeparateProcesses
 */
class TextRunTest extends \PHPUnit\Framework\TestCase
{
    /**
     * New instance
     */
    public function testConstruct()
    {
        $oTextRun = new TextRun();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $oTextRun);
        $this->assertCount(0, $oTextRun->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oTextRun->getParagraphStyle());
    }

    /**
     * New instance with string
     */
    public function testConstructString()
    {
        $oTextRun = new TextRun('pStyle');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $oTextRun);
        $this->assertCount(0, $oTextRun->getElements());
        $this->assertEquals('pStyle', $oTextRun->getParagraphStyle());
    }

    /**
     * New instance with array
     */
    public function testConstructArray()
    {
        $oTextRun = new TextRun(array('spacing' => 100));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $oTextRun);
        $this->assertCount(0, $oTextRun->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oTextRun->getParagraphStyle());
    }

    /**
     * New instance with object
     */
    public function testConstructObject()
    {
        $oParagraphStyle = new Paragraph();
        $oParagraphStyle->setAlignment(Jc::BOTH);
        $oTextRun = new TextRun($oParagraphStyle);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $oTextRun);
        $this->assertCount(0, $oTextRun->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oTextRun->getParagraphStyle());
        $this->assertEquals(Jc::BOTH, $oTextRun->getParagraphStyle()->getAlignment());
    }

    /**
     * Add text
     */
    public function testAddText()
    {
        $oTextRun = new TextRun();
        $element = $oTextRun->addText('text');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        $this->assertCount(1, $oTextRun->getElements());
        $this->assertEquals('text', $element->getText());
    }

    /**
     * Add text non-UTF8
     */
    public function testAddTextNotUTF8()
    {
        $oTextRun = new TextRun();
        $element = $oTextRun->addText(utf8_decode('ééé'));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        $this->assertCount(1, $oTextRun->getElements());
        $this->assertEquals('ééé', $element->getText());
    }

    /**
     * Add link
     */
    public function testAddLink()
    {
        $oTextRun = new TextRun();
        $element = $oTextRun->addLink('https://github.com/PHPOffice/PHPWord');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $element);
        $this->assertCount(1, $oTextRun->getElements());
        $this->assertEquals('https://github.com/PHPOffice/PHPWord', $element->getSource());
    }

    /**
     * Add link with name
     */
    public function testAddLinkWithName()
    {
        $oTextRun = new TextRun();
        $element = $oTextRun->addLink('https://github.com/PHPOffice/PHPWord', 'PHPWord on GitHub');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $element);
        $this->assertCount(1, $oTextRun->getElements());
        $this->assertEquals('https://github.com/PHPOffice/PHPWord', $element->getSource());
        $this->assertEquals('PHPWord on GitHub', $element->getText());
    }

    /**
     * Add text break
     */
    public function testAddTextBreak()
    {
        $oTextRun = new TextRun();
        $oTextRun->addTextBreak(2);

        $this->assertCount(2, $oTextRun->getElements());
    }

    /**
     * Add image
     */
    public function testAddImage()
    {
        $src = __DIR__ . '/../_files/images/earth.jpg';

        $oTextRun = new TextRun();
        $element = $oTextRun->addImage($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
        $this->assertCount(1, $oTextRun->getElements());
    }

    /**
     * Add footnote
     */
    public function testCreateFootnote()
    {
        $oTextRun = new TextRun();
        $oTextRun->setPhpWord(new PhpWord());
        $element = $oTextRun->addFootnote();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Footnote', $element);
        $this->assertCount(1, $oTextRun->getElements());
    }

    /**
     * Get paragraph style
     */
    public function testParagraph()
    {
        $oText = new TextRun('paragraphStyle');
        $this->assertEquals('paragraphStyle', $oText->getParagraphStyle());

        $oText->setParagraphStyle(array('alignment' => Jc::CENTER, 'spaceAfter' => 100));
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oText->getParagraphStyle());
    }
}
