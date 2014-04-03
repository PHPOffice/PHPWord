<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Style\Row as RowStyle;

/**
 * Table row element
 */
class Row extends Element
{
    /**
     * Row height
     *
     * @var int
     */
    private $height = null;

    /**
     * Row style
     *
     * @var RowStyle
     */
    private $style;

    /**
     * Row cells
     *
     * @var array
     */
    private $cells = array();

    /**
     * Table holder
     *
     * @var string
     */
    private $docPart;

    /**
     * Section/Header/Footer count
     *
     * @var int
     */
    private $docPartId;


    /**
     * Create a new table row
     *
     * @param string $docPart
     * @param int $docPartId
     * @param int $height
     * @param mixed $style
     */
    public function __construct($docPart, $docPartId, $height = null, $style = null)
    {
        $this->docPart = $docPart;
        $this->docPartId = $docPartId;
        $this->height = $height;
        $this->style = $this->setStyle(new RowStyle(), $style, true);
    }

    /**
     * Add a cell
     *
     * @param int $width
     * @param mixed $style
     */
    public function addCell($width = null, $style = null)
    {
        $cell = new Cell($this->docPart, $this->docPartId, $width, $style);
        $this->cells[] = $cell;
        return $cell;
    }

    /**
     * Get all cells
     *
     * @return array
     */
    public function getCells()
    {
        return $this->cells;
    }

    /**
     * Get row style
     *
     * @return RowStyle
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Get row height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }
}
