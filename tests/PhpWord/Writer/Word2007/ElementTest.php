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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */
namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\Common\XMLWriter;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Element subnamespace
 */
class ElementTest extends \PHPUnit_Framework_TestCase
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
            'Field', 'Line', 'Shape', 'Chart', 'FormField', 'SDT',
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
            $index++;
            $file = "word/charts/chart{$index}.xml";
            $path = "/c:chartSpace/c:chart/c:plotArea/c:{$chartType}Chart";
            $this->assertTrue($doc->elementExists($path, $file));
        }
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
        $section->addFormField('dropdown')->setEntries(array('Choice 1', 'Choice 2', 'Choice 3'));

        $doc = TestHelperDOCX::getDocument($phpWord);

        $path = '/w:document/w:body/w:p/w:r/w:fldChar/w:ffData';
        $this->assertTrue($doc->elementExists($path . '/w:textInput'));
        $this->assertTrue($doc->elementExists($path . '/w:checkBox'));
        $this->assertTrue($doc->elementExists($path . '/w:ddList'));
    }

    /**
     * Test SDT elements
     */
    public function testSDTElements()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addSDT('comboBox');
        $section->addSDT('dropDownList');
        $section->addSDT('date');

        $doc = TestHelperDOCX::getDocument($phpWord);

        $path = '/w:document/w:body/w:p/w:sdt/w:sdtPr';
        $this->assertTrue($doc->elementExists($path . '/w:comboBox'));
        $this->assertTrue($doc->elementExists($path . '/w:dropDownList'));
        $this->assertTrue($doc->elementExists($path . '/w:date'));
    }
}
