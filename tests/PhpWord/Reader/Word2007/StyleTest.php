<?php
declare(strict_types=1);
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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Reader\Word2007;

use PhpOffice\PhpWord\AbstractTestReader;
use PhpOffice\PhpWord\SimpleType\VerticalJc;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Lengths\Absolute;
use PhpOffice\PhpWord\Style\Lengths\Auto;
use PhpOffice\PhpWord\Style\Lengths\Percent;
use PhpOffice\PhpWord\Style\Table;
use PhpOffice\PhpWord\Style\TablePosition;

/**
 * Test class for PhpOffice\PhpWord\Reader\Word2007\Styles
 */
class StyleTest extends AbstractTestReader
{
    /**
     * Test reading of table layout
     */
    public function testReadTableLayout()
    {
        $documentXml = '<w:tbl>
            <w:tblPr>
                <w:tblLayout w:type="fixed"/>
            </w:tblPr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(array('document' => $documentXml));

        $elements = $phpWord->getSection(0)->getElements();
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        $this->assertInstanceOf('PhpOffice\PhpWord\Style\Table', $elements[0]->getStyle());
        $this->assertEquals(Table::LAYOUT_FIXED, $elements[0]->getStyle()->getLayout());
    }

    /**
     * Test reading of cell spacing
     */
    public function testReadCellSpacing()
    {
        $documentXml = '<w:tbl>
            <w:tblPr>
                <w:tblCellSpacing w:w="10.5" w:type="dxa"/>
            </w:tblPr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(array('document' => $documentXml));

        $elements = $phpWord->getSection(0)->getElements();
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        $this->assertInstanceOf('PhpOffice\PhpWord\Style\Table', $elements[0]->getStyle());
        /** @var \PhpOffice\PhpWord\Style\Table $tableStyle */
        $tableStyle = $elements[0]->getStyle();
        $this->assertEquals(10.5, $tableStyle->getCellSpacing()->toFloat('twip'));
    }

    /**
     * Test reading of cell spacing
     */
    public function testReadCellWidth()
    {
        $documentXml = '<w:tbl>
          <w:tblGrid/>
          <w:tblPr>
            <w:tblW w:type="auto"/>
            <w:tblInd w:type="auto"/>
            <w:tblLayout w:type="autofit"/>
            <w:bidiVisual w:val="0"/>
          </w:tblPr>
          <w:tr>
            <w:trPr>
              <w:trHeight w:val="" w:hRule="atLeast"/>
            </w:trPr>
            <w:tc>
              <w:tcPr>
                <w:tcW w:w="10.3" w:type="dxa"/>
              </w:tcPr>
              <w:p/>
            </w:tc>
            <w:tc>
              <w:tcPr>
                <w:tcW w:type="nil"/>
              </w:tcPr>
              <w:p/>
            </w:tc>
            <w:tc>
              <w:tcPr>
                <w:tcW w:w="50%" w:type="pct"/>
              </w:tcPr>
              <w:p/>
            </w:tc>
            <w:tc>
              <w:tcPr>
                <w:tcW w:type="nil"/>
              </w:tcPr>
              <w:p/>
            </w:tc>
            <w:tc>
              <w:tcPr>
                <w:tcW w:type="auto"/>
              </w:tcPr>
              <w:p/>
            </w:tc>
          </w:tr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(array('document' => $documentXml));

        $cells = $phpWord->getSection(0)->getElement(0)->getRows()[0]->getCells();

        $this->assertEquals(Absolute::from('twip', 10.3), $cells[0]->getWidth());
        $this->assertEquals(new Absolute(0), $cells[1]->getWidth());
        $this->assertEquals(new Percent(50), $cells[2]->getWidth());
        $this->assertEquals(new Absolute(0), $cells[3]->getWidth());
        $this->assertEquals(new Auto(), $cells[4]->getWidth());
    }

    /**
     * Test reading of cell spacing
     * @depends testReadCellWidth
     * @expectedException \PHPUnit\Framework\Error\Warning
     * @expectedExceptionMessage Cell width type `bad` is not supported
     */
    public function testReadCellWidthBadType()
    {
        $documentXml = '<w:tbl>
          <w:tblGrid/>
          <w:tblPr>
            <w:tblW w:type="auto"/>
            <w:tblInd w:type="auto"/>
            <w:tblLayout w:type="autofit"/>
            <w:bidiVisual w:val="0"/>
          </w:tblPr>
          <w:tr>
            <w:trPr>
              <w:trHeight w:val="" w:hRule="atLeast"/>
            </w:trPr>
            <w:tc>
              <w:tcPr>
                <w:tcW w:type="bad"/>
              </w:tcPr>
              <w:p/>
            </w:tc>
          </w:tr>
        </w:tbl>';

        $this->getDocumentFromString(array('document' => $documentXml));
    }

    /**
     * Test reading of cell spacing
     * @depends testReadCellWidthBadType
     */
    public function testReadCellWidthBadTypeReturn()
    {
        $documentXml = '<w:tbl>
          <w:tblGrid/>
          <w:tblPr>
            <w:tblW w:type="auto"/>
            <w:tblInd w:type="auto"/>
            <w:tblLayout w:type="autofit"/>
            <w:bidiVisual w:val="0"/>
          </w:tblPr>
          <w:tr>
            <w:trPr>
              <w:trHeight w:val="" w:hRule="atLeast"/>
            </w:trPr>
            <w:tc>
              <w:tcPr>
                <w:tcW w:type="bad"/>
              </w:tcPr>
              <w:p/>
            </w:tc>
          </w:tr>
        </w:tbl>';

        $phpWord = @$this->getDocumentFromString(array('document' => $documentXml));

        $cells = $phpWord->getSection(0)->getElement(0)->getRows()[0]->getCells();

        $this->assertEquals(new Absolute(null), $cells[0]->getWidth());
    }

    /**
     * Test reading of table position
     */
    public function testReadTablePosition()
    {
        $documentXml = '<w:tbl>
            <w:tblPr>
                <w:tblpPr w:leftFromText="10" w:rightFromText="20" w:topFromText="30" w:bottomFromText="40" w:vertAnchor="page" w:horzAnchor="margin" w:tblpXSpec="center" w:tblpX="50" w:tblpYSpec="top" w:tblpY="60"/>
            </w:tblPr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(array('document' => $documentXml));

        $elements = $phpWord->getSection(0)->getElements();
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        $this->assertInstanceOf('PhpOffice\PhpWord\Style\Table', $elements[0]->getStyle());
        $this->assertNotNull($elements[0]->getStyle()->getPosition());
        $this->assertInstanceOf('PhpOffice\PhpWord\Style\TablePosition', $elements[0]->getStyle()->getPosition());
        /** @var \PhpOffice\PhpWord\Style\TablePosition $tableStyle */
        $tableStyle = $elements[0]->getStyle()->getPosition();
        $this->assertEquals(10, $tableStyle->getLeftFromText()->toInt('twip'));
        $this->assertEquals(20, $tableStyle->getRightFromText()->toInt('twip'));
        $this->assertEquals(30, $tableStyle->getTopFromText()->toInt('twip'));
        $this->assertEquals(40, $tableStyle->getBottomFromText()->toInt('twip'));
        $this->assertEquals(TablePosition::VANCHOR_PAGE, $tableStyle->getVertAnchor());
        $this->assertEquals(TablePosition::HANCHOR_MARGIN, $tableStyle->getHorzAnchor());
        $this->assertEquals(TablePosition::XALIGN_CENTER, $tableStyle->getTblpXSpec());
        $this->assertEquals(50, $tableStyle->getTblpX()->toInt('twip'));
        $this->assertEquals(TablePosition::YALIGN_TOP, $tableStyle->getTblpYSpec());
        $this->assertEquals(60, $tableStyle->getTblpY()->toInt('twip'));
    }

    /**
     * Test reading of position
     */
    public function testReadPosition()
    {
        $documentXml = '<w:p>
            <w:r>
                <w:rPr>
                    <w:position w:val="15"/>
                </w:rPr>
                <w:t xml:space="preserve">This text is lowered</w:t>
            </w:r>
        </w:p>';

        $phpWord = $this->getDocumentFromString(array('document' => $documentXml));

        $elements = $phpWord->getSection(0)->getElements();
        /** @var \PhpOffice\PhpWord\Element\TextRun $elements */
        $textRun = $elements[0];
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\TextRun', $textRun);
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Text', $textRun->getElement(0));
        $this->assertInstanceOf('PhpOffice\PhpWord\Style\Font', $textRun->getElement(0)->getFontStyle());
        /** @var \PhpOffice\PhpWord\Style\Font $fontStyle */
        $fontStyle = $textRun->getElement(0)->getFontStyle();
        $this->assertEquals(15, $fontStyle->getPosition()->toInt('twip'));
    }

    public function testReadIndent()
    {
        $indents = array(
            array(2160, 'dxa', Absolute::from('twip', 2160)),
            array('', 'nil', new Absolute(0)),
            array('', 'auto', new Absolute(null)),
            array(50, 'pct', new Absolute(null)),
        );
        foreach ($indents as $indent) {
            $documentXml = '<w:tbl>
                <w:tblPr>
                    <w:tblInd w:w="' . $indent[0] . '" w:type="' . $indent[1] . '"/>
                </w:tblPr>
            </w:tbl>';

            $phpWord = $this->getDocumentFromString(array('document' => $documentXml));

            $elements = $phpWord->getSection(0)->getElements();
            $this->assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
            $this->assertInstanceOf('PhpOffice\PhpWord\Style\Table', $elements[0]->getStyle());
            /** @var \PhpOffice\PhpWord\Style\Table $tableStyle */
            $tableStyle = $elements[0]->getStyle();
            $this->assertEquals($indent[2], $tableStyle->getIndent());
        }
    }

    /**
     * @depends testReadIndent
     * @expectedException \PHPUnit\Framework\Error\Warning
     * @expectedExceptionMessage Table indent type `bad` is not supported
     */
    public function testReadIndentBadType()
    {
        $documentXml = '<w:tbl>
            <w:tblPr>
                <w:tblInd w:w="54" w:type="bad"/>
            </w:tblPr>
        </w:tbl>';

        $this->getDocumentFromString(array('document' => $documentXml));
    }

    /**
     * @depends testReadIndent
     */
    public function testReadIndentBadTypeReturn()
    {
        $documentXml = '<w:tbl>
            <w:tblPr>
                <w:tblInd w:w="54" w:type="bad"/>
            </w:tblPr>
        </w:tbl>';

        $phpWord = @$this->getDocumentFromString(array('document' => $documentXml));

        $elements = $phpWord->getSection(0)->getElements();
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        $this->assertInstanceOf('PhpOffice\PhpWord\Style\Table', $elements[0]->getStyle());
        /** @var \PhpOffice\PhpWord\Style\Table $tableStyle */
        $tableStyle = $elements[0]->getStyle();
        $this->assertEquals(new Absolute(null), $tableStyle->getIndent());
    }

    public function testReadTableRTL()
    {
        $documentXml = '<w:tbl>
            <w:tblPr>
                <w:bidiVisual w:val="1"/>
            </w:tblPr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(array('document' => $documentXml));

        $elements = $phpWord->getSection(0)->getElements();
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        $this->assertInstanceOf('PhpOffice\PhpWord\Style\Table', $elements[0]->getStyle());
        /** @var \PhpOffice\PhpWord\Style\Table $tableStyle */
        $tableStyle = $elements[0]->getStyle();
        $this->assertTrue($tableStyle->isBidiVisual());
    }

    public function testReadHidden()
    {
        $documentXml = '<w:p>
            <w:r>
                <w:rPr>
                    <w:vanish/>
                </w:rPr>
                <w:t xml:space="preserve">This text is hidden</w:t>
            </w:r>
        </w:p>';

        $phpWord = $this->getDocumentFromString(array('document' => $documentXml));

        $elements = $phpWord->getSection(0)->getElements();
        /** @var \PhpOffice\PhpWord\Element\TextRun $elements */
        $textRun = $elements[0];
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\TextRun', $textRun);
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Text', $textRun->getElement(0));
        $this->assertInstanceOf('PhpOffice\PhpWord\Style\Font', $textRun->getElement(0)->getFontStyle());
        /** @var \PhpOffice\PhpWord\Style\Font $fontStyle */
        $fontStyle = $textRun->getElement(0)->getFontStyle();
        $this->assertTrue($fontStyle->isHidden());
    }

    public function testReadHeading()
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

        $this->getDocumentFromString(array('styles' => $documentXml));
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', Style::getStyle($name));
    }

    public function testPageVerticalAlign()
    {
        $documentXml = '<w:sectPr>
            <w:vAlign w:val="center"/>
        </w:sectPr>';

        $phpWord = $this->getDocumentFromString(array('document' => $documentXml));

        $sectionStyle = $phpWord->getSection(0)->getStyle();
        $this->assertEquals(VerticalJc::CENTER, $sectionStyle->getVAlign());
    }
}
