<?php
declare(strict_types=1);
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

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Style\Lengths\Absolute;
use PhpOffice\PhpWord\Style\Lengths\Length;
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
     * @var \PhpOffice\PhpWord\Element\Row[]
     */
    private $rows = array();

    /**
     * Table width
     *
     * @var Length
     */
    private $width;

    /**
     * Create a new table
     * @param null|mixed $style
     */
    public function __construct($style = null)
    {
        $this->style = $this->setNewStyle(new TableStyle(), $style);
    }

    /**
     * Add a row
     *
     * @param null|mixed $style
     * @return \PhpOffice\PhpWord\Element\Row
     */
    public function addRow(Absolute $height = null, $style = null)
    {
        $row = new Row($height, $style);
        $row->setParentContainer($this);
        $this->rows[] = $row;

        return $row;
    }

    /**
     * Add a cell
     *
     * @param Length $width
     * @param null|mixed $style
     * @return \PhpOffice\PhpWord\Element\Cell
     */
    public function addCell(Length $width = null, $style = null)
    {
        $index = count($this->rows) - 1;
        $row = $this->rows[$index];
        $cell = $row->addCell($width, $style);

        return $cell;
    }

    /**
     * Get all rows
     *
     * @return \PhpOffice\PhpWord\Element\Row[]
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
     * Get table width
     */
    public function getWidth(): Length
    {
        if ($this->width === null) {
            $this->width = new Absolute(null);
        }

        return $this->width;
    }

    /**
     * Set table width.
     *
     * @param Table $width
     */
    public function setWidth(Length $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get column count
     *
     * @return int
     */
    public function countColumns()
    {
        $columnCount = 0;

        $rowCount = count($this->rows);
        for ($i = 0; $i < $rowCount; $i++) {
            /** @var \PhpOffice\PhpWord\Element\Row $row Type hint */
            $row = $this->rows[$i];
            $cellCount = count($row->getCells());
            if ($columnCount < $cellCount) {
                $columnCount = $cellCount;
            }
        }

        return $columnCount;
    }

    /**
     * The first declared cell width for each column
     *
     * @return Absolute[]
     */
    public function findFirstDefinedCellWidths(): array
    {
        $cellWidths = array();

        foreach ($this->rows as $row) {
            $cells = $row->getCells();
            if (count($cells) <= count($cellWidths)) {
                continue;
            }
            $cellWidths = array();
            foreach ($cells as $cell) {
                $cellWidths[] = $cell->getWidth();
            }
        }

        return $cellWidths;
    }
}
