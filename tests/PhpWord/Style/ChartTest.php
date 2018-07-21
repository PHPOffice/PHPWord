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
 * @see       https://github.com/PHPOffice/PHPWord
 * @copyright 2010-2017 PHPWord contributors
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

/**
 * Test class for PhpOffice\PhpWord\Style\Chart
 *
 * @coversDefaultClass          \PhpOffice\PhpWord\Style\Chart
 * @runTestsInSeparateProcesses
 */
class ChartTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Testing getter and setter for chart width
     */
    public function testSetGetWidth()
    {
        $chart = new Chart();

        $this->assertEquals($chart->getWidth(), 1000000);

        $chart->setWidth(200);

        $this->assertEquals($chart->getWidth(), 200);
    }

    /**
     * Testing getter and setter for chart height
     */
    public function testSetGetHeight()
    {
        $chart = new Chart();

        $this->assertEquals($chart->getHeight(), 1000000);

        $chart->setHeight(200);

        $this->assertEquals($chart->getHeight(), 200);
    }

    /**
     * Testing getter and setter for is3d
     */
    public function testSetIs3d()
    {
        $chart = new Chart();

        $this->assertEquals($chart->is3d(), false);

        $chart->set3d(true);

        $this->assertEquals($chart->is3d(), true);
    }

    /**
     * Testing getter and setter for chart colors
     */
    public function testSetGetColors()
    {
        $chart = new Chart();

        $this->assertInternalType('array', $chart->getColors());

        $this->assertEquals(count($chart->getColors()), 0);

        $chart->setColors(array('FFFFFFFF', 'FF000000', 'FFFF0000'));

        $this->assertEquals($chart->getColors(), array('FFFFFFFF', 'FF000000', 'FFFF0000'));
    }

    /**
     * Testing getter and setter for dataLabelOptions
     */
    public function testSetGetDataLabelOptions()
    {
        $chart = new Chart();

        $originalDataLabelOptions = array(
            'showVal'          => true,
            'showCatName'      => true,
            'showLegendKey'    => false,
            'showSerName'      => false,
            'showPercent'      => false,
            'showLeaderLines'  => false,
            'showBubbleSize'   => false,
        );

        $this->assertEquals($chart->getDataLabelOptions(), $originalDataLabelOptions);

        $changedDataLabelOptions = array(
            'showVal'          => false,
            'showCatName'      => false,
            'showLegendKey'    => true,
            'showSerName'      => true,
            'showPercent'      => true,
            'showLeaderLines'  => true,
            'showBubbleSize'   => true,
        );

        $chart->setDataLabelOptions(
            array(
                'showVal'          => false,
                'showCatName'      => false,
                'showLegendKey'    => true,
                'showSerName'      => true,
                'showPercent'      => true,
                'showLeaderLines'  => true,
                'showBubbleSize'   => true,
            )
        );
        $this->assertEquals($chart->getDataLabelOptions(), $changedDataLabelOptions);
    }

    /**
     * Testing categoryLabelPosition getter and setter
     */
    public function testSetGetCategoryLabelPosition()
    {
        $chart = new Chart();

        $this->assertEquals($chart->getCategoryLabelPosition(), 'nextTo');

        $chart->setCategoryLabelPosition('high');

        $this->assertEquals($chart->getCategoryLabelPosition(), 'high');
    }

    /**
     * Testing valueLabelPosition getter and setter
     */
    public function testSetGetValueLabelPosition()
    {
        $chart = new Chart();

        $this->assertEquals($chart->getValueLabelPosition(), 'nextTo');

        $chart->setValueLabelPosition('low');

        $this->assertEquals($chart->getValueLabelPosition(), 'low');
    }

    /**
     * Testing categoryAxisTitle getter and setter
     */
    public function testSetGetCategoryAxisTitle()
    {
        $chart = new Chart();

        $chart->getCategoryAxisTitle();

        $this->assertEquals($chart->getCategoryAxisTitle(), null);

        $chart->setCategoryAxisTitle('Test Category Axis Title');

        $this->assertEquals($chart->getCategoryAxisTitle(), 'Test Category Axis Title');
    }

    /**
     * Testing valueAxisTitle getter and setter
     */
    public function testSetGetValueAxisTitle()
    {
        $chart = new Chart();

        $chart->getValueAxisTitle();

        $this->assertEquals($chart->getValueAxisTitle(), null);

        $chart->setValueAxisTitle('Test Value Axis Title');

        $this->assertEquals($chart->getValueAxisTitle(), 'Test Value Axis Title');
    }
}
