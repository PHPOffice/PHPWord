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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Reader\Word2007;

use PhpOffice\PhpWord\AbstractTestReader;

/**
 * Test class for PhpOffice\PhpWord\Reader\Word2007 subnamespace
 */
class PartTest extends AbstractTestReader
{
    /**
     * Test reading Footnotes
     */
    public function testReadFootnote()
    {
        $documentXml = '<w:p>
                <w:r>
                    <w:t>This is a test</w:t>
                </w:r>
                <w:r>
                    <w:rPr>
                        <w:rStyle w:val="FootnoteReference"/>
                    </w:rPr>
                    <w:footnoteReference w:id="1"/>
                </w:r>
            </w:p>
            <w:p>
                <w:r>
                    <w:t>And another one</w:t>
                </w:r>
                <w:r>
                    <w:rPr>
                        <w:rStyle w:val="EndnoteReference"/>
                    </w:rPr>
                    <w:endnoteReference w:id="2"/>
                </w:r>
            </w:p>';

        $footnotesXml = '<w:footnote w:type="separator" w:id="-1">
                <w:p>
                    <w:r>
                        <w:separator/>
                    </w:r>
                </w:p>
            </w:footnote>
            <w:footnote w:id="1">
                <w:p>
                    <w:pPr>
                        <w:pStyle w:val="FootnoteText"/>
                    </w:pPr>
                    <w:r>
                        <w:rPr>
                            <w:rStyle w:val="FootnoteReference"/>
                        </w:rPr>
                        <w:footnoteRef/>
                    </w:r>
                    <w:r>
                        <w:rPr>
                            <w:lang w:val="nl-NL"/>
                        </w:rPr>
                        <w:t>footnote text</w:t>
                    </w:r>
                </w:p>
            </w:footnote>';

        $endnotesXml = '<w:endnote w:type="separator" w:id="-1">
                <w:p>
                    <w:r>
                        <w:separator/>
                    </w:r>
                </w:p>
            </w:endnote>
            <w:endnote w:type="continuationNotice" w:id="1">
                <w:p>
                    <w:r>
                        <w:separator/>
                    </w:r>
                </w:p>
            </w:endnote>
            <w:endnote w:id="2">
                <w:p>
                    <w:pPr>
                        <w:pStyle w:val="EndnoteText"/>
                    </w:pPr>
                    <w:r>
                        <w:rPr>
                            <w:rStyle w:val="EndnoteReference"/>
                        </w:rPr>
                        <w:endnoteRef/>
                    </w:r>
                    <w:r>
                        <w:rPr>
                            <w:lang w:val="nl-NL"/>
                        </w:rPr>
                        <w:t>This is an endnote</w:t>
                    </w:r>
                </w:p>
            </w:endnote>';

        $phpWord = $this->getDocumentFromString(array('document' => $documentXml, 'footnotes' => $footnotesXml, 'endnotes' => $endnotesXml));

        $elements = $phpWord->getSection(0)->getElements();
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\TextRun', $elements[0]);
        /** @var \PhpOffice\PhpWord\Element\TextRun $textRun */
        $textRun = $elements[0];

        //test the text in the first paragraph
        /** @var \PhpOffice\PhpWord\Element\Text $text */
        $text = $elements[0]->getElement(0);
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Text', $text);
        $this->assertEquals('This is a test', $text->getText());

        //test the presence of the footnote in the document.xml
        /** @var \PhpOffice\PhpWord\Element\Footnote $footnote */
        $documentFootnote = $textRun->getElement(1);
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Footnote', $documentFootnote);
        $this->assertEquals(1, $documentFootnote->getRelationId());

        //test the presence of the footnote in the footnote.xml
        /** @var \PhpOffice\PhpWord\Element\Footnote $footnote */
        $footnote = $phpWord->getFootnotes()->getItem(1);
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Footnote', $footnote);
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Text', $footnote->getElement(0));
        $this->assertEquals('footnote text', $footnote->getElement(0)->getText());
        $this->assertEquals(1, $footnote->getRelationId());

        //test the text in the second paragraph
        /** @var \PhpOffice\PhpWord\Element\Text $text */
        $text = $elements[1]->getElement(0);
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Text', $text);
        $this->assertEquals('And another one', $text->getText());

        //test the presence of the endnote in the document.xml
        /** @var \PhpOffice\PhpWord\Element\Endnote $endnote */
        $documentEndnote = $elements[1]->getElement(1);
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Endnote', $documentEndnote);
        $this->assertEquals(2, $documentEndnote->getRelationId());

        //test the presence of the endnote in the endnote.xml
        /** @var \PhpOffice\PhpWord\Element\Endnote $endnote */
        $endnote = $phpWord->getEndnotes()->getItem(1);
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Endnote', $endnote);
        $this->assertEquals(2, $endnote->getRelationId());
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Text', $endnote->getElement(0));
        $this->assertEquals('This is an endnote', $endnote->getElement(0)->getText());
    }

    public function testReadHeadingWithOverriddenStyle()
    {
        $documentXml = '<w:p>
            <w:pPr>
                <w:pStyle w:val="Heading1"/>
            </w:pPr>
            <w:r>
                <w:t>This is a bold </w:t>
            </w:r>
            <w:r w:rsidRPr="00377798">
                <w:rPr>
                    <w:b w:val="0"/>
                </w:rPr>
                <w:t>heading</w:t>
            </w:r>
            <w:r>
                <w:t xml:space="preserve"> but with parts not in bold</w:t>
            </w:r>
            </w:p>';

        $stylesXml = '<w:style w:type="paragraph" w:default="1" w:styleId="Normal">
                <w:name w:val="Normal"/>
                <w:qFormat/>
            </w:style>
            <w:style w:type="paragraph" w:styleId="Heading1">
                <w:name w:val="heading 1"/>
                <w:basedOn w:val="Normal"/>
                <w:next w:val="Normal"/>
                <w:link w:val="Heading1Char"/>
                <w:uiPriority w:val="9"/>
                <w:qFormat/>
                <w:rsid w:val="00377798"/>
                <w:pPr>
                    <w:keepNext/>
                    <w:keepLines/>
                    <w:spacing w:before="240"/>
                    <w:outlineLvl w:val="0"/>
                </w:pPr>
                <w:rPr>
                    <w:rFonts w:asciiTheme="majorHAnsi" w:eastAsiaTheme="majorEastAsia" w:hAnsiTheme="majorHAnsi" w:cstheme="majorBidi"/>
                    <w:b/>
                    <w:color w:val="2F5496" w:themeColor="accent1" w:themeShade="BF"/>
                    <w:sz w:val="32"/>
                    <w:szCs w:val="32"/>
                </w:rPr>
            </w:style>';

        $phpWord = $this->getDocumentFromString(array('document' => $documentXml, 'styles' => $stylesXml));

        $elements = $phpWord->getSection(0)->getElements();
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Title', $elements[0]);
        /** @var \PhpOffice\PhpWord\Element\Title $title */
        $title = $elements[0];
        $this->assertEquals('Heading1', $title->getStyle());

        /** @var \PhpOffice\PhpWord\Element\Text $text */
        $text = $title->getText()->getElement(0);
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Text', $text);
        $this->assertEquals('This is a bold ', $text->getText());

        /** @var \PhpOffice\PhpWord\Element\Text $text */
        $text = $title->getText()->getElement(1);
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Text', $text);
        $this->assertEquals('heading', $text->getText());
        $this->assertFalse($text->getFontStyle()->isBold());

        /** @var \PhpOffice\PhpWord\Element\Text $text */
        $text = $title->getText()->getElement(2);
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Text', $text);
        $this->assertEquals(' but with parts not in bold', $text->getText());
    }
}
