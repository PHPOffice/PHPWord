<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

/**
 * Table row element
 */
class Row
{
    /**
     * Row height
     *
     * @var int
     */
    private $_height = null;

    /**
     * Row style
     *
     * @var \PhpOffice\PhpWord\Style\Row
     */
    private $_style;

    /**
     * Row cells
     *
     * @var array
     */
    private $_cells = array();

    /**
     * Table holder
     *
     * @var string
     */
    private $_insideOf;

    /**
     * Section/Header/Footer count
     *
     * @var int
     */
    private $_pCount;


    /**
     * Create a new table row
     *
     * @param string $insideOf
     * @param int $pCount
     * @param int $height
     * @param mixed $style
     */
    public function __construct($insideOf, $pCount, $height = null, $style = null)
    {
        $this->_insideOf = $insideOf;
        $this->_pCount = $pCount;
        $this->_height = $height;
        $this->_style = new \PhpOffice\PhpWord\Style\Row();

        if (!is_null($style)) {
            if (is_array($style)) {

                foreach ($style as $key => $value) {
                    if (substr($key, 0, 1) != '_') {
                        $key = '_' . $key;
                    }
                    $this->_style->setStyleValue($key, $value);
                }
            }
        }
    }

    /**
     * Add a cell
     *
     * @param int $width
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Element\Cell
     */
    public function addCell($width = null, $style = null)
    {
        $cell = new Cell($this->_insideOf, $this->_pCount, $width, $style);
        $this->_cells[] = $cell;
        return $cell;
    }

    /**
     * Get all cells
     *
     * @return array
     */
    public function getCells()
    {
        return $this->_cells;
    }

    /**
     * Get row style
     *
     * @return \PhpOffice\PhpWord\Style\Row
     */
    public function getStyle()
    {
        return $this->_style;
    }

    /**
     * Get row height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->_height;
    }
}
