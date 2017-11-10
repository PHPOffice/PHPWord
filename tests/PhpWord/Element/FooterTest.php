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
 * Test class for PhpOffice\PhpWord\Element\Footer
 *
 * @runTestsInSeparateProcesses
 */
class FooterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * New instance
     */
    public function testConstruct()
    {
        $iVal = rand(1, 1000);
        $oFooter = new Footer($iVal);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Footer', $oFooter);
        $this->assertEquals($iVal, $oFooter->getSectionId());
    }

    /**
     * Add text
     */
    public function testAddText()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addText('text');

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
    }

    /**
     * Add text non-UTF8
     */
    public function testAddTextNotUTF8()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addText(utf8_decode('ééé'));

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        $this->assertEquals('ééé', $element->getText());
    }

    /**
     * Add text break
     */
    public function testAddTextBreak()
    {
        $oFooter = new Footer(1);
        $iVal = rand(1, 1000);
        $oFooter->addTextBreak($iVal);

        $this->assertCount($iVal, $oFooter->getElements());
    }

    /**
     * Add text run
     */
    public function testCreateTextRun()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addTextRun();

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $element);
    }

    /**
     * Add table
     */
    public function testAddTable()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addTable();

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Table', $element);
    }

    /**
     * Add image
     */
    public function testAddImage()
    {
        $src = __DIR__ . '/../_files/images/earth.jpg';
        $oFooter = new Footer(1);
        $element = $oFooter->addImage($src);

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Add image by URL
     */
    public function testAddImageByUrl()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addImage('http://php.net/images/logos/php-med-trans-light.gif');

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Add preserve text
     */
    public function testAddPreserveText()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addPreserveText('text');

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\PreserveText', $element);
    }

    /**
     * Add preserve text non-UTF8
     */
    public function testAddPreserveTextNotUTF8()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addPreserveText(utf8_decode('ééé'));

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\PreserveText', $element);
        $this->assertEquals(array('ééé'), $element->getText());
    }

    /**
     * Get elements
     */
    public function testGetElements()
    {
        $oFooter = new Footer(1);

        $this->assertInternalType('array', $oFooter->getElements());
    }

    /**
     * Set/get relation Id
     */
    public function testRelationID()
    {
        $oFooter = new Footer(0);

        $iVal = rand(1, 1000);
        $oFooter->setRelationId($iVal);

        $this->assertEquals($iVal, $oFooter->getRelationId());
        $this->assertEquals(Footer::AUTO, $oFooter->getType());
    }
}
