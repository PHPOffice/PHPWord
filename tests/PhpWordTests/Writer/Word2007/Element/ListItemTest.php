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

namespace PhpOffice\PhpWordTests\Writer\Word2007\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Paragraph as ParagraphStyle;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Element\ListItem.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Element\ListItem
 *
 * @runTestsInSeparateProcesses
 */
class ListItemTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed after each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * The numbering of a list item is written where the schema names it.
     *
     * `CT_PPr` is a sequence and not a set, so a reader that walks it stops at the first
     * child it did not expect. The order asserted below is the one in ECMA-376, in
     * `CT_PPrBase`.
     */
    public function testNumberingIsWrittenWhereTheSchemaNamesIt(): void
    {
        $paragraphStyle = new ParagraphStyle();
        $paragraphStyle->setSpaceAfter(120);
        $paragraphStyle->setAlignment('left');

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addListItem('A list item.', 1, null, null, $paragraphStyle);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        $pPr = $doc->getElement('/w:document/w:body/w:p/w:pPr', 'word/document.xml');
        self::assertNotNull($pPr);

        $written = [];
        foreach ($pPr->childNodes as $child) {
            if (XML_ELEMENT_NODE === $child->nodeType) {
                $written[] = $child->localName;
            }
        }

        self::assertSame(['numPr', 'spacing', 'jc'], $written);
    }

    /**
     * The numbering of a list item names the level before the number.
     *
     * `CT_NumPr` is a sequence and not a set, so a reader that walks it stops at the first
     * child it did not expect. The order asserted below is the one in ECMA-376, in
     * `CT_NumPr`.
     */
    public function testNumberingNamesTheLevelBeforeTheNumber(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addListItem('A list item.', 1);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        $numPr = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:numPr', 'word/document.xml');
        self::assertNotNull($numPr);

        $written = [];
        foreach ($numPr->childNodes as $child) {
            if (XML_ELEMENT_NODE === $child->nodeType) {
                $written[] = $child->localName;
            }
        }

        self::assertSame(['ilvl', 'numId'], $written);
    }

    /**
     * The name of the style of a list item comes before the numbering of the list.
     *
     * `CT_PPr` is a sequence and not a set, so a reader that walks it stops at the first
     * child it did not expect. The order asserted below is the one in ECMA-376, in
     * `CT_PPrBase`.
     */
    public function testTheNameOfTheStyleComesBeforeTheNumbering(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addParagraphStyle('ListStyle', ['alignment' => 'left']);
        $section = $phpWord->addSection();
        $section->addListItem('A list item.', 1, null, null, 'ListStyle');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        $pPr = $doc->getElement('/w:document/w:body/w:p/w:pPr', 'word/document.xml');
        self::assertNotNull($pPr);

        $written = [];
        foreach ($pPr->childNodes as $child) {
            if (XML_ELEMENT_NODE === $child->nodeType) {
                $written[] = $child->localName;
            }
        }

        self::assertSame(['pStyle', 'numPr'], $written);
    }
}
