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

namespace PhpOffice\PhpWord\Style;

/**
 * Chart style
 *
 * @since 0.12.0
 */
class Chart extends AbstractStyle
{
    /**
     * Width (in EMU)
     *
     * @var int
     */
    private $width = 1000000;

    /**
     * Height (in EMU)
     *
     * @var int
     */
    private $height = 1000000;

    /**
     * Is 3D; applies to pie, bar, line, area
     *
     * @var bool
     */
    private $is3d = false;

    /**
     * A list of colors to use in the chart
     *
     * @var array
     */
    private $colors = array();

    /**
     * Chart title
     *
     * @var string
     */
    private $title = null;

    /**
     * Chart legend visibility
     *
     * @var bool
     */
    private $showLegend = false;

    /**
     * Chart legend Position.
     * Possible values are 'r', 't', 'b', 'l', 'tr'
     *
     * @var string
     */
    private $legendPosition = 'r';

    /**
     * A list of display options for data labels
     *
     * @var array
     */
    private $dataLabelOptions = array(
        'showVal'          => true, // value
        'showCatName'      => true, // category name
        'showLegendKey'    => false, //show the cart legend
        'showSerName'      => false, // series name
        'showPercent'      => false,
        'showLeaderLines'  => false,
        'showBubbleSize'   => false,
    );

    /**
     * A string that tells the writer where to write chart labels or to skip
     * "nextTo" - sets labels next to the axis (bar graphs on the left) (default)
     * "low" - labels on the left side of the graph
     * "high" - labels on the right side of the graph
     *
     * @var string
     */
    private $categoryLabelPosition = 'nextTo';

    /**
     * A string that tells the writer where to write chart labels or to skip
     * "nextTo" - sets labels next to the axis (bar graphs on the bottom) (default)
     * "low" - labels are below the graph
     * "high" - labels above the graph
     *
     * @var string
     */
    private $valueLabelPosition = 'nextTo';

    /**
     * @var string
     */
    private $categoryAxisTitle;

    /**
     * @var string
     */
    private $valueAxisTitle;

    /**
     * The position for major tick marks
     * Possible values are 'in', 'out', 'cross', 'none'
     *
     * @var string
     */
    private $majorTickMarkPos = 'none';

    /**
     * Show labels for axis
     *
     * @var bool
     */
    private $showAxisLabels = false;

    /**
     * Show Gridlines for Y-Axis
     *
     * @var bool
     */
    private $gridY = false;

    /**
     * Show Gridlines for X-Axis
     *
     * @var bool
     */
    private $gridX = false;

    /**
     * by #rat
     * Hole size as a percentage
     *
     * @var int
     */
    private $holeSize;

    /**
     * by #rat
     * chart position parameter ChartManualLayout
     *
     * @var ChartManualLayout
     */
    private $chartPositionInBlock = null;

    /**
     * by #rat
     * legend position parameter ChartManualLayout
     *
     * @var ChartManualLayout
     */
    private $legendPositionInBlock = null;

    /**
     * by #rat
     * schema segment separator
     *
     * @var bool
     */
    private $schemaSeparator = true;

    /**
     * by #rat
     * show X and Y Axes
     *
     * @var bool
     */
    private $axes = true;

    /**
     * Create a new instance
     *
     * @param array $style
     */
    public function __construct($style = array())
    {
        $this->setStyleByArray($style);
    }

    /**
     * Get width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set width
     *
     * @param int $value
     * @return self
     */
    public function setWidth($value = null)
    {
        $this->width = $this->setIntVal($value, $this->width);

        return $this;
    }

    /**
     * Get height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set height
     *
     * @param int $value
     * @return self
     */
    public function setHeight($value = null)
    {
        $this->height = $this->setIntVal($value, $this->height);

        return $this;
    }

    /**
     * Is 3D
     *
     * @return bool
     */
    public function is3d()
    {
        return $this->is3d;
    }

    /**
     * Set 3D
     *
     * @param bool $value
     * @return self
     */
    public function set3d($value = true)
    {
        $this->is3d = $this->setBoolVal($value, $this->is3d);

        return $this;
    }

    /**
     * Get the list of colors to use in a chart.
     *
     * @return array
     */
    public function getColors()
    {
        return $this->colors;
    }

    /**
     * Set the colors to use in a chart.
     *
     * @param array $value a list of colors to use in the chart
     * @return self
     */
    public function setColors($value = array())
    {
        $this->colors = $value;

        return $this;
    }

    /**
     * Get the chart title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the chart title
     *
     * @param string $value
     * @return self
     */
    public function setTitle($value = null)
    {
        $this->title = $value;

        return $this;
    }

    /**
     * Get chart legend visibility
     *
     * @return bool
     */
    public function isShowLegend()
    {
        return $this->showLegend;
    }

    /**
     * Set chart legend visibility
     *
     * @param bool $value
     * @return self
     */
    public function setShowLegend($value = false)
    {
        $this->showLegend = $value;

        return $this;
    }

    /**
     * Get chart legend position
     *
     * @return string
     */
    public function getLegendPosition()
    {
        return $this->legendPosition;
    }

    /**
     * Set chart legend position. choices:
     * "r" - right of chart
     * "b" - bottom of chart
     * "t" - top of chart
     * "l" - left of chart
     * "tr" - top right of chart
     *
     * default: right
     *
     * @param string $legendPosition
     * @return self
     */
    public function setLegendPosition($legendPosition = 'r')
    {
        $enum = array('r', 'b', 't', 'l', 'tr');
        $this->legendPosition = $this->setEnumVal($legendPosition, $enum, $this->legendPosition);

        return $this;
    }

    /**
     * Show labels for axis
     *
     * @return bool
     */
    public function showAxisLabels()
    {
        return $this->showAxisLabels;
    }

    /**
     * Set show Gridlines for Y-Axis
     *
     * @param bool $value
     * @return self
     */
    public function setShowAxisLabels($value = true)
    {
        $this->showAxisLabels = $this->setBoolVal($value, $this->showAxisLabels);

        return $this;
    }

    /**
     * get the list of options for data labels
     *
     * @return array
     */
    public function getDataLabelOptions()
    {
        return $this->dataLabelOptions;
    }

    /**
     * Set values for data label options.
     * This will only change values for options defined in $this->dataLabelOptions, and cannot create new ones.
     *
     * @param array $values [description]
     */
    public function setDataLabelOptions($values = array())
    {
        foreach (array_keys($this->dataLabelOptions) as $option) {
            if (isset($values[$option])) {
                $this->dataLabelOptions[$option] = $this->setBoolVal(
                    $values[$option],
                    $this->dataLabelOptions[$option]
                );
            }
        }
    }

    /**
     * Show Gridlines for Y-Axis
     *
     * @return bool
     */
    public function showGridY()
    {
        return $this->gridY;
    }

    /**
     * Set show Gridlines for Y-Axis
     *
     * @param bool $value
     * @return self
     */
    public function setShowGridY($value = true)
    {
        $this->gridY = $this->setBoolVal($value, $this->gridY);

        return $this;
    }

    /**
     * Get the categoryLabelPosition setting
     *
     * @return string
     */
    public function getCategoryLabelPosition()
    {
        return $this->categoryLabelPosition;
    }

    /**
     * Set the categoryLabelPosition setting
     * "none" - skips writing  labels
     * "nextTo" - sets labels next to the  (bar graphs on the left)
     * "low" - labels on the left side of the graph
     * "high" - labels on the right side of the graph
     *
     * @param mixed $labelPosition
     * @return self
     */
    public function setCategoryLabelPosition($labelPosition)
    {
        $enum = array('nextTo', 'low', 'high');
        $this->categoryLabelPosition = $this->setEnumVal($labelPosition, $enum, $this->categoryLabelPosition);

        return $this;
    }

    /**
     * Get the valueAxisLabelPosition setting
     *
     * @return string
     */
    public function getValueLabelPosition()
    {
        return $this->valueLabelPosition;
    }

    /**
     * Set the valueLabelPosition setting
     * "none" - skips writing labels
     * "nextTo" - sets labels next to the value
     * "low" - sets labels are below the graph
     * "high" - sets labels above the graph
     *
     * @param string
     * @param mixed $labelPosition
     */
    public function setValueLabelPosition($labelPosition)
    {
        $enum = array('nextTo', 'low', 'high');
        $this->valueLabelPosition = $this->setEnumVal($labelPosition, $enum, $this->valueLabelPosition);

        return $this;
    }

    /**
     * Get the categoryAxisTitle
     * @return string
     */
    public function getCategoryAxisTitle()
    {
        return $this->categoryAxisTitle;
    }

    /**
     * Set the title that appears on the category side of the chart
     * @param string $axisTitle
     */
    public function setCategoryAxisTitle($axisTitle)
    {
        $this->categoryAxisTitle = $axisTitle;

        return $this;
    }

    /**
     * Get the valueAxisTitle
     * @return string
     */
    public function getValueAxisTitle()
    {
        return $this->valueAxisTitle;
    }

    /**
     * Set the title that appears on the value side of the chart
     * @param string $axisTitle
     */
    public function setValueAxisTitle($axisTitle)
    {
        $this->valueAxisTitle = $axisTitle;

        return $this;
    }

    public function getMajorTickPosition()
    {
        return $this->majorTickMarkPos;
    }

    /**
     * Set the position for major tick marks
     * @param string $position
     */
    public function setMajorTickPosition($position)
    {
        $enum = array('in', 'out', 'cross', 'none');
        $this->majorTickMarkPos = $this->setEnumVal($position, $enum, $this->majorTickMarkPos);
    }

    /**
     * Show Gridlines for X-Axis
     *
     * @return bool
     */
    public function showGridX()
    {
        return $this->gridX;
    }

    /**
     * Set show Gridlines for X-Axis
     *
     * @param bool $value
     * @return self
     */
    public function setShowGridX($value = true)
    {
        $this->gridX = $this->setBoolVal($value, $this->gridX);

        return $this;
    }

    /**
     * by #rat
     * get hole size
     *
     * Get the Hole Size
     * @return int
     */
    public function getHoleSize(): int
    {
        return $this->holeSize;
    }

    /**
     * by #rat
     * set hole size
     *
     * @param int $value
     * @return self
     */
    public function setHoleSize(int $value): self
    {
        $this->holeSize = $this->setIntVal($value);

        return $this;
    }

    /**
     * by #rat
     * get chart position in block plotArea
     *
     * @return ChartManualLayout
     */
    public function getChartPositionInBlock()
    {
        return $this->chartPositionInBlock;
    }

    /**
     * by #rat
     * set chart position in block plotArea
     *
     * @param ChartManualLayout $chartManualLayout
     * @return self
     */
    public function setChartPositionInBlock(ChartManualLayout $chartManualLayout): self
    {
        $this->chartPositionInBlock = $chartManualLayout;

        return $this;
    }

    /**
     * by #rat
     * get legend position in block plotArea
     *
     * @return ChartManualLayout
     */
    public function getLegendPositionInBlock()
    {
        return $this->legendPositionInBlock;
    }

    /**
     * by #rat
     * set legend position in block plotArea
     *
     * @param ChartManualLayout $chartManualLayout
     * @return self
     */
    public function setLegendPositionInBlock(ChartManualLayout $chartManualLayout): self
    {
        $this->legendPositionInBlock = $chartManualLayout;

        return $this;
    }

    /**
     * by #rat
     * checked state schema separator
     *
     * @return bool
     */
    public function isSchemaSeparator(): bool
    {
        return $this->schemaSeparator;
    }

    /**
     * by #rat
     * change state schema separator
     *
     * @param bool $value
     * @return self
     */
    public function setSchemaSeparator(bool $value): self
    {
        $this->schemaSeparator = $this->setBoolVal($value, true);

        return $this;
    }

    /**
     * by #rat
     * Show X and Y Axes
     *
     * @return bool
     */
    public function showAxes(): bool
    {
        return $this->axes;
    }

    /**
     * by #rat
     * Set show X and Y Axes
     *
     * @param bool $value
     * @return self
     */
    public function setShowAxes(bool $value): self
    {
        $this->axes = $this->setBoolVal($value, true);

        return $this;
    }
}
