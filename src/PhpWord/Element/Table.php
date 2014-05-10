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

use PhpOffice\PhpWord\Style\Table as TableStyle;

/**
 * Table element
 */
class Table extends AbstractElement
{
    /**
     * Table style
     *
     * @var \PhpOffice\PhpWord\Style\Table
     */
    private $style;

    /**
     * Table rows
     *
     * @var array
     */
    private $rows = array();

    /**
     * Table width
     *
     * @var integer
     */
    private $width = null;


    /**
     * Create a new table
     *
     * @param string $docPart
     * @param integer $docPartId
     * @param mixed $style
     */
    public function __construct($style = null)
    {
        $this->style = $this->setStyle(new TableStyle(), $style);
    }

    /**
     * Add a row
     *
     * @param integer $height
     * @param mixed $style
     */
    public function addRow($height = null, $style = null)
    {
        $row = new Row($this->getDocPart(), $this->getDocPartId(), $height, $style);
        $row->setPhpWord($this->phpWord);
        $this->rows[] = $row;
        return $row;
    }

    /**
     * Add a cell
     *
     * @param integer $width
     * @param mixed $style
     * @return Cell
     */
    public function addCell($width = null, $style = null)
    {
        $index = count($this->rows) - 1;
        $cell = $this->rows[$index]->addCell($width, $style);
        return $cell;
    }

    /**
     * Get all rows
     *
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Get table style
     *
     * @return \PhpOffice\PhpWord\Style\Table
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Set table width
     *
     * @param integer $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Get table width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Get column count
     *
     * @return integer
     */
    public function countColumns()
    {
        $columnCount = 0;
        if (is_array($this->rows)) {
            $rowCount = count($this->rows);
            for ($i = 0; $i < $rowCount; $i++) {
                $cellCount = count($this->rows[$i]->getCells());
                if ($columnCount < $cellCount) {
                    $columnCount = $cellCount;
                }
            }
        }

        return $columnCount;
    }
}
