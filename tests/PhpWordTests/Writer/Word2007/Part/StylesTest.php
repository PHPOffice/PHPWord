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

namespace PhpOffice\PhpWordTests\Writer\Word2007\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWordTests\TestHelperDOCX;
use PhpOffice\PhpWordTests\XmlDocument;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Part\Styles.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Part\Styles
 *
 * @runTestsInSeparateProcesses
 */
class StylesTest extends \PHPUnit\Framework\TestCase
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
    public function testWriteStyles(): void
    {
        $phpWord = new PhpWord();

        $pStyle = ['alignment' => Jc::BOTH];
        $pBase = ['basedOn' => 'Normal'];
        $pNew = ['basedOn' => 'Base Style', 'next' => 'Normal'];
        $rStyle = ['size' => 20];
        $tStyle = ['bgColor' => 'FF0000', 'cellMargin' => 120, 'borderSize' => 120];
        $firstRowStyle = ['bgColor' => '0000FF', 'borderSize' => 120, 'borderColor' => '00FF00'];
        $phpWord->setDefaultParagraphStyle($pStyle);
        $phpWord->addParagraphStyle('Base Style', $pBase);
        $phpWord->addParagraphStyle('New Style', $pNew);
        $phpWord->addFontStyle('New Style', $rStyle, $pStyle);
        $phpWord->addTableStyle('Table Style', $tStyle, $firstRowStyle);
        $phpWord->addTitleStyle(1, $rStyle, $pStyle);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/styles.xml';

        // Normal style generated?
        $path = '/w:styles/w:style[@w:styleId="Normal"]/w:name';
        $element = $doc->getElement($path, $file);
        self::assertEquals('Normal', $element->getAttribute('w:val'));

        // Parent style referenced?
        $path = '/w:styles/w:style[@w:styleId="New Style"]/w:basedOn';
        $element = $doc->getElement($path, $file);
        self::assertEquals('Base Style', $element->getAttribute('w:val'));

        // Next paragraph style correct?
        $path = '/w:styles/w:style[@w:styleId="New Style"]/w:next';
        $element = $doc->getElement($path, $file);
        self::assertEquals('Normal', $element->getAttribute('w:val'));
    }

    public function testFontStyleBasedOn(): void
    {
        $phpWord = new PhpWord();

        $baseParagraphStyle = new Paragraph();
        $baseParagraphStyle->setAlignment(Jc::CENTER);
        $baseParagraphStyle = $phpWord->addParagraphStyle('BaseStyle', $baseParagraphStyle);

        $childFont = new Font();
        $childFont->setParagraph($baseParagraphStyle);
        $childFont->setSize(16);
        $childFont = $phpWord->addFontStyle('ChildFontStyle', $childFont);

        $otherFont = new Font();
        $otherFont->setSize(20);
        $otherFont = $phpWord->addFontStyle('OtherFontStyle', $otherFont);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/styles.xml';

        // Normal style generated?
        $path = '/w:styles/w:style[@w:styleId="BaseStyle"]/w:name';
        $element = $doc->getElement($path, $file);
        self::assertEquals('BaseStyle', $element->getAttribute('w:val'));

        // Font style with paragraph should have it's base style set to that paragraphs style name
        $path = '/w:styles/w:style[w:name/@w:val="ChildFontStyle"]/w:basedOn';
        $element = $doc->getElement($path, $file);
        self::assertEquals('BaseStyle', $element->getAttribute('w:val'));

        // Font style without paragraph should not have a base style set
        $path = '/w:styles/w:style[w:name/@w:val="OtherFontStyle"]/w:basedOn';
        $element = $doc->getElement($path, $file);
        self::assertNull($element);
    }

    public function testFontStyleBasedOnOtherFontStyle(): void
    {
        $phpWord = new PhpWord();

        $styleGenerationP = new Paragraph();
        $styleGenerationP->setAlignment(Jc::BOTH);

        $styleGeneration = new Font();
        $styleGeneration->setParagraph($styleGenerationP);
        $styleGeneration->setSize(9.5);
        $phpWord->addFontStyle('Generation', $styleGeneration);

        $styleGenerationEteinteP = new Paragraph();
        $styleGenerationEteinteP->setBasedOn('Generation');

        $styleGenerationEteinte = new Font();
        $styleGenerationEteinte->setParagraph($styleGenerationEteinteP);
        $styleGenerationEteinte->setSize(8.5);
        $phpWord->addFontStyle('GeneratEteinte', $styleGenerationEteinte);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/styles.xml';

        $path = '/w:styles/w:style[@w:styleId="GeneratEteinte"]/w:basedOn';
        $element = $doc->getElement($path, $file);
        self::assertEquals('Generation', $element->getAttribute('w:val'));
    }

    /**
     * The children of w:style are a sequence in the schema and not a set, so a reader
     * that walks it stops at the first child it did not expect. The order asserted below
     * is the one in ECMA-376, in CT_Style, for every shape of style the writer writes.
     */
    public function testStyleChildrenInSchemaOrder(): void
    {
        $phpWord = new PhpWord();

        $phpWord->setDefaultParagraphStyle(['alignment' => Jc::BOTH]);
        $phpWord->addTitleStyle(1, ['size' => 14], ['alignment' => Jc::LEFT]);
        $phpWord->addParagraphStyle('New Style', ['basedOn' => 'Base Style', 'next' => 'Normal']);
        $phpWord->addFontStyle('Char Style', ['size' => 20]);
        $phpWord->addFontStyle('Font Style', ['size' => 18], ['alignment' => Jc::CENTER]);
        $phpWord->addTableStyle('Table Style', ['bgColor' => 'FF0000'], ['bgColor' => '0000FF']);

        $section = $phpWord->addSection();
        $section->addTitle('A title without a title style', 2);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/styles.xml';

        // The default paragraph style has a name and the properties of the paragraph.
        self::assertSame(
            ['w:name', 'w:pPr'],
            $this->childrenOfStyle($doc, '/w:styles/w:style[@w:styleId="Normal"]', $file)
        );

        // The default character style has a name, is hidden until it is used, and has properties.
        self::assertSame(
            ['w:name', 'w:semiHidden', 'w:unhideWhenUsed', 'w:rPr'],
            $this->childrenOfStyle($doc, '/w:styles/w:style[@w:styleId="FootnoteReference"]', $file)
        );

        // A paragraph style has a name, a parent style, the style to use next and properties.
        self::assertSame(
            ['w:name', 'w:basedOn', 'w:next', 'w:pPr'],
            $this->childrenOfStyle($doc, '/w:styles/w:style[@w:styleId="New Style"]', $file)
        );

        // A character style has a name and properties; it has no style id of its own.
        self::assertSame(
            ['w:name', 'w:rPr'],
            $this->childrenOfStyle($doc, '/w:styles/w:style[w:name/@w:val="Char Style"]', $file)
        );

        // A font style that carries a paragraph has a name, a parent style and properties.
        self::assertSame(
            ['w:name', 'w:basedOn', 'w:pPr', 'w:rPr'],
            $this->childrenOfStyle($doc, '/w:styles/w:style[@w:styleId="Font Style"]', $file)
        );

        // A heading has a name, a parent style, a linked character style and properties.
        self::assertSame(
            ['w:name', 'w:basedOn', 'w:link', 'w:pPr', 'w:rPr'],
            $this->childrenOfStyle($doc, '/w:styles/w:style[@w:styleId="Heading1"]', $file)
        );

        // The link of a heading names the character style it is paired with.
        self::assertSame(
            'Heading1Char',
            $doc->getElementAttribute('/w:styles/w:style[@w:styleId="Heading1"]/w:link', 'w:val', $file)
        );

        // A heading without a title style has a name, a linked character style and properties.
        self::assertSame(
            ['w:name', 'w:link', 'w:pPr', 'w:rPr'],
            $this->childrenOfStyle($doc, '/w:styles/w:style[@w:styleId="Heading2"]', $file)
        );

        // A table style has a name, a priority, the properties of the table, of its cells
        // and of its first row.
        self::assertSame(
            ['w:name', 'w:uiPriority', 'w:tblPr', 'w:tcPr', 'w:tblStylePr'],
            $this->childrenOfStyle($doc, '/w:styles/w:style[@w:styleId="Table Style"]', $file)
        );
    }

    /**
     * Get the names of the children of a style, in the order they are written.
     *
     * @return string[]
     */
    private function childrenOfStyle(XmlDocument $doc, string $path, string $file): array
    {
        $children = [];
        foreach ($doc->getNodeList($path . '/*', $file) as $node) {
            $children[] = $node->nodeName;
        }

        return $children;
    }

    /**
     * Test default font color.
     */
    public function testDefaultDefaultFontColor(): void
    {
        $phpWord = new PhpWord();

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/styles.xml';

        $path = '/w:styles/w:docDefaults/w:rPrDefault/w:rPr/w:color';
        self::assertTrue($doc->elementExists($path, $file));
        $element = $doc->getElement($path, $file);

        self::assertEquals('000000', $element->getAttribute('w:val'));
    }

    /**
     * Test default font color.
     */
    public function testDefaultFontColor(): void
    {
        $phpWord = new PhpWord();
        $defaultFontColor = '00FF00';
        $phpWord->setDefaultFontColor($defaultFontColor);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/styles.xml';

        $path = '/w:styles/w:docDefaults/w:rPrDefault/w:rPr/w:color';
        self::assertTrue($doc->elementExists($path, $file));
        $element = $doc->getElement($path, $file);

        self::assertEquals($defaultFontColor, $element->getAttribute('w:val'));
    }
}
