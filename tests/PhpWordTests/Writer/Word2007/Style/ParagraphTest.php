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

namespace PhpOffice\PhpWordTests\Writer\Word2007\Style;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Paragraph as ParagraphStyle;
use PhpOffice\PhpWord\Style\Tab;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Style\Paragraph.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Style\Paragraph
 *
 * @runTestsInSeparateProcesses
 */
class ParagraphTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test write styles.
     */
    public function testParagraphNumbering(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addParagraphStyle('testStyle', ['indent' => '10']);
        $section = $phpWord->addSection();
        $section->addText('test', null, ['numStyle' => 'testStyle', 'numLevel' => '1']);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:p/w:pPr/w:numPr/w:ilvl';
        self::assertTrue($doc->elementExists($path));
        self::assertEquals('1', $doc->getElementAttribute('/w:document/w:body/w:p/w:pPr/w:outlineLvl', 'w:val'));
    }

    public function testLineSpacingExact(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('test', null, ['spacing' => 240, 'spacingLineRule' => 'exact']);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:p/w:pPr/w:spacing';
        self::assertTrue($doc->elementExists($path));
        self::assertEquals('exact', $doc->getElementAttribute($path, 'w:lineRule'));
        self::assertEquals('240', $doc->getElementAttribute($path, 'w:line'));
    }

    public function testLineSpacingAuto(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('test', null, ['spacing' => 240, 'spacingLineRule' => 'auto']);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:p/w:pPr/w:spacing';
        self::assertTrue($doc->elementExists($path));
        self::assertEquals('auto', $doc->getElementAttribute($path, 'w:lineRule'));
        self::assertEquals('480', $doc->getElementAttribute($path, 'w:line'));
    }

    public function testSuppressAutoHyphens(): void
    {
        $paragraphStyle = new ParagraphStyle();
        $paragraphStyle->setSuppressAutoHyphens(true);

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('test', null, $paragraphStyle);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:p/w:pPr/w:suppressAutoHyphens';
        self::assertTrue($doc->elementExists($path));
    }

    /**
     * The properties of a paragraph are written in the order the schema names them.
     *
     * `CT_PPr` is a sequence and not a set, so a reader that walks it stops at the first
     * child it did not expect. The order asserted below is the one in ECMA-376, in
     * `CT_PPrBase`.
     */
    public function testParagraphPropertiesAreWrittenInSchemaOrder(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addNumberingStyle('Numbering', [
            'type' => 'multilevel',
            'levels' => [
                0 => ['text' => '%1.', 'left' => 360, 'hanging' => 360],
            ],
        ]);

        $paragraphStyle = new ParagraphStyle();
        $paragraphStyle->setStyleName('AllProps');
        $paragraphStyle->setKeepNext(true);
        $paragraphStyle->setKeepLines(true);
        $paragraphStyle->setPageBreakBefore(true);
        $paragraphStyle->setWidowControl(false);
        $paragraphStyle->setNumStyle('Numbering');
        $paragraphStyle->setNumLevel(1);
        $paragraphStyle->setBorderSize(10);
        $paragraphStyle->setBorderColor('000000');
        $paragraphStyle->setShading(['fill' => 'FF0000', 'type' => 'clear']);
        $paragraphStyle->setTabs([new Tab('left', 100)]);
        $paragraphStyle->setSuppressAutoHyphens(true);
        $paragraphStyle->setBidi(true);
        $paragraphStyle->setSpaceBefore(10);
        $paragraphStyle->setSpaceAfter(20);
        $paragraphStyle->setSpacing(240);
        $paragraphStyle->setIndentation(['left' => 100, 'right' => 50, 'firstLine' => 25]);
        $paragraphStyle->setContextualSpacing(true);
        $paragraphStyle->setAlignment('center');
        $paragraphStyle->setTextAlignment('top');

        $section = $phpWord->addSection();
        $section->addText('A paragraph with every property set.', null, $paragraphStyle);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        $pPr = $doc->getElement('/w:document/w:body/w:p/w:pPr', 'word/document.xml');
        self::assertNotNull($pPr);

        $written = [];
        foreach ($pPr->childNodes as $child) {
            if (XML_ELEMENT_NODE === $child->nodeType) {
                $written[] = $child->localName;
            }
        }

        self::assertSame([
            'pStyle',
            'keepNext',
            'keepLines',
            'pageBreakBefore',
            'widowControl',
            'numPr',
            'pBdr',
            'shd',
            'tabs',
            'suppressAutoHyphens',
            'bidi',
            'spacing',
            'ind',
            'contextualSpacing',
            'jc',
            'textAlignment',
            'outlineLvl',
        ], $written);
    }

    /**
     * The numbering of a paragraph names the level before the number.
     *
     * `CT_NumPr` is a sequence and not a set, so a reader that walks it stops at the first
     * child it did not expect. The order asserted below is the one in ECMA-376, in
     * `CT_NumPr`.
     */
    public function testNumberingIsWrittenInSchemaOrder(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addNumberingStyle('Numbering', [
            'type' => 'multilevel',
            'levels' => [
                0 => ['text' => '%1.', 'left' => 360, 'hanging' => 360],
            ],
        ]);

        $paragraphStyle = new ParagraphStyle();
        $paragraphStyle->setNumStyle('Numbering');
        $paragraphStyle->setNumLevel(1);

        $section = $phpWord->addSection();
        $section->addText('A numbered paragraph.', null, $paragraphStyle);

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
}
