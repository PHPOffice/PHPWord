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
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\Common\XMLWriter;
use PhpOffice\PhpWord\Element\Comment;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\PhpWord;
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
            'Object', 'PreserveText', 'Table', 'Text', 'TextBox', 'TextBreak', 'Title', 'TOC',
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

    /**
     * Test shape elements
     */
    public function testChartElements()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $style = array('width' => 1000000, 'height' => 1000000);

        $chartTypes = array('pie', 'doughnut', 'bar', 'line', 'area', 'scatter', 'radar');
        $categories = array('A', 'B', 'C', 'D', 'E');
        $series1 = array(1, 3, 2, 5, 4);
        foreach ($chartTypes as $chartType) {
            $section->addChart($chartType, $categories, $series1, $style);
        }
        $section->addChart('pie', $categories, $series1, array('3d' => true));

        $doc = TestHelperDOCX::getDocument($phpWord);

        $index = 0;
        foreach ($chartTypes as $chartType) {
            ++$index;
            $file = "word/charts/chart{$index}.xml";
            $path = "/c:chartSpace/c:chart/c:plotArea/c:{$chartType}Chart";
            $this->assertTrue($doc->elementExists($path, $file));
        }
    }

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
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p/w:r/w:instrText';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals(' INDEX \\c "3" ', $doc->getElement($element)->textContent);
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
     * Test form fields
     */
    public function testFormFieldElements()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addFormField('textinput')->setName('MyTextBox');
        $section->addFormField('checkbox')->setDefault(true)->setValue('Your name');
        $section->addFormField('checkbox')->setDefault(true);
        $section->addFormField('dropdown')->setEntries(array('Choice 1', 'Choice 2', 'Choice 3'));

        $doc = TestHelperDOCX::getDocument($phpWord);

        $path = '/w:document/w:body/w:p[%d]/w:r/w:fldChar/w:ffData';
        $this->assertTrue($doc->elementExists(sprintf($path, 1) . '/w:textInput'));
        $this->assertTrue($doc->elementExists(sprintf($path, 2) . '/w:checkBox'));
        $this->assertTrue($doc->elementExists(sprintf($path, 3) . '/w:checkBox'));
        $this->assertTrue($doc->elementExists(sprintf($path, 4) . '/w:ddList'));
    }

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
}
