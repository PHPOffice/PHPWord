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
    private $colors = [];

    /**
     * A string that tells the writer where to write chart labels or to skip
     * "none" - skips writing axis labels (default)
     * "nextTo" - sets labels next to the axis (bar graphs on the left)
     * "low" - labels on the left side of the graph
     * "high" - labels on the right side of the graph
     *
     * @var string
     */
    private $xAxisLabelsPosition = "none";

    /**
     * A string that tells the writer where to write chart labels or to skip
     * "none" - skips writing axis labels (default)
     * "nextTo" - sets labels next to the axis (bar graphs on the bottom)
     * "low" - labels are below the graph
     * "high" - labels above the graph
     *
     * @var string
     */
    private $yAxisLabelsPosition = "none";


    /**
     *
     *
     * @var string
     */
    private $xAxisTitle;

    /**
     *
     * @var string
     */
    private $yAxisTitle;

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
     */
    public function setColors($value = [])
    {
        $this->colors = $value;
    }

    /**
     * Get the xAxis Labels setting
     *
     * @return string
     */
    public function getXAxisLabelPosition()
    {
        return $this->xAxisLabelsPosition;
    }

    /**
     * Set the xAxis Labels setting
     * "none" - skips writing axis labels
     * "nextTo" - sets labels next to the axis (bar graphs on the left)
     * "low" - labels on the left side of the graph
     * "high" - labels on the right side of the graph
     *
     * @return string
     */
    public function setXAxisLabelPosition($label_position)
    {
        $this->xAxisLabelsPosition = $label_position;
    }

    /**
     * Get the yAxis Labels setting
     *
     * @return string
     */
    public function getYAxisLabelPosition()
    {
        return $this->yAxisLabelsPosition;
    }

        /**
     * Set the yAxis Labels setting
     * "none" - skips writing axis labels
     * "nextTo" - sets labels next to the axis (bar graphs on the bottom)
     * "low" - labels are below the graph
     * "high" - labels above the graph
     *
     * @var string
     */
    public function setYAxisLabelPosition($label_position)
    {
        $this->yAxisLabelsPosition = $label_position;
    }

    /**
     * Get the xAxisTitle
     * @return string
     */
    public function getXAxisTitle(){
        return $this->xAxisTitle;
    }

    /**
     * Set the xAxis title for the a chart
     * @var $axis_title string
     */
    public function setXAxisTitle($axis_title)
    {
        $this->xAxisTitle = $axis_title;
    }

    /**
     * Get the yAxisTitle
     * @return string
     */
    public function getYAxisTitle(){
        return $this->yAxisTitle;
    }

    /**
     * Set the yAxis title for the a chart
     * @var $axis_title string
     */
    public function setYAxisTitle($axis_title)
    {
        $this->yAxisTitle = $axis_title;
    }

}
