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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Tests\Element;

use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\PhpWord;

/**
 * Test class for PhpOffice\PhpWord\Element\TextRun
 *
 * @runTestsInSeparateProcesses
 */
class TextRunTest extends \PHPUnit_Framework_TestCase
{
    /**
     * New instance
     */
    public function testConstructNull()
    {
        $oTextRun = new TextRun();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $oTextRun);
        $this->assertCount(0, $oTextRun->getElements());
        $this->assertEquals($oTextRun->getParagraphStyle(), null);
    }

    /**
     * New instance with string
     */
    public function testConstructString()
    {
        $oTextRun = new TextRun('pStyle');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $oTextRun);
        $this->assertCount(0, $oTextRun->getElements());
        $this->assertEquals($oTextRun->getParagraphStyle(), 'pStyle');
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
     * Add text
     */
    public function testAddText()
    {
        $oTextRun = new TextRun();
        $element = $oTextRun->addText('text');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        $this->assertCount(1, $oTextRun->getElements());
        $this->assertEquals($element->getText(), 'text');
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
        $this->assertEquals($element->getText(), 'ééé');
    }

    /**
     * Add link
     */
    public function testAddLink()
    {
        $oTextRun = new TextRun();
        $element = $oTextRun->addLink('http://www.google.fr');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $element);
        $this->assertCount(1, $oTextRun->getElements());
        $this->assertEquals($element->getSource(), 'http://www.google.fr');
    }

    /**
     * Add link with name
     */
    public function testAddLinkWithName()
    {
        $oTextRun = new TextRun();
        $element = $oTextRun->addLink('http://www.google.fr', utf8_decode('ééé'));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $element);
        $this->assertCount(1, $oTextRun->getElements());
        $this->assertEquals($element->getSource(), 'http://www.google.fr');
        $this->assertEquals($element->getText(), 'ééé');
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
        $src = __DIR__ . "/../_files/images/earth.jpg";

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
}
