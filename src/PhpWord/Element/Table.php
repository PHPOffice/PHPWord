<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Element\Table\Row;

/**
 * Table element
 */
class Table
{
    /**
     * Table style
     *
     * @var \PhpOffice\PhpWord\Style\Table
     */
    private $_style;

    /**
     * Table rows
     *
     * @var array
     */
    private $_rows = array();

    /**
     * Table holder
     *
     * @var string
     */
    private $_insideOf = null;

    /**
     * Table holder count
     *
     * @var array
     */
    private $_pCount;

    /**
     * Table width
     *
     * @var int
     */
    private $_width = null;


    /**
     * Create a new table
     *
     * @param string $insideOf
     * @param int $pCount
     * @param mixed $style
     */
    public function __construct($insideOf, $pCount, $style = null)
    {
        $this->_insideOf = $insideOf;
        $this->_pCount = $pCount;

        if (!is_null($style)) {
            if (is_array($style)) {
                $this->_style = new \PhpOffice\PhpWord\Style\Table();

                foreach ($style as $key => $value) {
                    if (substr($key, 0, 1) != '_') {
                        $key = '_' . $key;
                    }
                    $this->_style->setStyleValue($key, $value);
                }
            } else {
                $this->_style = $style;
            }
        }
    }

    /**
     * Add a row
     *
     * @param int $height
     * @param mixed $style
     */
    public function addRow($height = null, $style = null)
    {
        $row = new Row($this->_insideOf, $this->_pCount, $height, $style);
        $this->_rows[] = $row;
        return $row;
    }

    /**
     * Add a cell
     *
     * @param int $width
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Element\Table\Cell
     */
    public function addCell($width = null, $style = null)
    {
        $i = count($this->_rows) - 1;
        $cell = $this->_rows[$i]->addCell($width, $style);
        return $cell;
    }

    /**
     * Get all rows
     *
     * @return array
     */
    public function getRows()
    {
        return $this->_rows;
    }

    /**
     * Get table style
     *
     * @return \PhpOffice\PhpWord\Style\Table
     */
    public function getStyle()
    {
        return $this->_style;
    }

    /**
     * Set table width
     *
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->_width = $width;
    }

    /**
     * Get table width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->_width;
    }
}
