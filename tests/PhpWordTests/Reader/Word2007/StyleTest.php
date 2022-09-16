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

namespace PhpOffice\PhpWordTests\Reader\Word2007;

use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\SimpleType\VerticalJc;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Table;
use PhpOffice\PhpWord\Style\TablePosition;
use PhpOffice\PhpWordTests\AbstractTestReader;

/**
 * Test class for PhpOffice\PhpWord\Reader\Word2007\Styles.
 */
class StyleTest extends AbstractTestReader
{
    /**
     * Test reading of table layout.
     */
    public function testReadTableLayout(): void
    {
        $documentXml = '<w:tbl>
            <w:tblPr>
                <w:tblLayout w:type="fixed"/>
            </w:tblPr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        self::assertInstanceOf('PhpOffice\PhpWord\Style\Table', $elements[0]->getStyle());
        self::assertEquals(Table::LAYOUT_FIXED, $elements[0]->getStyle()->getLayout());
    }

    /**
     * Test reading of cell spacing.
     */
    public function testReadCellSpacing(): void
    {
        $documentXml = '<w:tbl>
            <w:tblPr>
                <w:tblCellSpacing w:w="10.5" w:type="dxa"/>
            </w:tblPr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        self::assertInstanceOf('PhpOffice\PhpWord\Style\Table', $elements[0]->getStyle());
        /** @var \PhpOffice\PhpWord\Style\Table $tableStyle */
        $tableStyle = $elements[0]->getStyle();
        self::assertEquals(TblWidth::AUTO, $tableStyle->getUnit());
        self::assertEquals(10.5, $tableStyle->getCellSpacing());
    }

    /**
     * Test reading of table position.
     */
    public function testReadTablePosition(): void
    {
        $documentXml = '<w:tbl>
            <w:tblPr>
                <w:tblpPr w:leftFromText="10" w:rightFromText="20" w:topFromText="30" w:bottomFromText="40" w:vertAnchor="page" w:horzAnchor="margin" w:tblpXSpec="center" w:tblpX="50" w:tblpYSpec="top" w:tblpY="60"/>
            </w:tblPr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        self::assertInstanceOf('PhpOffice\PhpWord\Style\Table', $elements[0]->getStyle());
        self::assertNotNull($elements[0]->getStyle()->getPosition());
        self::assertInstanceOf('PhpOffice\PhpWord\Style\TablePosition', $elements[0]->getStyle()->getPosition());
        /** @var \PhpOffice\PhpWord\Style\TablePosition $tableStyle */
        $tableStyle = $elements[0]->getStyle()->getPosition();
        self::assertEquals(10, $tableStyle->getLeftFromText());
        self::assertEquals(20, $tableStyle->getRightFromText());
        self::assertEquals(30, $tableStyle->getTopFromText());
        self::assertEquals(40, $tableStyle->getBottomFromText());
        self::assertEquals(TablePosition::VANCHOR_PAGE, $tableStyle->getVertAnchor());
        self::assertEquals(TablePosition::HANCHOR_MARGIN, $tableStyle->getHorzAnchor());
        self::assertEquals(TablePosition::XALIGN_CENTER, $tableStyle->getTblpXSpec());
        self::assertEquals(50, $tableStyle->getTblpX());
        self::assertEquals(TablePosition::YALIGN_TOP, $tableStyle->getTblpYSpec());
        self::assertEquals(60, $tableStyle->getTblpY());
    }

    /**
     * Test reading of position.
     */
    public function testReadPosition(): void
    {
        $documentXml = '<w:p>
            <w:r>
                <w:rPr>
                    <w:position w:val="15"/>
                </w:rPr>
                <w:t xml:space="preserve">This text is lowered</w:t>
            </w:r>
        </w:p>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        /** @var \PhpOffice\PhpWord\Element\TextRun $elements */
        $textRun = $elements[0];
        self::assertInstanceOf('PhpOffice\PhpWord\Element\TextRun', $textRun);
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Text', $textRun->getElement(0));
        self::assertInstanceOf('PhpOffice\PhpWord\Style\Font', $textRun->getElement(0)->getFontStyle());
        /** @var \PhpOffice\PhpWord\Style\Font $fontStyle */
        $fontStyle = $textRun->getElement(0)->getFontStyle();
        self::assertEquals(15, $fontStyle->getPosition());
    }

    public function testReadIndent(): void
    {
        $documentXml = '<w:tbl>
            <w:tblPr>
                <w:tblInd w:w="2160" w:type="dxa"/>
            </w:tblPr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        self::assertInstanceOf('PhpOffice\PhpWord\Style\Table', $elements[0]->getStyle());
        /** @var \PhpOffice\PhpWord\Style\Table $tableStyle */
        $tableStyle = $elements[0]->getStyle();
        self::assertSame(TblWidth::TWIP, $tableStyle->getIndent()->getType());
        self::assertSame(2160, $tableStyle->getIndent()->getValue());
    }

    public function testReadTableRTL(): void
    {
        $documentXml = '<w:tbl>
            <w:tblPr>
                <w:bidiVisual w:val="1"/>
            </w:tblPr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        self::assertInstanceOf('PhpOffice\PhpWord\Style\Table', $elements[0]->getStyle());
        /** @var \PhpOffice\PhpWord\Style\Table $tableStyle */
        $tableStyle = $elements[0]->getStyle();
        self::assertTrue($tableStyle->isBidiVisual());
    }

    public function testReadHidden(): void
    {
        $documentXml = '<w:p>
            <w:r>
                <w:rPr>
                    <w:vanish/>
                </w:rPr>
                <w:t xml:space="preserve">This text is hidden</w:t>
            </w:r>
        </w:p>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        /** @var \PhpOffice\PhpWord\Element\TextRun $elements */
        $textRun = $elements[0];
        self::assertInstanceOf('PhpOffice\PhpWord\Element\TextRun', $textRun);
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Text', $textRun->getElement(0));
        self::assertInstanceOf('PhpOffice\PhpWord\Style\Font', $textRun->getElement(0)->getFontStyle());
        /** @var \PhpOffice\PhpWord\Style\Font $fontStyle */
        $fontStyle = $textRun->getElement(0)->getFontStyle();
        self::assertTrue($fontStyle->isHidden());
    }

    public function testReadHeading(): void
    {
        Style::resetStyles();

        $documentXml = '<w:style w:type="paragraph" w:styleId="Ttulo1">
            <w:name w:val="heading 1"/>
            <w:basedOn w:val="Normal"/>
            <w:uiPriority w:val="1"/>
            <w:qFormat/>
            <w:pPr>
                <w:outlineLvl w:val="0"/>
            </w:pPr>
            <w:rPr>
                <w:rFonts w:ascii="Times New Roman" w:eastAsia="Times New Roman" w:hAnsi="Times New Roman"/>
                <w:b/>
                <w:bCs/>
            </w:rPr>
        </w:style>';

        $name = 'Heading_1';

        $this->getDocumentFromString(['styles' => $documentXml]);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', Style::getStyle($name));
    }

    public function testPageVerticalAlign(): void
    {
        $documentXml = '<w:sectPr>
            <w:vAlign w:val="center"/>
        </w:sectPr>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $sectionStyle = $phpWord->getSection(0)->getStyle();
        self::assertEquals(VerticalJc::CENTER, $sectionStyle->getVAlign());
    }
}
