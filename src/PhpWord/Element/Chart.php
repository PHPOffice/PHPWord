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
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

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
     * Labels
     *
     * @var array
     */
    private $labels = array();

    /**
     * Data
     *
     * @var array
     */
    private $data = array();

    /**
     * Create new instance
     *
     * @param string $type
     * @param array $labels
     * @param array $data
     */
    public function __construct($type, $labels, $data)
    {
        $this->setType($type);
        $this->setLabels($labels);
        $this->setData($data);
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
     * Set type
     *
     * @param string $value
     */
    public function setType($value)
    {
        $enum = array('pie', 'doughnut', 'line', 'bar', 'area', 'radar', 'scatter');
        $this->type = $this->setEnumVal($value, $enum, 'pie');
    }

    /**
     * Get labels
     *
     * @return array
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * Set labels
     *
     * @param array $value
     */
    public function setLabels($value)
    {
        $this->labels = $value;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set data
     *
     * @param array $value
     */
    public function setData($value)
    {
        $this->data = $value;
    }
}
