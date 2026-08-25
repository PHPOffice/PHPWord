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

namespace PhpOffice\PhpWordTests\Writer\ODText;

use DateTime;
use PhpOffice\PhpWord\ComplexType\RubyProperties;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\TrackChange;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\ODText\Element subnamespace.
 */
class ElementTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed after each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test unmatched elements.
     */
    public function testUnmatchedElements(): void
    {
        $elements = ['Image', 'Line', 'Link', 'Table', 'Text', 'Title', 'Field'];
        foreach ($elements as $element) {
            $objectClass = 'PhpOffice\\PhpWord\\Writer\\ODText\\Element\\' . $element;
            $xmlWriter = new XMLWriter();
            $newElement = new \PhpOffice\PhpWord\Element\PageBreak();
            $object = new $objectClass($xmlWriter, $newElement);
            $object->write();

            self::assertEquals('', $xmlWriter->getData());
        }
    }

    /**
     * Test drawing elements are dispatched by the ODText container writer.
     */
    public function testDrawingElementsAreDispatched(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addShape('rect', ['frame' => ['width' => 100, 'height' => 50]]);
        $section->addTextBox(['width' => 100, 'height' => 50]);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $path = '/office:document-content/office:body/office:text/text:section';
        self::assertTrue($doc->elementExists($path . '/draw:rect'));
        self::assertTrue($doc->elementExists($path . '/draw:frame/draw:text-box'));
    }

    /**
     * Test bookmark element.
     */
    public function testBookmarkElement(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addBookmark('test_bookmark');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $path = '/office:document-content/office:body/office:text/text:section/text:p/text:bookmark';
        self::assertTrue($doc->elementExists($path));
        self::assertEquals('test_bookmark', $doc->getElementAttribute($path, 'text:name'));
    }

    /**
     * Test multiple bookmarks and bookmarks in text runs.
     */
    public function testMultipleBookmarksAndNestedBookmarkElement(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addBookmark('first_bookmark');
        $section->addBookmark('bookmark_with_&');
        $textRun = $section->addTextRun();
        $textRun->addBookmark('nested_bookmark');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $path = '/office:document-content/office:body/office:text/text:section//text:bookmark';
        self::assertTrue($doc->elementExists("($path)[3]"));
        self::assertEquals('first_bookmark', $doc->getElementAttribute("($path)[1]", 'text:name'));
        self::assertEquals('bookmark_with_&', $doc->getElementAttribute("($path)[2]", 'text:name'));
        self::assertEquals('nested_bookmark', $doc->getElementAttribute("($path)[3]", 'text:name'));
    }

    /**
     * Test footnote element.
     */
    public function testFootnoteElement(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $footnote = $section->addFootnote();
        $footnote->addText('Footnote text.');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $path = '/office:document-content/office:body/office:text/text:section/text:p/text:note';
        self::assertTrue($doc->elementExists($path));
        self::assertEquals('footnote', $doc->getElementAttribute($path, 'text:note-class'));
        self::assertEquals('ftn1', $doc->getElementAttribute($path, 'text:id'));
        self::assertEquals('1', $doc->getElement($path . '/text:note-citation')->textContent);
        self::assertEquals('Footnote text.', $doc->getElement($path . '/text:note-body/text:p')->textContent);
    }

    /**
     * Test endnotes, multiple notes, and rich note content.
     */
    public function testEndnoteAndMultipleNotes(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addFootnote()->addText('First footnote.');
        $textRun = $section->addTextRun();
        $footnote = $textRun->addFootnote();
        $footnote->addText('Rich footnote with ');
        $footnote->addLink('https://example.com', 'a link');
        $endnote = $textRun->addEndnote();
        $endnote->addText('Endnote text.');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $path = '/office:document-content/office:body/office:text/text:section//text:note';
        self::assertTrue($doc->elementExists("($path)[3]"));
        self::assertEquals('footnote', $doc->getElementAttribute("($path)[1]", 'text:note-class'));
        self::assertEquals('ftn1', $doc->getElementAttribute("($path)[1]", 'text:id'));
        self::assertEquals('1', $doc->getElement("($path)[1]/text:note-citation")->textContent);
        self::assertEquals('footnote', $doc->getElementAttribute("($path)[2]", 'text:note-class'));
        self::assertEquals('ftn2', $doc->getElementAttribute("($path)[2]", 'text:id'));
        self::assertEquals('Rich footnote with', $doc->getElement("($path)[2]/text:note-body/text:p[1]")->textContent);
        self::assertEquals('a link', $doc->getElement("($path)[2]/text:note-body/text:p[2]")->textContent);
        self::assertEquals('endnote', $doc->getElementAttribute("($path)[3]", 'text:note-class'));
        self::assertEquals('end1', $doc->getElementAttribute("($path)[3]", 'text:id'));
        self::assertEquals('1', $doc->getElement("($path)[3]/text:note-citation")->textContent);
        self::assertEquals('Endnote text.', $doc->getElement("($path)[3]/text:note-body/text:p")->textContent);
    }

    // ODT Line Element not yet implemented
    // ODT Table with style name not yet implemented (Word test defective)
    // ODT Shape Elements not yet implemented
    // ODT Chart Elements not yet implemented
    // ODT List not yet implemented
    // ODT Macro Button not yet implemented
    // ODT Form Field not yet implemented
    // ODT SDT not yet implemented
    // ODT Track Changes implemented, possibly not correctly
    // ODT List Item not yet implemented

    /**
     * Test link element.
     */
    public function testLinkElement(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $extlink = 'https://github.com/PHPOffice/PHPWord';
        $section->addLink($extlink);
        $intlink = 'internal_link';
        $section->addLink($intlink, null, null, null, true);
        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $p2t = '/office:document-content/office:body/office:text/text:section';
        $element = "$p2t/text:p[2]/text:a";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals($extlink, $doc->getElementAttribute($element, 'xlink:href'));

        $element = "$p2t/text:p[3]/text:a";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals("#$intlink", $doc->getElementAttribute($element, 'xlink:href'));
    }

    /**
     * Test comments as native ODF annotations.
     */
    public function testCommentElement(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $comment = new \PhpOffice\PhpWord\Element\Comment('Ada', new DateTime('2024-01-02 03:04:05 UTC'), 'AD');
        $comment->addText('Review this', ['bold' => true]);
        $phpWord->addComment($comment);
        $text = $section->addText('This text');
        $text->setCommentRangeStart($comment);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $path = '/office:document-content/office:body/office:text/text:section/text:p[2]';
        $annotation = $path . '/office:annotation';

        self::assertTrue($doc->elementExists($annotation));
        self::assertSame($comment->getElementId(), $doc->getElementAttribute($annotation, 'office:name'));
        self::assertSame('Ada', $doc->getElement($annotation . '/dc:creator')->nodeValue);
        self::assertSame('2024-01-02T03:04:05Z', $doc->getElement($annotation . '/dc:date')->nodeValue);
        self::assertSame('Review this', $doc->getElement($annotation . '/text:p/text:span')->nodeValue);
        self::assertTrue($doc->elementExists($path . '/office:annotation-end'));
        self::assertSame($comment->getElementId(), $doc->getElementAttribute($path . '/office:annotation-end', 'office:name'));
    }

    /**
     * Test explicit comment ranges and multiple comments on one element.
     */
    public function testCommentRangesAndMultipleComments(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $comment = new \PhpOffice\PhpWord\Element\Comment('Ada');
        $comment->addText('Across two runs');
        $secondComment = new \PhpOffice\PhpWord\Element\Comment('Grace');
        $secondComment->addText('Only the first run');
        $phpWord->addComment($comment);
        $phpWord->addComment($secondComment);
        $run = $section->addTextRun();
        $first = $run->addText('First');
        $last = $run->addText(' last');
        $first->setCommentRangeStart($comment);
        $first->setCommentRangeStart($secondComment);
        $last->setCommentRangeEnd($comment);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $path = '/office:document-content/office:body/office:text/text:section/text:p[2]';
        $annotations = $path . '/office:annotation';
        self::assertTrue($doc->elementExists($annotations . '[1]'));
        self::assertTrue($doc->elementExists($annotations . '[2]'));
        self::assertSame($comment->getElementId(), $doc->getElementAttribute($annotations . '[1]', 'office:name'));
        self::assertSame($secondComment->getElementId(), $doc->getElementAttribute($annotations . '[2]', 'office:name'));
        self::assertSame($secondComment->getElementId(), $doc->getElementAttribute($path . '/office:annotation-end[1]', 'office:name'));
        self::assertSame($comment->getElementId(), $doc->getElementAttribute($path . '/office:annotation-end[2]', 'office:name'));
    }

    /**
     * Test comments on image elements.
     */
    public function testCommentOnImageElement(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $comment = new \PhpOffice\PhpWord\Element\Comment('Ada');
        $phpWord->addComment($comment);
        $image = $section->addImage(__DIR__ . '/../../_files/images/earth.jpg');
        $image->setCommentRangeStart($comment);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $path = '/office:document-content/office:body/office:text/text:section/text:p[2]';
        self::assertSame($comment->getElementId(), $doc->getElementAttribute($path . '/office:annotation', 'office:name'));
        self::assertSame($comment->getElementId(), $doc->getElementAttribute($path . '/office:annotation-end', 'office:name'));
    }

    /**
     * Test comments on link elements.
     */
    public function testCommentOnLinkElement(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $comment = new \PhpOffice\PhpWord\Element\Comment('Ada');
        $phpWord->addComment($comment);
        $link = $section->addLink('https://example.com', 'Example');
        $link->setCommentRangeStart($comment);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $path = '/office:document-content/office:body/office:text/text:section/text:p[2]';
        self::assertSame($comment->getElementId(), $doc->getElementAttribute($path . '/office:annotation', 'office:name'));
        self::assertSame($comment->getElementId(), $doc->getElementAttribute($path . '/office:annotation-end', 'office:name'));
    }

    /**
     * Test comments on ruby elements.
     */
    public function testCommentOnRubyElement(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $comment = new \PhpOffice\PhpWord\Element\Comment('Ada');
        $phpWord->addComment($comment);
        $baseTextRun = new TextRun(null);
        $baseTextRun->addText('私');
        $rubyTextRun = new TextRun(null);
        $rubyTextRun->addText('わたし');
        $ruby = $section->addRuby($baseTextRun, $rubyTextRun, new RubyProperties());
        $ruby->setCommentRangeStart($comment);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $path = '/office:document-content/office:body/office:text/text:section/text:p[2]';
        self::assertSame($comment->getElementId(), $doc->getElementAttribute($path . '/office:annotation', 'office:name'));
        self::assertSame($comment->getElementId(), $doc->getElementAttribute($path . '/office:annotation-end', 'office:name'));
    }

    /**
     * Test comments on title elements.
     */
    public function testCommentOnTitleElement(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addTitleStyle(1, []);
        $section = $phpWord->addSection();
        $comment = new \PhpOffice\PhpWord\Element\Comment('Ada');
        $phpWord->addComment($comment);
        $title = $section->addTitle('Commented title', 1);
        $title->setCommentRangeStart($comment);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $path = '/office:document-content/office:body/office:text/text:section/text:h[1]/text:span';
        self::assertSame($comment->getElementId(), $doc->getElementAttribute($path . '/office:annotation', 'office:name'));
        self::assertSame($comment->getElementId(), $doc->getElementAttribute($path . '/office:annotation-end', 'office:name'));
    }

    /**
     * Basic test for table element.
     */
    public function testTableElements(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $table = $section->addTable(['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER]);
        $table->addRow(900);
        $table->addCell(2000)->addText('Row 1');
        $table->addCell(2000)->addText('Row 2');
        $table->addCell(2000)->addText('Row 3');
        $table->addCell(2000)->addText('Row 4');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $p2s = '/office:document-content/office:automatic-styles';
        $tableStyleNum = 1;
        /** @var null|string $tableStyleName */
        $tableStyleName = null;
        $element = '';
        while ($tableStyleName === null) {
            $element = "$p2s/style:style[$tableStyleNum]";
            if (!$doc->elementExists($element)) {
                break;
            }
            if ($doc->getElementAttribute($element, 'style:family') === 'table') {
                $tableStyleName = $doc->getElementAttribute($element, 'style:name');

                break;
            }
            ++$tableStyleNum;
        }
        self::assertNotNull($tableStyleName);
        $element = "$element/style:table-properties";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals(\PhpOffice\PhpWord\SimpleType\JcTable::CENTER, $doc->getElementAttribute($element, 'table:align'));
        $p2t = '/office:document-content/office:body/office:text/text:section';
        $tableRootElement = "$p2t/table:table";
        self::assertTrue($doc->elementExists($tableRootElement));
        self::assertEquals($tableStyleName, $doc->getElementAttribute($tableRootElement, 'table:style-name'));
        self::assertTrue($doc->elementExists($tableRootElement . '/table:table-column[4]'));
    }

    /**
     * Test Title and Headings.
     */
    public function testTitleAndHeading(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addTitleStyle(0, ['size' => 14, 'italic' => true]);
        $phpWord->addTitleStyle(1, ['size' => 20, 'color' => '333333', 'bold' => true]);

        $section = $phpWord->addSection();
        $section->addTitle('This is a title', 0);
        $section->addTitle('Heading 1', 1);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $p2t = '/office:document-content/office:body/office:text/text:section';
        $element = "$p2t/text:h[1]";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('HE0', $doc->getElementAttribute($element, 'text:style-name'));
        self::assertEquals('0', $doc->getElementAttribute($element, 'text:outline-level'));
        $span = "$element/text:span";
        self::assertTrue($doc->elementExists($span));
        self::assertEquals('This is a title', $doc->getElement($span)->textContent);
        self::assertEquals('Title', $doc->getElementAttribute($span, 'text:style-name'));

        $element = "$p2t/text:h[2]";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('HD1', $doc->getElementAttribute($element, 'text:style-name'));
        self::assertEquals('1', $doc->getElementAttribute($element, 'text:outline-level'));
        $span = "$element/text:span";
        self::assertTrue($doc->elementExists($span));
        self::assertEquals('Heading 1', $doc->getElement($span)->textContent);
        self::assertEquals('Heading_1', $doc->getElementAttribute($span, 'text:style-name'));

        $doc->setDefaultFile('styles.xml');
        $element = '/office:document-styles/office:styles/style:style[1]';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('Title', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:text-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('14pt', $doc->getElementAttribute($element, 'fo:font-size'));
        self::assertEquals('italic', $doc->getElementAttribute($element, 'fo:font-style'));
        self::assertEquals('', $doc->getElementAttribute($element, 'fo:font-weight'));
        self::assertEquals('', $doc->getElementAttribute($element, 'fo:color'));

        $element = '/office:document-styles/office:styles/style:style[2]';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('Heading_1', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:text-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('20pt', $doc->getElementAttribute($element, 'fo:font-size'));
        self::assertEquals('', $doc->getElementAttribute($element, 'fo:font-style'));
        self::assertEquals('bold', $doc->getElementAttribute($element, 'fo:font-weight'));
        self::assertEquals('#333333', $doc->getElementAttribute($element, 'fo:color'));
    }

    /**
     * Test title specified as text run rather than text.
     */
    public function testTextRunTitle(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addTitleStyle(1, ['name' => 'Times New Roman', 'size' => 18, 'bold' => true]);
        $section = $phpWord->addSection();
        $section->addTitle('Text Title', 1);
        $section->addText('Text following Text Title');
        $textRun = new TextRun();
        $textRun->addText('Text Run');
        $textRun->addText(' Title');
        $section->addTitle($textRun, 1);
        $section->addText('Text following Text Run Title');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $p2t = '/office:document-content/office:body/office:text/text:section';

        $element = "$p2t/text:h[1]";
        self::assertEquals('HE1', $doc->getElementAttribute($element, 'text:style-name'));
        self::assertEquals('1', $doc->getElementAttribute($element, 'text:outline-level'));
        $span = "$element/text:span";
        self::assertEquals('Text Title', $doc->getElement($span)->textContent);
        self::assertEquals('Heading_1', $doc->getElementAttribute($span, 'text:style-name'));
        $element = "$p2t/text:p[2]/text:span";
        self::assertEquals('Text following Text Title', $doc->getElement($element)->nodeValue);

        $element = "$p2t/text:h[2]";
        self::assertEquals('HD1', $doc->getElementAttribute($element, 'text:style-name'));
        self::assertEquals('1', $doc->getElementAttribute($element, 'text:outline-level'));
        $span = "$element/text:span";
        self::assertEquals('Text Run', $doc->getElement("$span/text:span[1]")->textContent);
        self::assertTrue($doc->elementExists("$span/text:span[2]/text:s"));
        self::assertEquals('Title', $doc->getElement("$span/text:span[2]")->textContent);
        self::assertEquals('Heading_1', $doc->getElementAttribute($span, 'text:style-name'));
        $element = "$p2t/text:p[3]/text:span";
        self::assertEquals('Text following Text Run Title', $doc->getElement($element)->nodeValue);
    }

    /**
     * Test correct writing of text with ampersand in it.
     */
    public function testTextWithAmpersand(): void
    {
        $esc = \PhpOffice\PhpWord\Settings::isOutputEscapingEnabled();
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $txt = 'this text contains an & (ampersand)';
        $section->addText($txt);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled($esc);
        $p2t = '/office:document-content/office:body/office:text/text:section';
        $element = "$p2t/text:p[2]";
        self::assertTrue($doc->elementExists($element));
        $span = "$element/text:span";
        self::assertTrue($doc->elementExists($span));
        self::assertEquals($txt, $doc->getElement($span)->nodeValue);
    }

    /**
     * Test PageBreak.
     */
    public function testPageBreak(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('test');
        $section->addPageBreak();

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $element = '/office:document-content/office:body/office:text/text:section/text:p[3]';
        self::assertTrue($doc->elementExists($element, 'content.xml'));
        self::assertEquals('PB', $doc->getElementAttribute($element, 'text:style-name', 'content.xml'));
    }

    /**
     * Test tracked changes.
     */
    public function testTrackedChanges(): void
    {
        $phpWord = new PhpWord();

        // New portrait section
        $section = $phpWord->addSection();
        $textRun = $section->addTextRun();

        $text = $textRun->addText('Hello World! Time to ');

        $text = $textRun->addText('wake ', ['bold' => true]);
        $text->setChangeInfo(TrackChange::INSERTED, 'Fred', time() - 1800);

        $text = $textRun->addText('up');
        $text->setTrackChange(new TrackChange(TrackChange::INSERTED, 'Fred'));

        $text = $textRun->addText('go to sleep');
        $text->setChangeInfo(TrackChange::DELETED, 'Barney', new DateTime('@' . (time() - 3600)));

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $tcs = '/office:document-content/office:body/office:text/text:tracked-changes';
        $tc1 = "$tcs/text:changed-region[1]";
        $tc1id = $doc->getElementAttribute($tc1, 'text:id');
        $element = "$tc1/text:insertion";
        self::assertTrue($doc->elementExists($element));
        $element .= '/office:change-info';
        self::AssertEquals('Fred', $doc->getElement("$element/dc:creator")->nodeValue);
        self::assertTrue($doc->elementExists("$element/dc:date"));

        $tc2 = "$tcs/text:changed-region[2]";
        $tc2id = $doc->getElementAttribute($tc2, 'text:id');
        $element = "$tc2/text:insertion";
        self::assertTrue($doc->elementExists($element));
        $element .= '/office:change-info';
        self::AssertEquals('Fred', $doc->getElement("$element/dc:creator")->nodeValue);
        //self::assertTrue($doc->elementExists("$element/dc:date"));

        $tc3 = "$tcs/text:changed-region[3]";
        $tc3id = $doc->getElementAttribute($tc3, 'text:id');
        $element = "$tc3/text:deletion";
        self::assertTrue($doc->elementExists($element));
        $element .= '/office:change-info';
        self::AssertEquals('Barney', $doc->getElement("$element/dc:creator")->nodeValue);
        self::assertTrue($doc->elementExists("$element/dc:date"));

        $p2t = '/office:document-content/office:body/office:text/text:section/text:p[2]';
        $element = "$p2t/text:span[2]/text:change-start";
        self::AssertEquals($tc1id, $doc->getElementAttribute($element, 'text:change-id'));
        $element = "$p2t/text:span[3]/text:change-start";
        self::AssertEquals($tc2id, $doc->getElementAttribute($element, 'text:change-id'));
        $element = "$p2t/text:change";
        self::AssertEquals($tc3id, $doc->getElementAttribute($element, 'text:change-id'));
    }

    /**
     * Test ruby output.
     * Note that this test will need to be updated when ODT Ruby output supports
     * ODT's native ruby functionality.
     */
    public function testRubyText(): void
    {
        $esc = \PhpOffice\PhpWord\Settings::isOutputEscapingEnabled();
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $properties = new RubyProperties();
        $properties->setAlignment(RubyProperties::ALIGNMENT_RIGHT_VERTICAL);
        $properties->setFontFaceSize(10);
        $properties->setFontPointsAboveBaseText(4);
        $properties->setFontSizeForBaseText(18);
        $properties->setLanguageId('ja-JP');

        $baseTextRun = new TextRun(null);
        $baseTextRun->addText('私');
        $rubyTextRun = new TextRun(null);
        $rubyTextRun->addText('わたし');
        $section->addRuby($baseTextRun, $rubyTextRun, $properties);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled($esc);
        $p2t = '/office:document-content/office:body/office:text/text:section';
        $element = "$p2t/text:p[2]";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('私 (わたし)', $doc->getElement($element)->nodeValue);
    }
}
