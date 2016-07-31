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

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Style\Chart as ChartStyle;

/**
 * Chart element
 *
 * @since 0.12.0
 */
class Chart extends AbstractElement
{
    /**
     * Is part of collection
     *
     * @var bool
     */
    protected $collectionRelation = true;

    /**
     * Type
     *
     * @var string
     */
    private $type = 'pie';

    /**
     * Series
     *
     * @var array
     */
    private $series = array();

    /**
     * Chart style
     *
     * @var \PhpOffice\PhpWord\Style\Chart
     */
    private $style;

    /**
     * Create new instance
     *
     * @param string $type
     * @param array $categories
     * @param array $values
     * @param array $style
     */
    public function __construct($type, $categories, $values, $style = null)
    {
        $this->setType($type);
        $this->addSeries($categories, $values);
        $this->style = $this->setNewStyle(new ChartStyle(), $style, true);
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param string $value
     * @return void
     */
    public function setType($value)
    {
        $enum = array('pie', 'doughnut', 'line', 'bar', 'column', 'area', 'radar', 'scatter');
        $this->type = $this->setEnumVal($value, $enum, 'pie');
    }

    /**
     * Add series
     *
     * @param array $categories
     * @param array $values
     * @return void
     */
    public function addSeries($categories, $values)
    {
        $this->series[] = array('categories' => $categories, 'values' => $values);
    }

    /**
     * Get series
     *
     * @return array
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * Get chart style
     *
     * @return \PhpOffice\PhpWord\Style\Chart
     */
    public function getStyle()
    {
        return $this->style;
    }
}
