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

use PhpOffice\PhpWord\Element\Cell;

/**
 * Test class for PhpOffice\PhpWord\Element\Cell
 *
 * @runTestsInSeparateProcesses
 */
class CellTest extends \PHPUnit_Framework_TestCase
{
    /**
     * New instance
     */
    public function testConstruct()
    {
        $oCell = new Cell();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Cell', $oCell);
        $this->assertEquals($oCell->getWidth(), null);
    }

    /**
     * New instance with array
     */
    public function testConstructWithStyleArray()
    {
        $oCell = new Cell(null, array('valign' => 'center'));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Cell', $oCell->getStyle());
        $this->assertEquals($oCell->getWidth(), null);
    }

    /**
     * Add text
     */
    public function testAddText()
    {
        $oCell = new Cell();
        $element = $oCell->addText('text');

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
    }

    /**
     * Add non-UTF8
     */
    public function testAddTextNotUTF8()
    {
        $oCell = new Cell();
        $element = $oCell->addText(utf8_decode('ééé'));

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        $this->assertEquals($element->getText(), 'ééé');
    }

    /**
     * Add link
     */
    public function testAddLink()
    {
        $oCell = new Cell();
        $element = $oCell->addLink(utf8_decode('ééé'), utf8_decode('ééé'));

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $element);
    }

    /**
     * Add text break
     */
    public function testAddTextBreak()
    {
        $oCell = new Cell();
        $oCell->addTextBreak();

        $this->assertCount(1, $oCell->getElements());
    }

    /**
     * Add list item
     */
    public function testAddListItem()
    {
        $oCell = new Cell();
        $element = $oCell->addListItem('text');

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\ListItem', $element);
        $this->assertEquals($element->getTextObject()->getText(), 'text');
    }

    /**
     * Add list item non-UTF8
     */
    public function testAddListItemNotUTF8()
    {
        $oCell = new Cell();
        $element = $oCell->addListItem(utf8_decode('ééé'));

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\ListItem', $element);
        $this->assertEquals($element->getTextObject()->getText(), 'ééé');
    }

    /**
     * Add image section
     */
    public function testAddImageSection()
    {
        $src = __DIR__ . "/../_files/images/earth.jpg";
        $oCell = new Cell();
        $element = $oCell->addImage($src);

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Add image header
     */
    public function testAddImageHeader()
    {
        $src = __DIR__ . "/../_files/images/earth.jpg";
        $oCell = new Cell('header', 1);
        $element = $oCell->addImage($src);

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Add image footer
     */
    public function testAddImageFooter()
    {
        $src = __DIR__ . "/../_files/images/earth.jpg";
        $oCell = new Cell('footer', 1);
        $element = $oCell->addImage($src);

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Add image section by URL
     */
    public function testAddImageSectionByUrl()
    {
        $oCell = new Cell();
        $element = $oCell->addImage(
            'http://php.net/images/logos/php-med-trans-light.gif'
        );

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Add object
     */
    public function testAddObjectXLS()
    {
        $src = __DIR__ . "/../_files/documents/sheet.xls";
        $oCell = new Cell();
        $element = $oCell->addObject($src);

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Object', $element);
    }

    /**
     * Test add object exception
     *
     * @expectedException \PhpOffice\PhpWord\Exception\InvalidObjectException
     */
    public function testAddObjectException()
    {
        $src = __DIR__ . "/../_files/xsl/passthrough.xsl";
        $oCell = new Cell();
        $oCell->addObject($src);
    }

    /**
     * Add preserve text
     */
    public function testAddPreserveText()
    {
        $oCell = new Cell();
        $oCell->setDocPart('Header', 1);
        $element = $oCell->addPreserveText('text');

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\PreserveText', $element);
    }

    /**
     * Add preserve text non-UTF8
     */
    public function testAddPreserveTextNotUTF8()
    {
        $oCell = new Cell();
        $oCell->setDocPart('Header', 1);
        $element = $oCell->addPreserveText(utf8_decode('ééé'));

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\PreserveText', $element);
        $this->assertEquals($element->getText(), array('ééé'));
    }

    /**
     * Add preserve text exception
     *
     * @expectedException \BadMethodCallException
     */
    public function testAddPreserveTextException()
    {
        $oCell = new Cell();
        $oCell->setDocPart('Section', 1);
        $oCell->addPreserveText('text');
    }

    /**
     * Add text run
     */
    public function testCreateTextRun()
    {
        $oCell = new Cell();
        $element = $oCell->addTextRun();

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $element);
    }

    /**
     * Add check box
     */
    public function testAddCheckBox()
    {
        $oCell = new Cell();
        $element = $oCell->addCheckBox(utf8_decode('ééé'), utf8_decode('ééé'));

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\CheckBox', $element);
    }

    /**
     * Get elements
     */
    public function testGetElements()
    {
        $oCell = new Cell();

        $this->assertInternalType('array', $oCell->getElements());
    }
}
