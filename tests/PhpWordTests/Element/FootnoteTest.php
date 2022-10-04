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

use PhpOffice\PhpWord\Element\Footnote;

/**
 * Test class for PhpOffice\PhpWord\Element\Footnote.
 *
 * @runTestsInSeparateProcesses
 */
class FootnoteTest extends \PHPUnit\Framework\TestCase
{
    /**
     * New instance without parameter.
     */
    public function testConstruct(): void
    {
        $oFootnote = new Footnote();

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Footnote', $oFootnote);
        self::assertCount(0, $oFootnote->getElements());
        self::assertNull($oFootnote->getParagraphStyle());
    }

    /**
     * New instance with string parameter.
     */
    public function testConstructString(): void
    {
        $oFootnote = new Footnote('pStyle');

        self::assertEquals('pStyle', $oFootnote->getParagraphStyle());
    }

    /**
     * New instance with array parameter.
     */
    public function testConstructArray(): void
    {
        $oFootnote = new Footnote(['spacing' => 100]);

        self::assertInstanceOf(
            'PhpOffice\\PhpWord\\Style\\Paragraph',
            $oFootnote->getParagraphStyle()
        );
    }

    /**
     * Add text element.
     */
    public function testAddText(): void
    {
        $oFootnote = new Footnote();
        $element = $oFootnote->addText('text');

        self::assertCount(1, $oFootnote->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
    }

    /**
     * Add text break element.
     */
    public function testAddTextBreak(): void
    {
        $oFootnote = new Footnote();
        $oFootnote->addTextBreak(2);

        self::assertCount(2, $oFootnote->getElements());
    }

    /**
     * Add link element.
     */
    public function testAddLink(): void
    {
        $oFootnote = new Footnote();
        $element = $oFootnote->addLink('https://github.com/PHPOffice/PHPWord');

        self::assertCount(1, $oFootnote->getElements());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Link', $element);
    }

    /**
     * Set/get reference Id.
     */
    public function testReferenceId(): void
    {
        $oFootnote = new Footnote();

        $iVal = mt_rand(1, 1000);
        $oFootnote->setRelationId($iVal);
        self::assertEquals($iVal, $oFootnote->getRelationId());
    }

    /**
     * Get elements.
     */
    public function testGetElements(): void
    {
        $oFootnote = new Footnote();
        self::assertIsArray($oFootnote->getElements());
    }
}
