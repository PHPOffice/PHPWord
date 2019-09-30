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
use PhpOffice\PhpWord\Style\Row as RowStyle;

/**
 * Table row element
 *
 * @since 0.8.0
 */
class Row extends AbstractElement
{
    /**
     * Row height
     *
     * @var Absolute
     */
    private $height;

    /**
     * Row style
     *
     * @var \PhpOffice\PhpWord\Style\Row
     */
    private $style;

    /**
     * Row cells
     *
     * @var \PhpOffice\PhpWord\Element\Cell[]
     */
    private $cells = array();

    /**
     * Create a new table row
     *
     * @param Absolute $height
     * @param null|mixed $style
     */
    public function __construct(Absolute $height = null, $style = null)
    {
        $this->height = $height ?? new Absolute(null);
        $this->style = $this->setNewStyle(new RowStyle(), $style, true);
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
        $cell = new Cell($width, $style);
        $cell->setParentContainer($this);
        $this->cells[] = $cell;

        return $cell;
    }

    /**
     * Get all cells
     *
     * @return \PhpOffice\PhpWord\Element\Cell[]
     */
    public function getCells()
    {
        return $this->cells;
    }

    /**
     * Get row style
     *
     * @return \PhpOffice\PhpWord\Style\Row
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Get row height
     */
    public function getHeight(): Absolute
    {
        return $this->height;
    }
}
