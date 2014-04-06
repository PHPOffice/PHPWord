<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Style;

/**
 * Table row style
 */
class Row
{
    /**
     * Repeat table row on every new page
     *
     * @var bool
     */
    private $tblHeader = false;

    /**
     * Table row cannot break across pages
     *
     * @var bool
     */
    private $cantSplit = false;

    /**
     * Table row exact height
     *
     * @var bool
     */
    private $exactHeight = false;

    /**
     * Create a new row style
     */
    public function __construct()
    {
    }

    /**
     * Set style value
     *
     * @param string $key
     * @param mixed $value
     */
    public function setStyleValue($key, $value)
    {
        if (substr($key, 0, 1) == '_') {
            $key = substr($key, 1);
        }
        $this->$key = $value;
    }

    /**
     * Set tblHeader
     *
     * @param boolean $pValue
     * @return $this
     */
    public function setTblHeader($pValue = false)
    {
        if (!is_bool($pValue)) {
            $pValue = false;
        }
        $this->tblHeader = $pValue;
        return $this;
    }

    /**
     * Get tblHeader
     *
     * @return boolean
     */
    public function getTblHeader()
    {
        return $this->tblHeader;
    }

    /**
     * Set cantSplit
     *
     * @param boolean $pValue
     * @return $this
     */
    public function setCantSplit($pValue = false)
    {
        if (!is_bool($pValue)) {
            $pValue = false;
        }
        $this->cantSplit = $pValue;
        return $this;
    }

    /**
     * Get cantSplit
     *
     * @return boolean
     */
    public function getCantSplit()
    {
        return $this->cantSplit;
    }

    /**
     * Set exactHeight
     *
     * @param bool $pValue
     * @return $this
     */
    public function setExactHeight($pValue = false)
    {
        if (!is_bool($pValue)) {
            $pValue = false;
        }
        $this->exactHeight = $pValue;
        return $this;
    }

    /**
     * Get exactHeight
     *
     * @return boolean
     */
    public function getExactHeight()
    {
        return $this->exactHeight;
    }
}
