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
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

/**
 * Test class for PhpOffice\PhpWord\Element\Header
 *
 * @runTestsInSeparateProcesses
 */
class HeaderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * New instance
     */
    public function testConstructDefault()
    {
        $iVal = rand(1, 1000);
        $oHeader = new Header($iVal);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Header', $oHeader);
        $this->assertEquals($iVal, $oHeader->getSectionId());
        $this->assertEquals(Header::AUTO, $oHeader->getType());
    }

    /**
     * Add text
     */
    public function testAddText()
    {
        $oHeader = new Header(1);
        $element = $oHeader->addText('text');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        $this->assertCount(1, $oHeader->getElements());
        $this->assertEquals('text', $element->getText());
    }

    /**
     * Add text non-UTF8
     */
    public function testAddTextNotUTF8()
    {
        $oHeader = new Header(1);
        $element = $oHeader->addText(utf8_decode('ééé'));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        $this->assertCount(1, $oHeader->getElements());
        $this->assertEquals('ééé', $element->getText());
    }

    /**
     * Add text break
     */
    public function testAddTextBreak()
    {
        $oHeader = new Header(1);
        $oHeader->addTextBreak();
        $this->assertCount(1, $oHeader->getElements());
    }

    /**
     * Add text break with params
     */
    public function testAddTextBreakWithParams()
    {
        $oHeader = new Header(1);
        $iVal = rand(1, 1000);
        $oHeader->addTextBreak($iVal);
        $this->assertCount($iVal, $oHeader->getElements());
    }

    /**
     * Add text run
     */
    public function testCreateTextRun()
    {
        $oHeader = new Header(1);
        $element = $oHeader->addTextRun();
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $element);
        $this->assertCount(1, $oHeader->getElements());
    }

    /**
     * Add table
     */
    public function testAddTable()
    {
        $oHeader = new Header(1);
        $element = $oHeader->addTable();
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Table', $element);
        $this->assertCount(1, $oHeader->getElements());
    }

    /**
     * Add image
     */
    public function testAddImage()
    {
        $src = __DIR__ . '/../_files/images/earth.jpg';
        $oHeader = new Header(1);
        $element = $oHeader->addImage($src);

        $this->assertCount(1, $oHeader->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Add image by URL
     */
    public function testAddImageByUrl()
    {
        $oHeader = new Header(1);
        $element = $oHeader->addImage('http://php.net/images/logos/php-med-trans-light.gif');

        $this->assertCount(1, $oHeader->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Add preserve text
     */
    public function testAddPreserveText()
    {
        $oHeader = new Header(1);
        $element = $oHeader->addPreserveText('text');

        $this->assertCount(1, $oHeader->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\PreserveText', $element);
    }

    /**
     * Add preserve text non-UTF8
     */
    public function testAddPreserveTextNotUTF8()
    {
        $oHeader = new Header(1);
        $element = $oHeader->addPreserveText(utf8_decode('ééé'));

        $this->assertCount(1, $oHeader->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\PreserveText', $element);
        $this->assertEquals(array('ééé'), $element->getText());
    }

    /**
     * Add watermark
     */
    public function testAddWatermark()
    {
        $src = __DIR__ . '/../_files/images/earth.jpg';
        $oHeader = new Header(1);
        $element = $oHeader->addWatermark($src);

        $this->assertCount(1, $oHeader->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Get elements
     */
    public function testGetElements()
    {
        $oHeader = new Header(1);

        $this->assertInternalType('array', $oHeader->getElements());
    }

    /**
     * Set/get relation Id
     */
    public function testRelationId()
    {
        $oHeader = new Header(1);

        $iVal = rand(1, 1000);
        $oHeader->setRelationId($iVal);
        $this->assertEquals($iVal, $oHeader->getRelationId());
    }

    /**
     * Reset type
     */
    public function testResetType()
    {
        $oHeader = new Header(1);
        $oHeader->firstPage();
        $oHeader->resetType();

        $this->assertEquals(Header::AUTO, $oHeader->getType());
    }

    /**
     * First page
     */
    public function testFirstPage()
    {
        $oHeader = new Header(1);
        $oHeader->firstPage();

        $this->assertEquals(Header::FIRST, $oHeader->getType());
    }

    /**
     * Even page
     */
    public function testEvenPage()
    {
        $oHeader = new Header(1);
        $oHeader->evenPage();

        $this->assertEquals(Header::EVEN, $oHeader->getType());
    }

    /**
     * Add footnote exception
     *
     * @expectedException \BadMethodCallException
     */
    public function testAddFootnoteException()
    {
        $header = new Header(1);
        $header->addFootnote();
    }

    /**
     * Set/get type
     */
    public function testSetGetType()
    {
        $object = new Header(1);
        $this->assertEquals(Header::AUTO, $object->getType());

        $object->setType('ODD');
        $this->assertEquals(Header::AUTO, $object->getType());
    }
}
