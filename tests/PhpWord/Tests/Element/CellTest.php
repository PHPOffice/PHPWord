<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
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
        $iVal = rand(1, 1000);
        $oCell = new Cell('section', $iVal);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Cell', $oCell);
        $this->assertEquals($oCell->getWidth(), null);
    }

    /**
     * New instance with array
     */
    public function testConstructWithStyleArray()
    {
        $iVal = rand(1, 1000);
        $oCell = new Cell('section', $iVal, null, array('valign' => 'center'));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Cell', $oCell->getStyle());
        $this->assertEquals($oCell->getWidth(), null);
    }

    /**
     * Add text
     */
    public function testAddText()
    {
        $oCell = new Cell('section', 1);
        $element = $oCell->addText('text');

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
    }

    /**
     * Add non-UTF8
     */
    public function testAddTextNotUTF8()
    {
        $oCell = new Cell('section', 1);
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
        $oCell = new Cell('section', 1);
        $element = $oCell->addLink(utf8_decode('ééé'), utf8_decode('ééé'));

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $element);
    }

    /**
     * Add text break
     */
    public function testAddTextBreak()
    {
        $oCell = new Cell('section', 1);
        $oCell->addTextBreak();

        $this->assertCount(1, $oCell->getElements());
    }

    /**
     * Add list item
     */
    public function testAddListItem()
    {
        $oCell = new Cell('section', 1);
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
        $oCell = new Cell('section', 1);
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
        $oCell = new Cell('section', 1);
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
        $oCell = new Cell('section', 1);
        $element = $oCell->addImage(
            'https://assets.mozillalabs.com/Brands-Logos/Thunderbird/logo-only/thunderbird_logo-only_RGB.png'
        );

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Add image header by URL
     */
    public function testAddImageHeaderByUrl()
    {
        $oCell = new Cell('header', 1);
        $element = $oCell->addImage(
            'https://assets.mozillalabs.com/Brands-Logos/Thunderbird/logo-only/thunderbird_logo-only_RGB.png'
        );

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $element);
    }

    /**
     * Add image footer by URL
     */
    public function testAddImageFooterByUrl()
    {
        $oCell = new Cell('footer', 1);
        $element = $oCell->addImage(
            'https://assets.mozillalabs.com/Brands-Logos/Thunderbird/logo-only/thunderbird_logo-only_RGB.png'
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
        $oCell = new Cell('section', 1);
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
        $oCell = new Cell('section', 1);
        $element = $oCell->addObject($src);
    }

    /**
     * Add preserve text
     */
    public function testAddPreserveText()
    {
        $oCell = new Cell('header', 1);
        $element = $oCell->addPreserveText('text');

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\PreserveText', $element);
    }

    /**
     * Add preserve text non-UTF8
     */
    public function testAddPreserveTextNotUTF8()
    {
        $oCell = new Cell('header', 1);
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
        $oCell = new Cell('section', 1);
        $element = $oCell->addPreserveText('text');
    }

    /**
     * Add text run
     */
    public function testCreateTextRun()
    {
        $oCell = new Cell('section', 1);
        $element = $oCell->addTextRun();

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $element);
    }

    /**
     * Add check box
     */
    public function testAddCheckBox()
    {
        $oCell = new Cell('section', 1);
        $element = $oCell->addCheckBox(utf8_decode('ééé'), utf8_decode('ééé'));

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\CheckBox', $element);
    }

    /**
     * Get elements
     */
    public function testGetElements()
    {
        $oCell = new Cell('section', 1);

        $this->assertInternalType('array', $oCell->getElements());
    }
}
