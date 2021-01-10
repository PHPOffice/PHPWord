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

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\Element\Comment;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\TrackChange;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Element subnamespace
 */
class ElementTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test unmatched element
     */
    public function testUnmatchedElements()
    {
        $elements = array(
            'CheckBox', 'Container', 'Footnote', 'Image', 'Link', 'ListItem', 'ListItemRun',
            'OLEObject', 'PreserveText', 'Table', 'Text', 'TextBox', 'TextBreak', 'Title', 'TOC',
            'Field', 'Line', 'Shape', 'Chart', 'FormField', 'SDT', 'Bookmark',
        );
        foreach ($elements as $element) {
            $objectClass = 'PhpOffice\\PhpWord\\Writer\\Word2007\\Element\\' . $element;
            $xmlWriter = new XMLWriter();
            $newElement = new \PhpOffice\PhpWord\Element\PageBreak();
            $object = new $objectClass($xmlWriter, $newElement);
            $object->write();

            $this->assertEquals('', $xmlWriter->getData());
        }
    }

    /**
     * Test line element
     */
    public function testLineElement()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addLine(array('width' => 1000, 'height' => 1000, 'positioning' => 'absolute', 'flip' => true));
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p/w:r/w:pict/v:shapetype';
        $this->assertTrue($doc->elementExists($element));
    }

    /**
     * Test bookmark element
     */
    public function testBookmark()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addBookmark('test_bookmark');
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:bookmarkStart';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('test_bookmark', $doc->getElementAttribute($element, 'w:name'));

        $element = '/w:document/w:body/w:bookmarkEnd';
        $this->assertTrue($doc->elementExists($element));
    }

    /**
     * Test link element
     */
    public function testLinkElement()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addLink('https://github.com/PHPOffice/PHPWord');
        $section->addLink('internal_link', null, null, null, true);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p[1]/w:hyperlink/w:r/w:t';
        $this->assertTrue($doc->elementExists($element));

        $element = '/w:document/w:body/w:p[2]/w:hyperlink/w:r/w:t';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('internal_link', $doc->getElementAttribute('/w:document/w:body/w:p[2]/w:hyperlink', 'w:anchor'));
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

        $doc = TestHelperDOCX::getDocument($phpWord);

        $tableRootElement = '/w:document/w:body/w:tbl';
        $this->assertTrue($doc->elementExists($tableRootElement . '/w:tblGrid/w:gridCol'));
        $this->assertTrue($doc->elementExists($tableRootElement . '/w:tblPr/w:jc'));
        $this->assertEquals('center', $doc->getElementAttribute($tableRootElement . '/w:tblPr/w:jc', 'w:val'));
    }

    /**
     * Tests that the style name gets added
     */
    public function testTableWithStyleName()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $table = $section->addTable('my_predefined_style');
        $table->setWidth(75);
        $table->addRow(900);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $tableRootElement = '/w:document/w:body/w:tbl';
        $this->assertTrue($doc->elementExists($tableRootElement . '/w:tblPr/w:tblStyle'));
        $this->assertEquals('my_predefined_style', $doc->getElementAttribute($tableRootElement . '/w:tblPr/w:tblStyle', 'w:val'));
    }

    /**
     * Test shape elements
     */
    public function testShapeElements()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Arc
        $section->addShape(
            'arc',
            array(
                'points'  => '-90 20',
                'frame'   => array('width' => 120, 'height' => 120),
                'outline' => array('color' => '#333333', 'weight' => 2, 'startArrow' => 'oval', 'endArrow' => 'open'),
            )
        );

        // Curve
        $section->addShape(
            'curve',
            array(
                'points'  => '1,100 200,1 1,50 200,50', 'connector' => 'elbow',
                'outline' => array(
                    'color'      => '#66cc00',
                    'weight'     => 2,
                    'dash'       => 'dash',
                    'startArrow' => 'diamond',
                    'endArrow'   => 'block',
                ),
            )
        );

        // Line
        $section->addShape(
            'line',
            array(
                'points'  => '1,1 150,30',
                'outline' => array(
                    'color'      => '#cc00ff',
                    'line'       => 'thickThin',
                    'weight'     => 3,
                    'startArrow' => 'oval',
                    'endArrow'   => 'classic',
                    'endCap'     => 'round',
                ),
            )
        );

        // Polyline
        $section->addShape(
            'polyline',
            array(
                'points'  => '1,30 20,10 55,20 75,10 100,40 115,50, 120,15 200,50',
                'outline' => array(
                    'color'      => '#cc6666',
                    'weight'     => 2,
                    'startArrow' => 'none',
                    'endArrow'   => 'classic',
                ),
            )
        );

        // Rectangle
        $section->addShape(
            'rect',
            array(
                'roundness' => 0.2,
                'frame'     => array('width' => 100, 'height' => 100, 'left' => 1, 'top' => 1),
                'fill'      => array('color' => '#FFCC33'),
                'outline'   => array('color' => '#990000', 'weight' => 1),
                'shadow'    => array('color' => '#EEEEEE', 'offset' => '3pt,3pt'),
            )
        );

        // Oval
        $section->addShape(
            'oval',
            array(
                'frame'     => array('width' => 100, 'height' => 70, 'left' => 1, 'top' => 1),
                'fill'      => array('color' => '#33CC99'),
                'outline'   => array('color' => '#333333', 'weight' => 2),
                'extrusion' => array('type' => 'perspective', 'color' => '#EEEEEE'),
            )
        );

        $doc = TestHelperDOCX::getDocument($phpWord);

        $elements = array('arc', 'curve', 'line', 'polyline', 'roundrect', 'oval');
        foreach ($elements as $element) {
            $path = "/w:document/w:body/w:p/w:r/w:pict/v:{$element}";
            $this->assertTrue($doc->elementExists($path));
        }
    }

    // testChartElements moved to Writer/Word2007/Element/ChartTest

    public function testFieldElement()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addField('INDEX', array(), array('\\c "3"'));
        $section->addField('XE', array(), array('Bold', 'Italic'), 'Index Entry');
        $section->addField('DATE', array('dateformat' => 'd-M-yyyy'), array('PreserveFormat', 'LastUsedFormat'));
        $section->addField('DATE', array(), array('LunarCalendar'));
        $section->addField('DATE', array(), array('SakaEraCalendar'));
        $section->addField('NUMPAGES', array('format' => 'roman', 'numformat' => '0,00'), array('SakaEraCalendar'));
        $section->addField('STYLEREF', array('StyleIdentifier' => 'Heading 1'));
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p/w:r/w:instrText';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals(' INDEX \\c "3" ', $doc->getElement($element)->textContent);
    }

    public function testUnstyledFieldElement()
    {
        $phpWord = new PhpWord();
        $phpWord->addFontStyle('h1', array('name' => 'Courier New', 'size' => 8));
        $section = $phpWord->addSection();

        $section->addField('PAGE');
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p/w:r[2]/w:instrText';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals(' PAGE ', $doc->getElement($element)->textContent);
        $sty = '/w:document/w:body/w:p/w:r[2]/w:rPr';
        $this->assertFalse($doc->elementExists($sty));
    }

    public function testStyledFieldElement()
    {
        $phpWord = new PhpWord();
        $stnam = 'h1';
        $phpWord->addFontStyle($stnam, array('name' => 'Courier New', 'size' => 8));
        $section = $phpWord->addSection();

        $fld = $section->addField('PAGE');
        $fld->setFontStyle($stnam);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p/w:r[2]/w:instrText';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals(' PAGE ', $doc->getElement($element)->textContent);
        $sty = '/w:document/w:body/w:p/w:r[2]/w:rPr';
        $this->assertTrue($doc->elementExists($sty));
        $this->assertEquals($stnam, $doc->getElementAttribute($sty . '/w:rStyle', 'w:val'));
    }

    public function testFieldElementWithComplexText()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $text = new TextRun();
        $text->addText('test string', array('bold' => true));

        $section->addField('XE', array(), array('Bold', 'Italic'), $text);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p/w:r[2]/w:instrText';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals(' XE "', $doc->getElement($element)->textContent);

        $element = '/w:document/w:body/w:p/w:r[3]/w:rPr/w:b';
        $this->assertTrue($doc->elementExists($element));

        $element = '/w:document/w:body/w:p/w:r[3]/w:t';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('test string', $doc->getElement($element)->textContent);

        $element = '/w:document/w:body/w:p/w:r[4]/w:instrText';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('"\\b \\i ', $doc->getElement($element)->textContent);
    }

    /**
     * Test writing the macrobutton field
     */
    public function testMacroButtonField()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $macroText = new TextRun();
        $macroText->addText('Double click', array('bold' => true));
        $macroText->addText(' to ');
        $macroText->addText('zoom to 100%', array('italic' => true));

        $section->addField('MACROBUTTON', array('macroname' => 'Zoom100'), array(), $macroText);
        $section->addField('MACROBUTTON', array('macroname' => 'Zoom100'), array(), 'double click to zoom');
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p[1]/w:r[2]/w:instrText';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals(' MACROBUTTON Zoom100 ', $doc->getElement($element)->textContent);

        $element = '/w:document/w:body/w:p[1]/w:r[3]/';
        $this->assertTrue($doc->elementExists($element . 'w:t'));
        $this->assertEquals('Double click', $doc->getElement($element . 'w:t')->textContent);
        $this->assertTrue($doc->elementExists($element . 'w:rPr/w:b'));

        $element = '/w:document/w:body/w:p[2]/w:r[2]/w:instrText';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals(' MACROBUTTON Zoom100 double click to zoom ', $doc->getElement($element)->textContent);
    }

    // testFormFieldElements moved to Writer/Word2007/Element/FormFieldTest

    /**
     * Test SDT elements
     */
    public function testSDTElements()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addSDT('comboBox')->setListItems(array('1' => 'Choice 1', '2' => 'Choice 2'))->setValue('select value');
        $section->addSDT('dropDownList');
        $section->addSDT('date')->setAlias('date_alias')->setTag('my_tag');
        $section->addSDT('plainText');

        $doc = TestHelperDOCX::getDocument($phpWord);

        $path = '/w:document/w:body/w:p';

        $this->assertTrue($doc->elementExists($path . '[1]/w:sdt/w:sdtContent/w:r/w:t'));
        $this->assertEquals('select value', $doc->getElement($path . '[1]/w:sdt/w:sdtContent/w:r/w:t')->nodeValue);
        $this->assertTrue($doc->elementExists($path . '[1]/w:sdt/w:sdtPr/w:comboBox'));
        $this->assertTrue($doc->elementExists($path . '[1]/w:sdt/w:sdtPr/w:comboBox/w:listItem'));
        $this->assertEquals('1', $doc->getElementAttribute($path . '[1]/w:sdt/w:sdtPr/w:comboBox/w:listItem[1]', 'w:value'));
        $this->assertEquals('Choice 1', $doc->getElementAttribute($path . '[1]/w:sdt/w:sdtPr/w:comboBox/w:listItem[1]', 'w:displayText'));

        $this->assertTrue($doc->elementExists($path . '[2]/w:sdt/w:sdtPr/w:dropDownList'));
        $this->assertFalse($doc->elementExists($path . '[2]/w:sdt/w:sdtPr/w:alias'));

        $this->assertTrue($doc->elementExists($path . '[3]/w:sdt/w:sdtPr/w:date'));
        $this->assertTrue($doc->elementExists($path . '[3]/w:sdt/w:sdtPr/w:alias'));
        $this->assertTrue($doc->elementExists($path . '[3]/w:sdt/w:sdtPr/w:tag'));

        $this->assertTrue($doc->elementExists($path . '[4]/w:sdt/w:sdtPr/w:text'));
    }

    /**
     * Test Comment element
     */
    public function testCommentWithoutEndElement()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $comment = new Comment('tester');
        $phpWord->addComment($comment);

        $element = $section->addText('this is a test');
        $element->setCommentRangeStart($comment);

        $doc = TestHelperDOCX::getDocument($phpWord);
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:commentRangeStart'));
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:commentRangeEnd'));
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:commentReference'));
    }

    /**
     * Test Comment element
     */
    public function testCommentWithEndElement()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $comment = new Comment('tester');
        $phpWord->addComment($comment);

        $element = $section->addText('this is a test');
        $element->setCommentRangeStart($comment);
        $element->setCommentRangeEnd($comment);

        $doc = TestHelperDOCX::getDocument($phpWord);
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:commentRangeStart'));
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:commentRangeEnd'));
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:commentReference'));
    }

    /**
     * Test Track changes
     */
    public function testTrackChange()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $text = $section->addText('my dummy text');
        $text->setChangeInfo(TrackChange::INSERTED, 'author name');
        $text2 = $section->addText('my other text');
        $text2->setTrackChange(new TrackChange(TrackChange::DELETED, 'another author', new \DateTime()));

        $doc = TestHelperDOCX::getDocument($phpWord);

        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:ins/w:r'));
        $this->assertEquals('author name', $doc->getElementAttribute('/w:document/w:body/w:p/w:ins', 'w:author'));
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:del/w:r/w:delText'));
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

        $doc = TestHelperDOCX::getDocument($phpWord);

        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:r/w:t'));
        $this->assertEquals('This is a title', $doc->getElement('/w:document/w:body/w:p[1]/w:r/w:t')->textContent);
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:pPr/w:pStyle'));
        $this->assertEquals('Title', $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:pPr/w:pStyle', 'w:val'));

        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p[2]/w:r/w:t'));
        $this->assertEquals('Heading 1', $doc->getElement('/w:document/w:body/w:p[2]/w:r/w:t')->textContent);
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p[2]/w:pPr/w:pStyle'));
        $this->assertEquals('Heading1', $doc->getElementAttribute('/w:document/w:body/w:p[2]/w:pPr/w:pStyle', 'w:val'));
    }

    /**
     * Test correct writing of text with ampersant in it
     */
    public function testTextWithAmpersant()
    {
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('this text contains an & (ampersant)');

        $doc = TestHelperDOCX::getDocument($phpWord);
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:t'));
        $this->assertEquals('this text contains an & (ampersant)', $doc->getElement('/w:document/w:body/w:p/w:r/w:t')->nodeValue);
    }

    /**
     * Test ListItemRun paragraph style writing
     */
    public function testListItemRunStyleWriting()
    {
        $phpWord = new PhpWord();
        $phpWord->addParagraphStyle('MyParagraphStyle', array('spaceBefore' => 400));

        $section = $phpWord->addSection();
        $listItemRun = $section->addListItemRun(0, null, 'MyParagraphStyle');
        $listItemRun->addText('List item');
        $listItemRun->addText(' in bold', array('bold' => true));

        $doc = TestHelperDOCX::getDocument($phpWord);
        $this->assertFalse($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:pPr'));
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:pStyle'));
        $this->assertEquals('List item', $doc->getElement('/w:document/w:body/w:p/w:r[1]/w:t')->nodeValue);
        $this->assertEquals(' in bold', $doc->getElement('/w:document/w:body/w:p/w:r[2]/w:t')->nodeValue);
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r[2]/w:rPr/w:b'));
    }
}
