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
 *
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\Style;

use PhpOffice\PhpWord\Style\Chart;

/**
 * Test class for PhpOffice\PhpWord\Style\Chart.
 *
 * @coversDefaultClass          \PhpOffice\PhpWord\Style\Chart
 *
 * @runTestsInSeparateProcesses
 */
class ChartTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Testing getter and setter for chart width.
     */
    public function testSetGetWidth(): void
    {
        $chart = new Chart();

        self::assertEquals($chart->getWidth(), 1000000);

        $chart->setWidth(200);

        self::assertEquals($chart->getWidth(), 200);
    }

    /**
     * Testing getter and setter for chart height.
     */
    public function testSetGetHeight(): void
    {
        $chart = new Chart();

        self::assertEquals($chart->getHeight(), 1000000);

        $chart->setHeight(200);

        self::assertEquals($chart->getHeight(), 200);
    }

    /**
     * Testing getter and setter for is3d.
     */
    public function testSetIs3d(): void
    {
        $chart = new Chart();

        self::assertEquals($chart->is3d(), false);

        $chart->set3d(true);

        self::assertEquals($chart->is3d(), true);
    }

    /**
     * Testing getter and setter for chart colors.
     */
    public function testSetGetColors(): void
    {
        $chart = new Chart();

        self::assertIsArray($chart->getColors());

        self::assertEquals(count($chart->getColors()), 0);

        $chart->setColors(['FFFFFFFF', 'FF000000', 'FFFF0000']);

        self::assertEquals($chart->getColors(), ['FFFFFFFF', 'FF000000', 'FFFF0000']);
    }

    /**
     * Testing getter and setter for dataLabelOptions.
     */
    public function testSetGetDataLabelOptions(): void
    {
        $chart = new Chart();

        $originalDataLabelOptions = [
            'showVal' => true,
            'showCatName' => true,
            'showLegendKey' => false,
            'showSerName' => false,
            'showPercent' => false,
            'showLeaderLines' => false,
            'showBubbleSize' => false,
        ];

        self::assertEquals($chart->getDataLabelOptions(), $originalDataLabelOptions);

        $changedDataLabelOptions = [
            'showVal' => false,
            'showCatName' => false,
            'showLegendKey' => true,
            'showSerName' => true,
            'showPercent' => true,
            'showLeaderLines' => true,
            'showBubbleSize' => true,
        ];

        $chart->setDataLabelOptions(
            [
                'showVal' => false,
                'showCatName' => false,
                'showLegendKey' => true,
                'showSerName' => true,
                'showPercent' => true,
                'showLeaderLines' => true,
                'showBubbleSize' => true,
            ]
        );
        self::assertEquals($chart->getDataLabelOptions(), $changedDataLabelOptions);
    }

    /**
     * Testing categoryLabelPosition getter and setter.
     */
    public function testSetGetCategoryLabelPosition(): void
    {
        $chart = new Chart();

        self::assertEquals($chart->getCategoryLabelPosition(), 'nextTo');

        $chart->setCategoryLabelPosition('high');

        self::assertEquals($chart->getCategoryLabelPosition(), 'high');
    }

    /**
     * Testing valueLabelPosition getter and setter.
     */
    public function testSetGetValueLabelPosition(): void
    {
        $chart = new Chart();

        self::assertEquals($chart->getValueLabelPosition(), 'nextTo');

        $chart->setValueLabelPosition('low');

        self::assertEquals($chart->getValueLabelPosition(), 'low');
    }

    /**
     * Testing categoryAxisTitle getter and setter.
     */
    public function testSetGetCategoryAxisTitle(): void
    {
        $chart = new Chart();

        $chart->getCategoryAxisTitle();

        self::assertEquals($chart->getCategoryAxisTitle(), null);

        $chart->setCategoryAxisTitle('Test Category Axis Title');

        self::assertEquals($chart->getCategoryAxisTitle(), 'Test Category Axis Title');
    }

    /**
     * Testing valueAxisTitle getter and setter.
     */
    public function testSetGetValueAxisTitle(): void
    {
        $chart = new Chart();

        $chart->getValueAxisTitle();

        self::assertEquals($chart->getValueAxisTitle(), null);

        $chart->setValueAxisTitle('Test Value Axis Title');

        self::assertEquals($chart->getValueAxisTitle(), 'Test Value Axis Title');
    }
}
