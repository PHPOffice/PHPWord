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
 * @copyright 2010-2017 PHPWord contributors
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 * @see       https://github.com/PHPOffice/PHPWord
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
     * A list of display options for data labels
     *
     * @var array
     */
    private $dataLabelOptions = array(
        "showVal"          => true, // value
        "showCatName"      => true, // category name
        "showLegendKey"    => false, //show the cart legend
        "showSerName"      => false, // series name
        "showPercent"      => false,
        "showLeaderLines"  => false,
        "showBubbleSize"   => false,
    );

    /**
     * A string that tells the writer where to write chart labels or to skip
     * "none" - skips writing axis labels (default)
     * "nextTo" - sets labels next to the axis (bar graphs on the left)
     * "low" - labels on the left side of the graph
     * "high" - labels on the right side of the graph
     *
     * @var string
     */
    private $categoryLabelPosition = "none";

    /**
     * A string that tells the writer where to write chart labels or to skip
     * "none" - skips writing axis labels (default)
     * "nextTo" - sets labels next to the axis (bar graphs on the bottom)
     * "low" - labels are below the graph
     * "high" - labels above the graph
     *
     * @var string
     */
    private $valueLabelPosition = "none";


    /**
     *
     *
     * @var string
     */
    private $categoryAxisTitle;

    /**
     *
     * @var string
     */
    private $valueAxisTitle;

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

        return $this;
    }

    /**
     * get the list of options for data labels
     *
     * @return array
     */
    public function getDataLabelOptions() {
        return $this->dataLabelOptions;
    }

    /**
     * Set values for data label options.
     * This will only change values for options defined in $this->dataLabelOptions, and cannot create new ones.
     *
     * @param array $values [description]
     */
    public function setDataLabelOptions($values = array()) {
        foreach(array_keys($this->dataLabelOptions) as $option) {
            if(isset($values[$option])) {
                $this->dataLabelOptions[$option] = $this->setBoolVal($values[$option], $this->dataLabelOptions[$option]);
            }
        }

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
     * @return string
     */
    public function setCategoryLabelPosition($label_position)
    {
        $this->categoryLabelPosition = $label_position;

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
     * @var string
     */
    public function setValueLabelPosition($label_position)
    {
        $this->valueLabelPosition = $label_position;

        return $this;
    }

    /**
     * Get the categoryAxisTitle
     * @return string
     */
    public function getCategoryAxisTitle(){
        return $this->categoryAxisTitle;
    }

    /**
     * Set the title that appears on the category side of the chart
     * @var $axis_title string
     */
    public function setCategoryAxisTitle($axis_title)
    {
        $this->categoryAxisTitle = $axis_title;

        return $this;
    }

    /**
     * Get the valueAxisTitle
     * @return string
     */
    public function getValueAxisTitle(){
        return $this->valueAxisTitle;
    }

    /**
     * Set the title that appears on the value side of the chart
     * @var $axis_title string
     */
    public function setValueAxisTitle($axis_title)
    {
        $this->valueAxisTitle = $axis_title;

        return $this;
    }

}
