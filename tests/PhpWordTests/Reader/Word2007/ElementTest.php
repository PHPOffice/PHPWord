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

use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TrackChange;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWordTests\AbstractTestReader;

/**
 * Test class for PhpOffice\PhpWord\Reader\Word2007\Element subnamespace.
 */
class ElementTest extends AbstractTestReader
{
    /**
     * Test reading of alternate content value.
     */
    public function testReadAlternateContent(): void
    {
        $documentXml = '<w:p>
            <w:r>
                <mc:AlternateContent>
                    <mc:Choice Requires="wps"></mc:Choice>
                    <mc:Fallback>
                        <w:pict>
                            <v:rect>
                                <v:textbox>
                                    <w:txbxContent>
                                        <w:p>
                                            <w:pPr>
                                                <w:jc w:val="center"/>
                                            </w:pPr>
                                            <w:r>
                                                <w:t>Test node value</w:t>
                                            </w:r>
                                        </w:p>
                                    </w:txbxContent>
                                </v:textbox>
                            </v:rect>
                        </w:pict>
                    </mc:Fallback>
                </mc:AlternateContent>
            </w:r>
        </w:p>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\TextRun', $elements[0]);
        self::assertInstanceOf(Text::class, $elements[0]->getElement(0));
        $text = $elements[0];
        self::assertEquals('Test node value', trim($text->getElement(0)->getText()));
    }

    /**
     * Test reading of textbreak.
     */
    public function testReadTextBreak(): void
    {
        $documentXml = '<w:p>
            <w:r>
                <w:br/>
                <w:t xml:space="preserve">test string</w:t>
            </w:r>
        </w:p>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\TextRun', $elements[0]);
        /** @var \PhpOffice\PhpWord\Element\TextRun $textRun */
        $textRun = $elements[0];
        self::assertInstanceOf('PhpOffice\PhpWord\Element\TextBreak', $textRun->getElement(0));
        self::assertInstanceOf(Text::class, $textRun->getElement(1));
        self::assertEquals('test string', $textRun->getElement(1)->getText());
    }

    /**
     * Test reading content inside w:smartTag.
     */
    public function testSmartTag(): void
    {
        $documentXml = '<w:p>
            <w:smartTag>
                <w:r>
                    <w:t xml:space="preserve">test string</w:t>
                </w:r>
            </w:smartTag>
        </w:p>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\TextRun', $elements[0]);
        /** @var \PhpOffice\PhpWord\Element\TextRun $textRun */
        $textRun = $elements[0];
        self::assertInstanceOf(Text::class, $textRun->getElement(0));
        self::assertEquals('test string', $textRun->getElement(0)->getText());
    }

    /**
     * Test reading of textbreak.
     */
    public function testReadListItemRunWithFormatting(): void
    {
        $documentXml = '<w:p>
            <w:pPr>
                <w:numPr>
                    <w:ilvl w:val="0"/>
                    <w:numId w:val="11"/>
                </w:numPr>
            </w:pPr>
            <w:r>
                <w:t>Two</w:t>
            </w:r>
            <w:r>
                <w:t xml:space="preserve"> with </w:t>
            </w:r>
            <w:r>
                <w:rPr>
                    <w:b/>
                </w:rPr>
                <w:t>bold</w:t>
            </w:r>
        </w:p>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $sections = $phpWord->getSection(0);
        self::assertNull($sections->getElement(999));
        self::assertInstanceOf('PhpOffice\PhpWord\Element\ListItemRun', $sections->getElement(0));
        self::assertEquals(0, $sections->getElement(0)->getDepth());

        $listElements = $sections->getElement(0)->getElements();
        /** @var Text $listElement0 */
        $listElement0 = $listElements[0];
        self::assertInstanceOf(Text::class, $listElement0);
        self::assertEquals('Two', $listElement0->getText());
        /** @var Text $listElement1 */
        $listElement1 = $listElements[1];
        self::assertEquals(' with ', $listElement1->getText());
        /** @var Text $listElement2 */
        $listElement2 = $listElements[2];
        self::assertEquals('bold', $listElement2->getText());
        /** @var Font $listElement2FontStyle */
        $listElement2FontStyle = $listElement2->getFontStyle();
        self::assertInstanceOf(Font::class, $listElement2FontStyle);
        self::assertTrue($listElement2FontStyle->isBold());
    }

    /**
     * Test reading track changes.
     */
    public function testReadTrackChange(): void
    {
        $documentXml = '<w:p>
            <w:r>
                <w:t>One </w:t>
            </w:r>
            <w:del w:author="Barney" w:date="2018-03-14T10:57:05Z">
                <w:r>
                    <w:delText>two</w:delText>
                </w:r>
            </w:del>
            <w:ins w:author="Fred" w:date="2018-03-14T10:57:05Z">
                	<w:r>
                    <w:t>three</w:t>
                	</w:r>
            </w:ins>
        </w:p>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\TextRun', $elements[0]);
        /** @var \PhpOffice\PhpWord\Element\TextRun $elements */
        $textRun = $elements[0];

        self::assertEquals('One ', $textRun->getElement(0)->getText());

        self::assertEquals('two', $textRun->getElement(1)->getText());
        self::assertNotNull($textRun->getElement(1)->getTrackChange());
        /** @var \PhpOffice\PhpWord\Element\TrackChange $trackChange */
        $trackChange = $textRun->getElement(1)->getTrackChange();
        self::assertEquals(TrackChange::DELETED, $trackChange->getChangeType());

        self::assertEquals('three', $textRun->getElement(2)->getText());
        self::assertNotNull($textRun->getElement(2)->getTrackChange());
        /** @var \PhpOffice\PhpWord\Element\TrackChange $trackChange */
        $trackChange = $textRun->getElement(2)->getTrackChange();
        self::assertEquals(TrackChange::INSERTED, $trackChange->getChangeType());
    }

    /**
     * Test reading of tab.
     */
    public function testReadTab(): void
    {
        $documentXml = '<w:p>
            <w:r>
                <w:t>One</w:t>
                <w:tab/>
                <w:t>Two</w:t>
            </w:r>
        </w:p>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\TextRun', $elements[0]);
        /** @var \PhpOffice\PhpWord\Element\TextRun $textRun */
        $textRun = $elements[0];
        self::assertInstanceOf(Text::class, $textRun->getElement(0));
        self::assertEquals('One', $textRun->getElement(0)->getText());
        self::assertInstanceOf(Text::class, $textRun->getElement(1));
        self::assertEquals("\t", $textRun->getElement(1)->getText());
        self::assertInstanceOf(Text::class, $textRun->getElement(2));
        self::assertEquals('Two', $textRun->getElement(2)->getText());
    }

    /**
     * Test reading Title style.
     */
    public function testReadTitleStyle(): void
    {
        $documentXml = '<w:p>
            <w:pPr>
                <w:pStyle w:val="Title"/>
            </w:pPr>
            <w:r>
                <w:t>This is a non formatted title</w:t>
            </w:r>
        </w:p>
        <w:p>
            <w:pPr>
                <w:pStyle w:val="Title"/>
            </w:pPr>
            <w:r>
                <w:t>This is a </w:t>
            </w:r>
            <w:r>
                <w:rPr>
                    <w:b/>
                </w:rPr>
                <w:t>bold</w:t>
            </w:r>
            <w:r>
                <w:t> title</w:t>
            </w:r>
        </w:p>';

        $stylesXml = '<w:style w:type="paragraph" w:styleId="Title">
            <w:name w:val="Title"/>
            <w:link w:val="TitleChar"/>
            <w:rPr>
                <w:i/>
            </w:rPr>
        </w:style>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml, 'styles' => $stylesXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Title', $elements[0]);
        /** @var \PhpOffice\PhpWord\Element\Title $title */
        $title = $elements[0];
        self::assertEquals('Title', $title->getStyle());
        self::assertEquals('This is a non formatted title', $title->getText());

        self::assertInstanceOf(\PhpOffice\PhpWord\Element\Title::class, $elements[1]);
        /** @var \PhpOffice\PhpWord\Element\Title $formattedTitle */
        $formattedTitle = $elements[1];
        self::assertEquals('Title', $formattedTitle->getStyle());
        self::assertInstanceOf(\PhpOffice\PhpWord\Element\TextRun::class, $formattedTitle->getText());
    }

    /**
     * Test reading of nested table.
     */
    public function testReadNestedTable(): void
    {
        $documentXml = '<w:tbl>
          <w:tr>
            <w:tc>
              <w:tbl>
                <w:tr>
                  <w:tc>
                    <w:p>
                      <w:t>${Field}</w:t>
                    </w:p>
                  </w:tc>
                </w:tr>
              </w:tbl>
              <w:p />
            </w:tc>
          </w:tr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $section = $phpWord->getSection(0);
        $table = $section->getElement(0);
        $rows = $table->getRows();
        $cells = $rows[0]->getCells();
        $nestedTable = $cells[0]->getElement(0);
        self::assertInstanceOf('PhpOffice\PhpWord\Element\Table', $nestedTable);
    }

    /**
     * Test reading Drawing.
     */
    public function testReadDrawing(): void
    {
        $documentXml = '<w:p>
            <w:r>
                <w:drawing xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing">
                    <wp:inline distT="0" distB="0" distL="0" distR="0">
                        <wp:extent cx="5727700" cy="6621145"/>
                        <wp:docPr id="1" name="Picture 1"/>
                        <a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
                            <a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">
                                <pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
                                    <pic:nvPicPr>
                                        <pic:cNvPr id="1" name="file_name.jpg"/>
                                        <pic:cNvPicPr/>
                                    </pic:nvPicPr>
                                    <pic:blipFill>
                                        <a:blip r:embed="rId4" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
                                        </a:blip>
                                    </pic:blipFill>
                                </pic:pic>
                            </a:graphicData>
                        </a:graphic>
                    </wp:inline>
                </w:drawing>
            </w:r>
        </w:p>';

        $phpWord = $this->getDocumentFromString(['document' => $documentXml]);

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf('PhpOffice\PhpWord\Element\TextRun', $elements[0]);
    }
}
