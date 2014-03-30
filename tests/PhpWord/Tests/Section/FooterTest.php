<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Section;

use PhpOffice\PhpWord\Section\Footer;

/**
 * Test class for PhpOffice\PhpWord\Section\Footer
 *
 * @runTestsInSeparateProcesses
 */
class FooterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * New instance
     */
    public function testConstruct()
    {
        $iVal = rand(1, 1000);
        $oFooter = new Footer($iVal);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Footer', $oFooter);
        $this->assertEquals($oFooter->getFooterCount(), $iVal);
    }

    /**
     * Add text
     */
    public function testAddText()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addText('text');

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Text', $element);
    }

    /**
     * Add text non-UTF8
     */
    public function testAddTextNotUTF8()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addText(utf8_decode('ééé'));

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Text', $element);
        $this->assertEquals($element->getText(), 'ééé');
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
        $element = $oFooter->createTextRun();

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\TextRun', $element);
    }

    /**
     * Add table
     */
    public function testAddTable()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addTable();

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Table', $element);
    }

    /**
     * Add image
     */
    public function testAddImage()
    {
        $src = __DIR__ . "/../_files/images/earth.jpg";
        $oFooter = new Footer(1);
        $element1 = $oFooter->addImage($src);
        $element2 = $oFooter->addMemoryImage($src); // @deprecated

        $this->assertCount(2, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Image', $element1);
    }

    /**
     * Add image by URL
     */
    public function testAddImageByUrl()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addImage(
            'https://assets.mozillalabs.com/Brands-Logos/Thunderbird/logo-only/thunderbird_logo-only_RGB.png'
        );

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Image', $element);
    }

    /**
     * Add preserve text
     */
    public function testAddPreserveText()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addPreserveText('text');

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Footer\\PreserveText', $element);
    }

    /**
     * Add preserve text non-UTF8
     */
    public function testAddPreserveTextNotUTF8()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addPreserveText(utf8_decode('ééé'));

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Footer\\PreserveText', $element);
        $this->assertEquals($element->getText(), array('ééé'));
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
        $this->assertEquals($oFooter->getRelationId(), $iVal);
    }
}
