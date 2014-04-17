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
 *
 * @since 0.8.0
 */
class Row extends AbstractStyle
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
     * Set tblHeader
     *
     * @param boolean $value
     * @return self
     */
    public function setTblHeader($value = false)
    {
        $this->tblHeader = $this->setBoolVal($value, $this->tblHeader);
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
     * @param boolean $value
     * @return self
     */
    public function setCantSplit($value = false)
    {
        $this->cantSplit = $this->setBoolVal($value, $this->cantSplit);
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
     * @param bool $value
     * @return self
     */
    public function setExactHeight($value = false)
    {
        $this->exactHeight = $this->setBoolVal($value, $this->exactHeight);
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
