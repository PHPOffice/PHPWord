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

    const LANG_RU = 'ru';

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
     * schema segment separator
     *
     * @var int
     */
    private $schemaSeparatorSize = 1200;

    /**
     * by #rat
     * show X and Y Axes
     *
     * @var bool
     */
    private $axes = true;

    /**
     * by #rat
     * separator (dot) color
     *
     * @var string
     */
    protected  $elementSeparatorColor = 'bg1';

    /**
     * by #rat
     * marker (dot) shape
     *
     * @var string
     */
    protected $markerShape = 'circle';

    /**
     * by #rat
     * marker(dot) color
     *
     * @var string
     */
    protected $markerColor = '3279D7';

    /**
     * by #rat
     * marker(dot) size
     *
     * @var int
     */
    protected $markerSize = 3;

    /**
     * by #rat
     *
     * @var string
     */
    protected $axisLabelValueColor  = '000000';

    /**
     * by #rat
     *
     * @var int
     */
    protected $axisLabelValueSize  = 900;

    /**
     * by #rat
     *
     * @var string
     */
    protected $axisLabelCategoryColor  = '000000';

    /**
     * by #rat
     *
     * @var int
     */
    protected $axisLabelCategorySize  = 900;

    /**
     * by #rat
     *
     * @var string
     */
    protected $lineWidth  = 12700;

    /**
     * by #rat
     *
     * @var string
     */
    protected $textLegendColor  = '4A515D';

    /**
     * by #rat
     *
     * @var int
     */
    protected $textLegendSize  = 1000;

    /**
     * by #rat
     *
     * @var string
     */
    protected $showMarker = true;

    /**
     * by #rat
     *
     * @var string
     */
    protected $lineGradient = false;

    /**
     * by #rat
     *
     * @var bool
     */
    protected $isDate = false;

    /**
     * by #rat
     *
     * @var bool
     */
    protected $isAlongLength = false;

    /**
     * by #rat
     *
     * @var int | null
     */
    protected $lineChartGapWidth;

    /**
     * @var int
     */
    protected $valSize = 900;

    /**
     * @var string
     */
    protected $valColor = 'bg1';

    /**
     * if you want to change the position of a value on the chart,
     * you must add the position value to the array instead of " true"
     * ["inBase", false, "inEnd"], [true, false, true]
     *
     * @var array | null
     */
    protected $showValList = null;

    /**
     * percent, date, time
     * @var string | null
     */
    protected $format = null;

    /**
     * @var string | null
     */
    protected $language = null;

    /**
     * @var bool
     */
    protected $catFormat = false;

    /**
     * @var bool
     */
    protected $valFormat = false;

    /**
     * @var bool
     */
    protected $xValFormat = false;

    /**
     * @var bool
     */
    protected $yValFormat = false;

    /**
     * @var array
     */
    private $formatPattern = [
        'number' => ['#\ ##0', '#\ ##0'],
        'text' => ['@','@'],
        'percent' =>['0%', '0%'],
        'date' =>['[$-419]d\ mmm;@', '[$-409]d\ mmm;@'],
        'time' =>['h:mm;@', 'h:mm;@'],
        '5_min' => ['h:mm;@', 'h:mm;@'],
        '30_min' => ['h:mm;@', 'h:mm;@'],
        'hour' => ['[$-419]h:mm;@', '[$-409]h:mm;@'],
        'day' => ['[$-419]d\ mmm;@', '[$-409]d\ mmm;@'],
        'week' => ['[$-419]d\ mmm;@', '[$-409]d\ mmm;@'],
        'month' => ['[$-419]mmmm;@', '[$-409]mmmm;@'],
        'quarter' => ['[$-419]mmmm;@', '[$-409]mmmm;@'],
        'year' =>['[$-ru-RU]YYYY;@', '[$-en-EN]YYYY;@']
    ];

    /**
     * @var int | null
     */
    protected $axisValMax = null;

    /**
     * @var int | null
     */
    protected $axisValMin = null;

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
     * @return ChartManualLayout | null
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
     * checked size schema separator
     *
     * @return int
     */
    public function getSchemaSeparatorSize(): int
    {
        return $this->schemaSeparatorSize;
    }

    /**
     * by #rat
     * add size schema separator
     *
     * @param int $schemaSeparatorSize
     * @return self
     */
    public function setSchemaSeparatorSize(int $schemaSeparatorSize): self
    {
        $this->schemaSeparatorSize = $schemaSeparatorSize;

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

    /**
     * by #rat
     *
     * @return string
     */
    public  function getElementSeparatorColor(): string
    {
        return $this->elementSeparatorColor;
    }

    /**
     * by #rat
     *
     * @param string  $elementSeparatorColor
     * @return self
     */
    public  function setElementSeparatorColor(string $elementSeparatorColor): self
    {
        $this->elementSeparatorColor = $elementSeparatorColor;

        return $this;
    }

    /**
     * by #rat
     *
     * @return string
     */
    public function getMarkerShape(): string
    {
        return $this->markerShape;
    }

    /**
     * by #rat
     *
     * @param string  $markerShape
     * @return self
     */
    public function setMarkerShape(string $markerShape): self
    {
        $this->markerShape = $markerShape;

        return $this;
    }

    /**
     * by #rat
     *
     * @return string
     */
    public function getMarkerColor(): string
    {
        return $this->markerColor;
    }

    /**
     * by #rat
     *
     * @param string $markerColor
     * @return self
     */
    public function setMarkerColor(string $markerColor): self
    {
        $this->markerColor = $markerColor;

        return $this;
    }

    /**
     * by #rat
     *
     * @return int
     */
    public function getMarkerSize(): int
    {
        return $this->markerSize;
    }

    /**
     * by #rat
     *
     * @param int $markerSize
     * @return self
     */
    public function setMarkerSize(int $markerSize = 3): self
    {
        $this->markerSize = $markerSize;

        return $this;
    }

    /**
     * by #rat
     *
     * @return string
     */
    public function getAxisLabelValueColor(): string
    {
        return $this->axisLabelValueColor;
    }

    /**
     * by #rat
     *
     * @param string $axisLabelValueColor
     * @return self
     */
    public function setAxisLabelValueColor(string $axisLabelValueColor): self
    {
        $this->axisLabelValueColor = $axisLabelValueColor;

        return $this;
    }

    /**
     * by #rat
     *
     * @return int
     */
    public function getAxisLabelValueSize(): int
    {
        return $this->axisLabelValueSize;
    }

    /**
     * by #rat
     *
     * @param int $axisLabelValueSize
     * @return self
     */
    public function setAxisLabelValueSize(int $axisLabelValueSize): self
    {
        $this->axisLabelValueSize = $axisLabelValueSize;

        return $this;
    }

    /**
     * by #rat
     *
     * @return string
     */
    public function getAxisLabelCategoryColor(): string
    {
        return $this->axisLabelCategoryColor;
    }

    /**
     * by #rat
     *
     * @param string $axisLabelCategoryColor
     * @return self
     */
    public function setAxisLabelCategoryColor(string $axisLabelCategoryColor): self
    {
        $this->axisLabelCategoryColor = $axisLabelCategoryColor;

        return $this;
    }

    /**
     * by #rat
     *
     * @return int
     */
    public function getAxisLabelCategorySize(): int
    {
        return $this->axisLabelCategorySize;
    }

    /**
     * by #rat
     *
     * @param int $axisLabelCategorySize
     * @return self
     */
    public function setAxisLabelCategorySize(int $axisLabelCategorySize): self
    {
        $this->axisLabelCategorySize = $axisLabelCategorySize;

        return $this;
    }

    /**
     * by #rat
     *
     * @return string
     */
    public function getLineWidth(): string
    {
        return $this->lineWidth;
    }

    /**
     * by #rat
     *
     * @param string $lineWidth
     * @return self
     */
    public function setLineWidth(string $lineWidth): self
    {
        $this->lineWidth = $lineWidth;

        return $this;
    }

    /**
     * by #rat
     *
     * @return string
     */
    public function getTextLegendColor(): string
    {
        return $this->textLegendColor;
    }

    /**
     * by #rat
     *
     * @param string $textLegendColor
     * @return self
     */
    public function setTextLegendColor(string $textLegendColor): self
    {
        $this->textLegendColor = $textLegendColor;

        return $this;
    }

    /**
     * by #rat
     *
     * @return int
     */
    public function getTextLegendSize(): int
    {
        return $this->textLegendSize;
    }

    /**
     * by #rat
     *
     * @param int $textLegendSize
     * @return self
     */
    public function setTextLegendSize(int $textLegendSize): self
    {
        $this->textLegendSize = $textLegendSize;

        return $this;
    }

    /**
     * by #rat
     *
     * @return bool
     */
    public function isShowMarker(): bool
    {
        return $this->showMarker;
    }

    /**
     * by #rat
     *
     * @param bool
     * @return self
     */
    public function setShowMarker(bool $showMarker): self
    {
        $this->showMarker = $showMarker;

        return $this;
    }

    /**
     * by #rat
     *
     * @return bool
     */
    public function isLineGradient(): bool
    {
        return $this->lineGradient;
    }

    /**
     * by #rat
     *
     * @param bool
     * @return self
     */
    public function setLineGradient(bool $lineGradient): self
    {
        $this->lineGradient = $lineGradient;

        return $this;
    }

    /**
     * by #rat
     *
     * @return bool
     */
    public function isDate(): bool
    {
        return $this->isDate;
    }

    /**
     * by #rat
     *
     * @param bool
     * @return self
     */
    public function setDate(bool $date): self
    {
        $this->isDate = $date;

        return $this;
    }

    /**
     * by #rat
     *
     * @return bool
     */
    public function isAlongLength(): bool
    {
        return $this->isAlongLength;
    }

    /**
     * by #rat
     *
     * @param bool
     * @return self
     */
    public function setAlongLength(bool $isAlongLength): self
    {
        $this->isAlongLength = $isAlongLength;

        return $this;
    }

    /**
     * by #rat
     *
     * @return bool | null
     */
    public function getLineChartGapWidth()
    {
        return $this->lineChartGapWidth;
    }

    /**
     * by #rat
     *
     * @param int | null
     * @return self
     */
    public function setLineChartGapWidth($lineChartGapWidth): self
    {
        $this->lineChartGapWidth = $lineChartGapWidth;

        return $this;
    }

    /**
     * @return int
     */
    public function getValSize(): int
    {
        return $this->valSize;
    }

    /**
     * @param int $valSize
     *
     * @return self
     */
    public function setValSize(int $valSize): self
    {
        $this->valSize = $valSize;

        return $this;
    }

    /**
     * @return string
     */
    public function getValColor(): string
    {
        return $this->valColor;
    }

    /**
     * @param string $valColor
     *
     * @return self
     */
    public function setValColor(string $valColor): self
    {
        $this->valColor = $valColor;

        return $this;
    }

    /**
     * @return array | null
     */
    public function getShowValList(): ?array
    {
        return $this->showValList;
    }

    /**
     * @param array $showValList
     *
     * @return self
     */
    public function setShowValList(array $showValList): self
    {
        $this->showValList = $showValList;

        return $this;
    }


    /**
     * @return string | null
     */
    public function getFormat(): ?string
    {
        return $this->format;
    }

    /**
     * @return string | null
     */
    public function getFormatPattern(): ?string
    {
        return $this->formatPattern[$this->format][strtolower($this->language) == self::LANG_RU ? 0 : 1];
    }

    /**
     * @param string $format
     *
     * @return self
     */
    public function setFormat(?string $format): self
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @param string $language
     *
     * @return self
     */
    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCatFormat(): bool
    {
        return $this->catFormat;
    }

    /**
     * @param bool $catFormat
     *
     * @return self
     */
    public function setCatFormat(bool $catFormat): self
    {
        $this->catFormat = $catFormat;

        return $this;
    }

    /**
     * @return bool
     */
    public function isValFormat(): bool
    {
        return $this->valFormat;
    }

    /**
     * @param bool $valFormat
     *
     * @return self
     */
    public function setValFormat(bool $valFormat): self
    {
        $this->valFormat = $valFormat;

        return $this;
    }

    /**
     * @return bool
     */
    public function isXValFormat(): bool
    {
        return $this->xValFormat;
    }

    /**
     * @param bool $xValFormat
     *
     * @return self
     */
    public function setXValFormat(bool $xValFormat): self
    {
        $this->xValFormat = $xValFormat;

        return $this;
    }

    /**
     * @return bool
     */
    public function isYValFormat(): bool
    {
        return $this->yValFormat;
    }

    /**
     * @param bool $yValFormat
     *
     * @return self
     */
    public function setYValFormat(bool $yValFormat): self
    {
        $this->yValFormat = $yValFormat;

        return $this;
    }

    /**
     * @param int $axisValMin
     *
     * @return self
     */
    public function setAxisValMin($axisValMin): self
    {
        $this->axisValMin = $axisValMin;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAxisValMin()
    {
        return $this->axisValMin;
    }

    /**
     * @param int $axisValMax
     *
     * @return self
     */
    public function setAxisValMax($axisValMax): self
    {
        $this->axisValMax = $axisValMax;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAxisValMax()
    {
        return $this->axisValMax;
    }

}
