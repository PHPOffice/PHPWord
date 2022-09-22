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

namespace PhpOffice\PhpWordTests\Writer\Word2007;

use DateTime;
use PhpOffice\PhpWord\Element\Comment;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\TrackChange;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Element subnamespace.
 */
class ElementTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test unmatched element.
     */
    public function testUnmatchedElements(): void
    {
        $elements = [
            'CheckBox', 'Container', 'Footnote', 'Image', 'Link', 'ListItem', 'ListItemRun',
            'OLEObject', 'PreserveText', 'Table', 'Text', 'TextBox', 'TextBreak', 'Title', 'TOC',
            'Field', 'Line', 'Shape', 'Chart', 'FormField', 'SDT', 'Bookmark',
        ];
        foreach ($elements as $element) {
            $objectClass = 'PhpOffice\\PhpWord\\Writer\\Word2007\\Element\\' . $element;
            $xmlWriter = new XMLWriter();
            $newElement = new \PhpOffice\PhpWord\Element\PageBreak();
            $object = new $objectClass($xmlWriter, $newElement);
            $object->write();

            self::assertEquals('', $xmlWriter->getData());
        }
    }

    /**
     * Test line element.
     */
    public function testLineElement(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addLine(['width' => 1000, 'height' => 1000, 'positioning' => 'absolute', 'flip' => true]);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p/w:r/w:pict/v:shapetype';
        self::assertTrue($doc->elementExists($element));
    }

    /**
     * Test bookmark element.
     */
    public function testBookmark(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addBookmark('test_bookmark');
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:bookmarkStart';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('test_bookmark', $doc->getElementAttribute($element, 'w:name'));

        $element = '/w:document/w:body/w:bookmarkEnd';
        self::assertTrue($doc->elementExists($element));
    }

    /**
     * Test link element.
     */
    public function testLinkElement(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addLink('https://github.com/PHPOffice/PHPWord');
        $section->addLink('internal_link', null, null, null, true);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p[1]/w:hyperlink/w:r/w:t';
        self::assertTrue($doc->elementExists($element));

        $element = '/w:document/w:body/w:p[2]/w:hyperlink/w:r/w:t';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('internal_link', $doc->getElementAttribute('/w:document/w:body/w:p[2]/w:hyperlink', 'w:anchor'));
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

        $doc = TestHelperDOCX::getDocument($phpWord);

        $tableRootElement = '/w:document/w:body/w:tbl';
        self::assertTrue($doc->elementExists($tableRootElement . '/w:tblGrid/w:gridCol'));
        self::assertTrue($doc->elementExists($tableRootElement . '/w:tblPr/w:jc'));
        self::assertEquals('center', $doc->getElementAttribute($tableRootElement . '/w:tblPr/w:jc', 'w:val'));
    }

    /**
     * Tests that the style name gets added.
     */
    public function testTableWithStyleName(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $table = $section->addTable('my_predefined_style');
        $table->setWidth(75);
        $table->addRow(900);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $tableRootElement = '/w:document/w:body/w:tbl';
        self::assertTrue($doc->elementExists($tableRootElement . '/w:tblPr/w:tblStyle'));
        self::assertEquals('my_predefined_style', $doc->getElementAttribute($tableRootElement . '/w:tblPr/w:tblStyle', 'w:val'));
    }

    /**
     * Test shape elements.
     */
    public function testShapeElements(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Arc
        $section->addShape(
            'arc',
            [
                'points' => '-90 20',
                'frame' => ['width' => 120, 'height' => 120],
                'outline' => ['color' => '#333333', 'weight' => 2, 'startArrow' => 'oval', 'endArrow' => 'open'],
            ]
        );

        // Curve
        $section->addShape(
            'curve',
            [
                'points' => '1,100 200,1 1,50 200,50', 'connector' => 'elbow',
                'outline' => [
                    'color' => '#66cc00',
                    'weight' => 2,
                    'dash' => 'dash',
                    'startArrow' => 'diamond',
                    'endArrow' => 'block',
                ],
            ]
        );

        // Line
        $section->addShape(
            'line',
            [
                'points' => '1,1 150,30',
                'outline' => [
                    'color' => '#cc00ff',
                    'line' => 'thickThin',
                    'weight' => 3,
                    'startArrow' => 'oval',
                    'endArrow' => 'classic',
                    'endCap' => 'round',
                ],
            ]
        );

        // Polyline
        $section->addShape(
            'polyline',
            [
                'points' => '1,30 20,10 55,20 75,10 100,40 115,50, 120,15 200,50',
                'outline' => [
                    'color' => '#cc6666',
                    'weight' => 2,
                    'startArrow' => 'none',
                    'endArrow' => 'classic',
                ],
            ]
        );

        // Rectangle
        $section->addShape(
            'rect',
            [
                'roundness' => 0.2,
                'frame' => ['width' => 100, 'height' => 100, 'left' => 1, 'top' => 1],
                'fill' => ['color' => '#FFCC33'],
                'outline' => ['color' => '#990000', 'weight' => 1],
                'shadow' => ['color' => '#EEEEEE', 'offset' => '3pt,3pt'],
            ]
        );

        // Oval
        $section->addShape(
            'oval',
            [
                'frame' => ['width' => 100, 'height' => 70, 'left' => 1, 'top' => 1],
                'fill' => ['color' => '#33CC99'],
                'outline' => ['color' => '#333333', 'weight' => 2],
                'extrusion' => ['type' => 'perspective', 'color' => '#EEEEEE'],
            ]
        );

        $doc = TestHelperDOCX::getDocument($phpWord);

        $elements = ['arc', 'curve', 'line', 'polyline', 'roundrect', 'oval'];
        foreach ($elements as $element) {
            $path = "/w:document/w:body/w:p/w:r/w:pict/v:{$element}";
            self::assertTrue($doc->elementExists($path));
        }
    }

    // testChartElements moved to Writer/Word2007/Element/ChartTest

    public function testFieldElement(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addField('INDEX', [], ['\\c "3"']);
        $section->addField('XE', [], ['Bold', 'Italic'], 'Index Entry');
        $section->addField('DATE', ['dateformat' => 'd-M-yyyy'], ['PreserveFormat', 'LastUsedFormat']);
        $section->addField('DATE', [], ['LunarCalendar']);
        $section->addField('DATE', [], ['SakaEraCalendar']);
        $section->addField('NUMPAGES', ['format' => 'roman', 'numformat' => '0,00'], ['SakaEraCalendar']);
        $section->addField('STYLEREF', ['StyleIdentifier' => 'Heading 1']);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p/w:r/w:instrText';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals(' INDEX \\c "3" ', $doc->getElement($element)->textContent);
    }

    public function testUnstyledFieldElement(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addFontStyle('h1', ['name' => 'Courier New', 'size' => 8]);
        $section = $phpWord->addSection();

        $section->addField('PAGE');
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p/w:r[2]/w:instrText';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals(' PAGE ', $doc->getElement($element)->textContent);
        $sty = '/w:document/w:body/w:p/w:r[2]/w:rPr';
        self::assertFalse($doc->elementExists($sty));
    }

    public function testStyledFieldElement(): void
    {
        $phpWord = new PhpWord();
        $stnam = 'h1';
        $phpWord->addFontStyle($stnam, ['name' => 'Courier New', 'size' => 8]);
        $section = $phpWord->addSection();

        $fld = $section->addField('PAGE');
        $fld->setFontStyle($stnam);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p/w:r[2]/w:instrText';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals(' PAGE ', $doc->getElement($element)->textContent);
        $sty = '/w:document/w:body/w:p/w:r[2]/w:rPr';
        self::assertTrue($doc->elementExists($sty));
        self::assertEquals($stnam, $doc->getElementAttribute($sty . '/w:rStyle', 'w:val'));
    }

    public function testFieldElementWithComplexText(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $text = new TextRun();
        $text->addText('test string', ['bold' => true]);

        $section->addField('XE', [], ['Bold', 'Italic'], $text);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p/w:r[2]/w:instrText';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals(' XE "', $doc->getElement($element)->textContent);

        $element = '/w:document/w:body/w:p/w:r[3]/w:rPr/w:b';
        self::assertTrue($doc->elementExists($element));

        $element = '/w:document/w:body/w:p/w:r[3]/w:t';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('test string', $doc->getElement($element)->textContent);

        $element = '/w:document/w:body/w:p/w:r[4]/w:instrText';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('"\\b \\i ', $doc->getElement($element)->textContent);
    }

    /**
     * Test writing the macrobutton field.
     */
    public function testMacroButtonField(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $macroText = new TextRun();
        $macroText->addText('Double click', ['bold' => true]);
        $macroText->addText(' to ');
        $macroText->addText('zoom to 100%', ['italic' => true]);

        $section->addField('MACROBUTTON', ['macroname' => 'Zoom100'], [], $macroText);
        $section->addField('MACROBUTTON', ['macroname' => 'Zoom100'], [], 'double click to zoom');
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p[1]/w:r[2]/w:instrText';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals(' MACROBUTTON Zoom100 ', $doc->getElement($element)->textContent);

        $element = '/w:document/w:body/w:p[1]/w:r[3]/';
        self::assertTrue($doc->elementExists($element . 'w:t'));
        self::assertEquals('Double click', $doc->getElement($element . 'w:t')->textContent);
        self::assertTrue($doc->elementExists($element . 'w:rPr/w:b'));

        $element = '/w:document/w:body/w:p[2]/w:r[2]/w:instrText';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals(' MACROBUTTON Zoom100 double click to zoom ', $doc->getElement($element)->textContent);
    }

    // testFormFieldElements moved to Writer/Word2007/Element/FormFieldTest

    /**
     * Test SDT elements.
     */
    public function testSDTElements(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addSDT('comboBox')->setListItems(['1' => 'Choice 1', '2' => 'Choice 2'])->setValue('select value');
        $section->addSDT('dropDownList');
        $section->addSDT('date')->setAlias('date_alias')->setTag('my_tag');
        $section->addSDT('plainText');

        $doc = TestHelperDOCX::getDocument($phpWord);

        $path = '/w:document/w:body/w:p';

        self::assertTrue($doc->elementExists($path . '[1]/w:sdt/w:sdtContent/w:r/w:t'));
        self::assertEquals('select value', $doc->getElement($path . '[1]/w:sdt/w:sdtContent/w:r/w:t')->nodeValue);
        self::assertTrue($doc->elementExists($path . '[1]/w:sdt/w:sdtPr/w:comboBox'));
        self::assertTrue($doc->elementExists($path . '[1]/w:sdt/w:sdtPr/w:comboBox/w:listItem'));
        self::assertEquals('1', $doc->getElementAttribute($path . '[1]/w:sdt/w:sdtPr/w:comboBox/w:listItem[1]', 'w:value'));
        self::assertEquals('Choice 1', $doc->getElementAttribute($path . '[1]/w:sdt/w:sdtPr/w:comboBox/w:listItem[1]', 'w:displayText'));

        self::assertTrue($doc->elementExists($path . '[2]/w:sdt/w:sdtPr/w:dropDownList'));
        self::assertFalse($doc->elementExists($path . '[2]/w:sdt/w:sdtPr/w:alias'));

        self::assertTrue($doc->elementExists($path . '[3]/w:sdt/w:sdtPr/w:date'));
        self::assertTrue($doc->elementExists($path . '[3]/w:sdt/w:sdtPr/w:alias'));
        self::assertTrue($doc->elementExists($path . '[3]/w:sdt/w:sdtPr/w:tag'));

        self::assertTrue($doc->elementExists($path . '[4]/w:sdt/w:sdtPr/w:text'));
    }

    /**
     * Test Comment element.
     */
    public function testCommentWithoutEndElement(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $comment = new Comment('tester');
        $phpWord->addComment($comment);

        $element = $section->addText('this is a test');
        $element->setCommentRangeStart($comment);

        $doc = TestHelperDOCX::getDocument($phpWord);
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:commentRangeStart'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:commentRangeEnd'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:commentReference'));
    }

    /**
     * Test Comment element.
     */
    public function testCommentWithEndElement(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $comment = new Comment('tester');
        $phpWord->addComment($comment);

        $element = $section->addText('this is a test');
        $element->setCommentRangeStart($comment);
        $element->setCommentRangeEnd($comment);

        $doc = TestHelperDOCX::getDocument($phpWord);
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:commentRangeStart'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:commentRangeEnd'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:commentReference'));
    }

    /**
     * Test Track changes.
     */
    public function testTrackChange(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $text = $section->addText('my dummy text');
        $text->setChangeInfo(TrackChange::INSERTED, 'author name');
        $text2 = $section->addText('my other text');
        $text2->setTrackChange(new TrackChange(TrackChange::DELETED, 'another author', new DateTime()));

        $doc = TestHelperDOCX::getDocument($phpWord);

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:ins/w:r'));
        self::assertEquals('author name', $doc->getElementAttribute('/w:document/w:body/w:p/w:ins', 'w:author'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:del/w:r/w:delText'));
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

        $doc = TestHelperDOCX::getDocument($phpWord);

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:r/w:t'));
        self::assertEquals('This is a title', $doc->getElement('/w:document/w:body/w:p[1]/w:r/w:t')->textContent);
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:pPr/w:pStyle'));
        self::assertEquals('Title', $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:pPr/w:pStyle', 'w:val'));

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[2]/w:r/w:t'));
        self::assertEquals('Heading 1', $doc->getElement('/w:document/w:body/w:p[2]/w:r/w:t')->textContent);
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[2]/w:pPr/w:pStyle'));
        self::assertEquals('Heading1', $doc->getElementAttribute('/w:document/w:body/w:p[2]/w:pPr/w:pStyle', 'w:val'));
    }

    /**
     * Test correct writing of text with ampersant in it.
     */
    public function testTextWithAmpersant(): void
    {
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('this text contains an & (ampersant)');

        $doc = TestHelperDOCX::getDocument($phpWord);
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:t'));
        self::assertEquals('this text contains an & (ampersant)', $doc->getElement('/w:document/w:body/w:p/w:r/w:t')->nodeValue);
    }

    /**
     * Test ListItemRun paragraph style writing.
     */
    public function testListItemRunStyleWriting(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addParagraphStyle('MyParagraphStyle', ['spaceBefore' => 400]);

        $section = $phpWord->addSection();
        $listItemRun = $section->addListItemRun(0, null, 'MyParagraphStyle');
        $listItemRun->addText('List item');
        $listItemRun->addText(' in bold', ['bold' => true]);

        $doc = TestHelperDOCX::getDocument($phpWord);
        self::assertFalse($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:pPr'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:pStyle'));
        self::assertEquals('List item', $doc->getElement('/w:document/w:body/w:p/w:r[1]/w:t')->nodeValue);
        self::assertEquals(' in bold', $doc->getElement('/w:document/w:body/w:p/w:r[2]/w:t')->nodeValue);
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r[2]/w:rPr/w:b'));
    }
}
