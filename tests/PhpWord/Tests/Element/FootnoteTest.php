<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Element;

use PhpOffice\PhpWord\Element\Footnote;

/**
 * Test class for PhpOffice\PhpWord\Element\Footnote
 *
 * @runTestsInSeparateProcesses
 */
class FootnoteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * New instance without parameter
     */
    public function testConstruct()
    {
        $oFootnote = new Footnote();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Footnote', $oFootnote);
        $this->assertCount(0, $oFootnote->getElements());
        $this->assertEquals($oFootnote->getParagraphStyle(), null);
    }

    /**
     * New instance with string parameter
     */
    public function testConstructString()
    {
        $oFootnote = new Footnote('pStyle');

        $this->assertEquals($oFootnote->getParagraphStyle(), 'pStyle');
    }

    /**
     * New instance with array parameter
     */
    public function testConstructArray()
    {
        $oFootnote = new Footnote(array('spacing' => 100));

        $this->assertInstanceOf(
            'PhpOffice\\PhpWord\\Style\\Paragraph',
            $oFootnote->getParagraphStyle()
        );
    }

    /**
     * Add text element
     */
    public function testAddText()
    {
        $oFootnote = new Footnote();
        $element = $oFootnote->addText('text');

        $this->assertCount(1, $oFootnote->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
    }

    /**
     * Add text break element
     */
    public function testAddTextBreak()
    {
        $oFootnote = new Footnote();
        $oFootnote->addTextBreak(2);

        $this->assertCount(2, $oFootnote->getElements());
    }

    /**
     * Add link element
     */
    public function testAddLink()
    {
        $oFootnote = new Footnote();
        $element = $oFootnote->addLink('http://www.google.fr');

        $this->assertCount(1, $oFootnote->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $element);
    }

    /**
     * Set/get reference Id
     */
    public function testReferenceId()
    {
        $oFootnote = new Footnote();

        $iVal = rand(1, 1000);
        $oFootnote->setRelationId($iVal);
        $this->assertEquals($oFootnote->getRelationId(), $iVal);
    }

    /**
     * Get elements
     */
    public function testGetElements()
    {
        $oFootnote = new Footnote();
        $this->assertInternalType('array', $oFootnote->getElements());
    }
}
