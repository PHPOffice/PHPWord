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
 * Test class for PhpOffice\PhpWord\Element\Footnote
 *
 * @runTestsInSeparateProcesses
 */
class FootnoteTest extends \PHPUnit\Framework\TestCase
{
    /**
     * New instance without parameter
     */
    public function testConstruct()
    {
        $oFootnote = new Footnote();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Footnote', $oFootnote);
        $this->assertCount(0, $oFootnote->getElements());
        $this->assertNull($oFootnote->getParagraphStyle());
    }

    /**
     * New instance with string parameter
     */
    public function testConstructString()
    {
        $oFootnote = new Footnote('pStyle');

        $this->assertEquals('pStyle', $oFootnote->getParagraphStyle());
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
        $element = $oFootnote->addLink('https://github.com/PHPOffice/PHPWord');

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
        $this->assertEquals($iVal, $oFootnote->getRelationId());
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
