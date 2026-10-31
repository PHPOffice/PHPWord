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

declare(strict_types=1);

namespace PhpOffice\PhpWordTests\Writer\Word2007\Element;

use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Element subnamespace.
 */
class TOCTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed after each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testWriteTitlePageNumber(): void
    {
        $expectedPageNum = mt_rand(1, 1000);

        $phpWord = new PhpWord();

        $section = $phpWord->addSection();
        $section->addTOC();
        $section->addTitle('TestTitle 1', 1, $expectedPageNum);

        $doc = TestHelperDOCX::getDocument($phpWord);

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:hyperlink/w:r[1]/w:t'));
        self::assertEquals('TestTitle 1', $doc->getElement('/w:document/w:body/w:p[1]/w:hyperlink/w:r[1]/w:t')->textContent);
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:hyperlink/w:r[5]/w:fldChar'));
        self::assertEquals('separate', $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:hyperlink/w:r[5]/w:fldChar', 'w:fldCharType'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:hyperlink/w:r[6]/w:t'));
        self::assertEquals($expectedPageNum, $doc->getElement('/w:document/w:body/w:p[1]/w:hyperlink/w:r[6]/w:t')->textContent);
    }

    public function testWriteTitleWithoutpageNumber(): void
    {
        $phpWord = new PhpWord();

        $section = $phpWord->addSection();
        $section->addTOC();

        $staticHtml = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. 
            Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. 
            Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi.</p>
            <p>Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat. 
            Duis semper. Duis arcu massa, scelerisque vitae, consequat in, pretium a, enim.</p>';

        //more than one title and random text for create more than one page
        for ($i = 1; $i <= 10; ++$i) {
            $section->addTitle('Title ' . $i, 1);
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $staticHtml, false, false);
            $section->addPageBreak();
        }

        $doc = TestHelperDOCX::getDocument($phpWord);

        for ($i = 1; $i <= 10; ++$i) {
            self::assertTrue($doc->elementExists('/w:document/w:body/w:p[' . $i . ']/w:hyperlink/w:r[1]/w:t'));
            self::assertEquals('Title ' . $i, $doc->getElement('/w:document/w:body/w:p[' . $i . ']/w:hyperlink/w:r[1]/w:t')->textContent);
            self::assertTrue($doc->elementExists('/w:document/w:body/w:p[' . $i . ']/w:hyperlink/w:r[4]/w:instrText'));
            self::assertEquals('preserve', $doc->getElementAttribute('/w:document/w:body/w:p[' . $i . ']/w:hyperlink/w:r[4]/w:instrText', 'xml:space'));
            self::assertEquals('PAGEREF _Toc' . ($i - 1) . ' \\h', $doc->getElement('/w:document/w:body/w:p[' . $i . ']/w:hyperlink/w:r[4]/w:instrText')->nodeValue);
        }
    }

    public function testPageReferenceAndLinkPointAtTheBookmarkOfTheTitle(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addTOC();
        $section->addTitle('Plain', 1);
        $textRun = new TextRun();
        $textRun->addText('Run ');
        $textRun->addText('title', ['italic' => true]);
        $section->addTitle($textRun, 2);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $bookmarks = [];
        foreach ($doc->getNodeList('//w:bookmarkStart/@w:name') as $name) {
            $bookmarks[] = $name->nodeValue;
        }
        foreach (['Plain', 'Run title'] as $i => $text) {
            $link = '/w:document/w:body/w:p[' . ($i + 1) . ']/w:hyperlink';
            self::assertEquals($text, $doc->getElement($link . '/w:r[1]/w:t')->textContent);
            $anchor = $doc->getElementAttribute($link, 'w:anchor');
            self::assertContains($anchor, $bookmarks);
            self::assertEquals('PAGEREF ' . $anchor . ' \\h', $doc->getElement($link . '/w:r[4]/w:instrText')->nodeValue);
        }
    }

    /**
     * The properties of an entry of a table of contents are written in the order the
     * schema names them.
     *
     * `CT_PPr` is a sequence and not a set, so a reader that walks it stops at the first
     * child it did not expect. The order asserted below is the one in ECMA-376, in
     * `CT_PPrBase`.
     */
    public function testParagraphPropertiesAreWrittenInSchemaOrder(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addFontStyle('fStyle', ['color' => '333333']);

        $section = $phpWord->addSection();
        $section->addTOC('fStyle');
        $section->addTitle('Title 1', 2);

        $doc = TestHelperDOCX::getDocument($phpWord);
        $pPr = $doc->getElement('/w:document/w:body/w:p[1]/w:pPr', 'word/document.xml');
        self::assertNotNull($pPr);

        $written = [];
        foreach ($pPr->childNodes as $child) {
            if (XML_ELEMENT_NODE === $child->nodeType) {
                $written[] = $child->localName;
            }
        }

        self::assertSame(['tabs', 'ind', 'rPr'], $written);
    }

    /**
     * The properties of the style of an entry of a table of contents are written among
     * the properties of the entry, in the order the schema names them.
     *
     * `CT_PPr` is a sequence and not a set, so a reader that walks it stops at the first
     * child it did not expect. The order asserted below is the one in ECMA-376, in
     * `CT_PPrBase`.
     */
    public function testThePropertiesOfTheStyleAreWrittenInSchemaOrder(): void
    {
        $fontStyle = new Font('text', ['alignment' => 'left', 'spaceAfter' => 120]);

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addTOC($fontStyle);
        $section->addTitle('Title 1', 2);

        $doc = TestHelperDOCX::getDocument($phpWord);
        $pPr = $doc->getElement('/w:document/w:body/w:p[1]/w:pPr', 'word/document.xml');
        self::assertNotNull($pPr);

        $written = [];
        foreach ($pPr->childNodes as $child) {
            if (XML_ELEMENT_NODE === $child->nodeType) {
                $written[] = $child->localName;
            }
        }

        self::assertSame(['tabs', 'spacing', 'ind', 'jc'], $written);
    }

    /**
     * An entry of a table of contents keeps its tab stop and its indent when the
     * font style names a paragraph style.
     *
     * A paragraph style that is named rather than carried brings no properties of
     * its own, so the entry writes the tab stop and the indent itself.
     */
    public function testTheTabStopAndTheIndentAreWrittenForANamedParagraphStyle(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addParagraphStyle('TocParagraph', ['alignment' => 'left']);

        $section = $phpWord->addSection();
        $section->addTOC(new Font('text', 'TocParagraph'));
        $section->addTitle('Title 1', 2);

        $doc = TestHelperDOCX::getDocument($phpWord);
        $pPr = $doc->getElement('/w:document/w:body/w:p[1]/w:pPr', 'word/document.xml');
        self::assertNotNull($pPr);

        $written = [];
        foreach ($pPr->childNodes as $child) {
            if (XML_ELEMENT_NODE === $child->nodeType) {
                $written[] = $child->localName;
            }
        }

        self::assertSame(['tabs', 'ind'], $written);
        self::assertSame('right', $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:pPr/w:tabs/w:tab', 'w:val'));
        self::assertSame('9062', $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:pPr/w:tabs/w:tab', 'w:pos'));
        self::assertSame('200', $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:pPr/w:ind', 'w:left'));
    }
}
