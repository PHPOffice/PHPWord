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

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Element subnamespace
 */
class ChartTest extends \PHPUnit\Framework\TestCase
{
    private $outputEscapingEnabled;

    /**
     * Executed before each method of the class
     */
    public function setUp()
    {
        $this->outputEscapingEnabled = Settings::isOutputEscapingEnabled();
    }

    /**
     * Executed after each method of the class
     */
    public function tearDown()
    {
        Settings::setOutputEscapingEnabled($this->outputEscapingEnabled);
        TestHelperDOCX::clear();
    }

    /**
     * Test chart elements
     */
    public function testChartElements()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $style = array(
            'width'          => 5000000,
            'height'         => 5000000,
            'showAxisLabels' => true,
            'showGridX'      => true,
            'showGridY'      => true,
            'showLegend'     => false,
            );

        $chartTypes = array('pie', 'doughnut', 'bar', 'line', 'area', 'scatter', 'radar');
        $categories = array('A', 'B', 'C', 'D', 'E');
        $series1 = array(1, 3, 2, 5, 4);
        foreach ($chartTypes as $chartType) {
            $section->addChart($chartType, $categories, $series1, $style);
        }
        $colorArray = array('FFFFFF', '000000', 'FF0000', '00FF00', '0000FF');
        $numColor = count($colorArray);
        $chart = $section->addChart('pie', $categories, $series1, $style);
        $chart->getStyle()->setColors($colorArray)->setTitle('3d chart')->set3d(true);
        $chart = $section->addChart('stacked_bar', $categories, $series1, $style);
        $chart->getStyle()->setColors($colorArray)->setShowLegend(true);
        $chart = $section->addChart('scatter', $categories, $series1, $style);
        $chart->getStyle()->setMajorTickPosition('cross');
        $section->addChart('scatter', $categories, $series1, $style, 'seriesname');

        $doc = TestHelperDOCX::getDocument($phpWord);

        $index = 0;
        foreach ($chartTypes as $chartType) {
            ++$index;
            $file = "word/charts/chart{$index}.xml";
            $path = "/c:chartSpace/c:chart/c:plotArea/c:{$chartType}Chart";
            self::assertTrue($doc->elementExists($path, $file), "chart type $chartType");
        }

        $index = 11;
        $file = "word/charts/chart{$index}.xml";
        $doc->setDefaultFile($file);
        $chartType = 'scatter';
        $path = "/c:chartSpace/c:chart/c:plotArea/c:{$chartType}Chart";
        self::assertEquals('seriesname', $doc->getElement($path . '/c:ser/c:tx/c:strRef/c:strCache/c:pt/c:v')->nodeValue);

        $index = 8;
        $file = "word/charts/chart{$index}.xml";
        $doc->setDefaultFile($file);
        $chartType = 'pie3D';
        $path = "/c:chartSpace/c:chart/c:plotArea/c:{$chartType}Chart";
        for ($idx = 0; $idx < $numColor; ++$idx) {
            $idxp1 = $idx + 1;
            $element = $path . "/c:ser/c:dPt[$idxp1]/c:spPr/a:solidFill/a:srgbClr";
            self::assertEquals($colorArray[$idx], $doc->getElementAttribute($element, 'val'), "pie3d chart idx=$idx");
        }

        $index = 9;
        $file = "word/charts/chart{$index}.xml";
        $doc->setDefaultFile($file);
        $chartType = 'bar';
        $path = "/c:chartSpace/c:chart/c:plotArea/c:{$chartType}Chart";
        for ($idxp1 = 1; $idxp1 < $numColor; ++$idxp1) {
            $idx = $idxp1; // stacked bar chart is shifted
            $element = $path . "/c:ser/c:dPt[$idxp1]/c:spPr/a:solidFill/a:srgbClr";
            self::assertEquals($colorArray[$idx - 1], $doc->getElementAttribute($element, 'val'), "bar chart idx=$idx");
        }
    }

    public function testChartEscapingEnabled()
    {
        Settings::setOutputEscapingEnabled(true);
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $style = array(
            'width'          => 5000000,
            'height'         => 5000000,
            'showAxisLabels' => true,
            'showGridX'      => true,
            'showGridY'      => true,
            'showLegend'     => false,
            'valueAxisTitle' => 'Values',
            );
        $categories = array('A&B', 'C<D>', 'E', 'F', 'G');
        $series1 = array(1, 3, 2, 5, 4);
        $section->addChart('bar', $categories, $series1, $style);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $index = 1;
        $file = "word/charts/chart{$index}.xml";
        $doc->setDefaultFile($file);
        $chartType = 'bar';
        $path = "/c:chartSpace/c:chart/c:plotArea/c:{$chartType}Chart/c:ser/c:cat/c:strLit";
        $element = "$path/c:pt[1]/c:v";
        self::assertEquals('A&B', $doc->getElement($element)->nodeValue);
        $element = "$path/c:pt[2]/c:v";
        self::assertEquals('C<D>', $doc->getElement($element)->nodeValue);
    }

    public function testChartEscapingDisabled()
    {
        Settings::setOutputEscapingEnabled(false);
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $style = array(
            'width'          => 5000000,
            'height'         => 5000000,
            'showAxisLabels' => true,
            'showGridX'      => true,
            'showGridY'      => true,
            'showLegend'     => false,
            'valueAxisTitle' => 'Values',
            );
        $categories = array('A&amp;B', 'C&lt;D&gt;', 'E', 'F', 'G');
        $series1 = array(1, 3, 2, 5, 4);
        $section->addChart('bar', $categories, $series1, $style);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $index = 1;
        $file = "word/charts/chart{$index}.xml";
        $doc->setDefaultFile($file);
        $chartType = 'bar';
        $path = "/c:chartSpace/c:chart/c:plotArea/c:{$chartType}Chart/c:ser/c:cat/c:strLit";
        $element = "$path/c:pt[1]/c:v";
        self::assertEquals('A&B', $doc->getElement($element)->nodeValue);
        $element = "$path/c:pt[2]/c:v";
        self::assertEquals('C<D>', $doc->getElement($element)->nodeValue);
    }

    public function testValueAxisTitle()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $style = array(
            'width'          => 5000000,
            'height'         => 5000000,
            'showAxisLabels' => true,
            'showGridX'      => true,
            'showGridY'      => true,
            'showLegend'     => false,
            'valueAxisTitle' => 'Values',
            );
        $chartType = 'line';
        $categories = array('A', 'B', 'C', 'D', 'E');
        $series1 = array(1, 3, 2, 5, 4);
        $section->addChart($chartType, $categories, $series1, $style);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $index = 1;
        $file = "word/charts/chart{$index}.xml";
        $doc->setDefaultFile($file);
        $chartType = 'line';
        $path = '/c:chartSpace/c:chart/c:plotArea';
        $element = "$path/c:{$chartType}Chart";
        self::assertTrue($doc->elementExists($path));
        $element = "$path/c:valAx";
        self::assertTrue($doc->elementExists($element));
        $element .= '/c:title/c:tx/c:rich/a:p/a:r/a:t';
        self::assertEquals('Values', $doc->getElement($element)->nodeValue);
    }

    public function testNoAxisLabels()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $style = array(
            'width'          => 5000000,
            'height'         => 5000000,
            'showAxisLabels' => false,
            'showGridX'      => true,
            'showGridY'      => true,
            'showLegend'     => false,
            'valueAxisTitle' => 'Values',
            );
        $chartType = 'line';
        $categories = array('A', 'B', 'C', 'D', 'E');
        $series1 = array(1, 3, 2, 5, 4);
        $section->addChart($chartType, $categories, $series1, $style);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $index = 1;
        $file = "word/charts/chart{$index}.xml";
        $doc->setDefaultFile($file);
        $chartType = 'line';
        $path = '/c:chartSpace/c:chart/c:plotArea';
        $element = "$path/c:{$chartType}Chart";
        $element = "$path/c:valAx";
        $element .= '/c:tickLblPos';
        self::assertEquals('none', $doc->getElementAttribute($element, 'val'));
    }
}
