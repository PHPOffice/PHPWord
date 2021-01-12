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

namespace PhpOffice\PhpWord\Writer\ODText;

use PhpOffice\PhpWord\Element\TrackChange;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\ODText\Element subnamespace
 */
class ElementTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed after each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test unmatched elements
     */
    public function testUnmatchedElements()
    {
        $elements = array('Image', 'Link', 'Table', 'Text', 'Title', 'Field');
        foreach ($elements as $element) {
            $objectClass = 'PhpOffice\\PhpWord\\Writer\\ODText\\Element\\' . $element;
            $xmlWriter = new XMLWriter();
            $newElement = new \PhpOffice\PhpWord\Element\PageBreak();
            $object = new $objectClass($xmlWriter, $newElement);
            $object->write();

            self::assertEquals('', $xmlWriter->getData());
        }
    }

    // ODT Line Element not yet implemented
    // ODT Bookmark not yet implemented
    // ODT Table with style name not yet implemented (Word test defective)
    // ODT Shape Elements not yet implemented
    // ODT Chart Elements not yet implemented
    // ODT adding Field to Section not yet implemented
    // ODT List not yet implemented
    // ODT Macro Button not yet implemented
    // ODT Form Field not yet implemented
    // ODT SDT not yet implemented
    // ODT Comment not yet implemented
    // ODT Track Changes implemented, possibly not correctly
    // ODT List Item not yet implemented

    /**
     * Test link element
     */
    public function testLinkElement()
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
     * Basic test for table element
     */
    public function testTableElements()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $table = $section->addTable(array('alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER));
        $table->addRow(900);
        $table->addCell(2000)->addText('Row 1');
        $table->addCell(2000)->addText('Row 2');
        $table->addCell(2000)->addText('Row 3');
        $table->addCell(2000)->addText('Row 4');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $p2s = '/office:document-content/office:automatic-styles';
        $tableStyleNum = 1;
        $tableStyleName = '';
        while ($tableStyleName === '') {
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
        self::AssertNotEquals('', $tableStyleName);
        $element = "$element/style:table-properties";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals(\PhpOffice\PhpWord\SimpleType\JcTable::CENTER, $doc->getElementAttribute($element, 'table:align'));
        $p2t = '/office:document-content/office:body/office:text/text:section';
        $tableRootElement = "$p2t/table:table";
        self::assertTrue($doc->elementExists($tableRootElement));
        self::assertEquals($tableStyleName, $doc->getElementAttribute($tableRootElement, 'table:style'));
        self::assertTrue($doc->elementExists($tableRootElement . '/table:table-column[4]'));
    }

    /**
     * Test Title and Headings
     */
    public function testTitleAndHeading()
    {
        $phpWord = new PhpWord();
        $phpWord->addTitleStyle(0, array('size' => 14, 'italic' => true));
        $phpWord->addTitleStyle(1, array('size' => 20, 'color' => '333333', 'bold' => true));

        $section = $phpWord->addSection();
        $section->addTitle('This is a title', 0);
        $section->addTitle('Heading 1', 1);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $p2t = '/office:document-content/office:body/office:text/text:section';
        $element = "$p2t/text:h[1]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('HE0', $doc->getElementAttribute($element, 'text:style-name'));
        $this->assertEquals('0', $doc->getElementAttribute($element, 'text:outline-level'));
        $span = "$element/text:span";
        $this->assertTrue($doc->elementExists($span));
        $this->assertEquals('This is a title', $doc->getElement($span)->textContent);
        $this->assertEquals('Title', $doc->getElementAttribute($span, 'text:style-name'));

        $element = "$p2t/text:h[2]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('HD1', $doc->getElementAttribute($element, 'text:style-name'));
        $this->assertEquals('1', $doc->getElementAttribute($element, 'text:outline-level'));
        $span = "$element/text:span";
        $this->assertTrue($doc->elementExists($span));
        $this->assertEquals('Heading 1', $doc->getElement($span)->textContent);
        $this->assertEquals('Heading_1', $doc->getElementAttribute($span, 'text:style-name'));

        $doc->setDefaultFile('styles.xml');
        $element = '/office:document-styles/office:styles/style:style[1]';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('Title', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:text-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('14pt', $doc->getElementAttribute($element, 'fo:font-size'));
        $this->assertEquals('italic', $doc->getElementAttribute($element, 'fo:font-style'));
        $this->assertEquals('', $doc->getElementAttribute($element, 'fo:font-weight'));
        $this->assertEquals('', $doc->getElementAttribute($element, 'fo:color'));

        $element = '/office:document-styles/office:styles/style:style[2]';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('Heading_1', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:text-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('20pt', $doc->getElementAttribute($element, 'fo:font-size'));
        $this->assertEquals('', $doc->getElementAttribute($element, 'fo:font-style'));
        $this->assertEquals('bold', $doc->getElementAttribute($element, 'fo:font-weight'));
        $this->assertEquals('#333333', $doc->getElementAttribute($element, 'fo:color'));
    }

    /**
     * Test title specified as text run rather than text
     */
    public function testTextRunTitle()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->addTitleStyle(1, array('name' => 'Times New Roman', 'size' => 18, 'bold' => true));
        $section = $phpWord->addSection();
        $section->addTitle('Text Title', 1);
        $section->addText('Text following Text Title');
        $textRun = new \PhpOffice\PhpWord\Element\TextRun();
        $textRun->addText('Text Run');
        $textRun->addText(' Title');
        $section->addTitle($textRun, 1);
        $section->addText('Text following Text Run Title');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $p2t = '/office:document-content/office:body/office:text/text:section';

        $element = "$p2t/text:h[1]";
        $this->assertEquals('HE1', $doc->getElementAttribute($element, 'text:style-name'));
        $this->assertEquals('1', $doc->getElementAttribute($element, 'text:outline-level'));
        $span = "$element/text:span";
        $this->assertEquals('Text Title', $doc->getElement($span)->textContent);
        $this->assertEquals('Heading_1', $doc->getElementAttribute($span, 'text:style-name'));
        $element = "$p2t/text:p[2]/text:span";
        $this->assertEquals('Text following Text Title', $doc->getElement($element)->nodeValue);

        $element = "$p2t/text:h[2]";
        $this->assertEquals('HD1', $doc->getElementAttribute($element, 'text:style-name'));
        $this->assertEquals('1', $doc->getElementAttribute($element, 'text:outline-level'));
        $span = "$element/text:span";
        $this->assertEquals('Text Run', $doc->getElement("$span/text:span[1]")->textContent);
        $this->assertTrue($doc->elementExists("$span/text:span[2]/text:s"));
        $this->assertEquals('Title', $doc->getElement("$span/text:span[2]")->textContent);
        $this->assertEquals('Heading_1', $doc->getElementAttribute($span, 'text:style-name'));
        $element = "$p2t/text:p[3]/text:span";
        $this->assertEquals('Text following Text Run Title', $doc->getElement($element)->nodeValue);
    }

    /**
     * Test correct writing of text with ampersand in it
     */
    public function testTextWithAmpersand()
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
        $this->assertTrue($doc->elementExists($element));
        $span = "$element/text:span";
        $this->assertTrue($doc->elementExists($span));
        $this->assertEquals($txt, $doc->getElement($span)->nodeValue);
    }

    /**
     * Test PageBreak
     */
    public function testPageBreak()
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
     * Test tracked changes
     */
    public function testTrackedChanges()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // New portrait section
        $section = $phpWord->addSection();
        $textRun = $section->addTextRun();

        $text = $textRun->addText('Hello World! Time to ');

        $text = $textRun->addText('wake ', array('bold' => true));
        $text->setChangeInfo(TrackChange::INSERTED, 'Fred', time() - 1800);

        $text = $textRun->addText('up');
        $text->setTrackChange(new TrackChange(TrackChange::INSERTED, 'Fred'));

        $text = $textRun->addText('go to sleep');
        $text->setChangeInfo(TrackChange::DELETED, 'Barney', new \DateTime('@' . (time() - 3600)));

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
}
