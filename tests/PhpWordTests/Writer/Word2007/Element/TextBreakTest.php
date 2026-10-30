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
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Element\TextBreak.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Element\TextBreak
 */
class TextBreakTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed after each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * A w:br is a run-level element; a w:p takes runs, hyperlinks and the like, but no break.
     */
    public function testTextBreakIsWrittenInARun(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $textRun = $section->addTextRun();
        $textRun->addText('before');
        $textRun->addTextBreak(1);
        $textRun->addText('after');
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $paragraph = '/w:document/w:body/w:p';
        self::assertTrue($doc->elementExists($paragraph . '/w:r/w:br'));
        self::assertFalse($doc->elementExists($paragraph . '/w:br'));
        self::assertSame('before', $doc->getElement($paragraph . '/w:r[1]/w:t')->textContent);
        self::assertSame('after', $doc->getElement($paragraph . '/w:r[3]/w:t')->textContent);
    }

    /**
     * The properties of a paragraph with a break in it are written in one `w:pPr`.
     *
     * `CT_P` takes one `w:pPr`, and the run properties of the paragraph mark belong
     * in it, after the properties of the paragraph: `CT_PPr` names `w:rPr` after the
     * children of `CT_PPrBase`.
     */
    public function testTheParagraphOfABreakHasOneSetOfProperties(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addFontStyle('fontStyle', ['size' => 9]);
        $phpWord->addParagraphStyle('paragraphStyle', ['spacing' => 480]);

        $section = $phpWord->addSection();
        $section->addTextBreak(1, 'fontStyle', 'paragraphStyle');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $paragraph = $doc->getElement('/w:document/w:body/w:p');
        self::assertNotNull($paragraph);

        $written = [];
        foreach ($paragraph->childNodes as $child) {
            if (XML_ELEMENT_NODE === $child->nodeType) {
                $written[] = $child->localName;
            }
        }

        self::assertSame(['pPr'], $written);

        $properties = $doc->getElement('/w:document/w:body/w:p/w:pPr');
        self::assertNotNull($properties);

        $children = [];
        foreach ($properties->childNodes as $child) {
            if (XML_ELEMENT_NODE === $child->nodeType) {
                $children[] = $child->localName;
            }
        }

        self::assertSame(['pStyle', 'rPr'], $children);
    }
}
