<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
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
     * Is tblHeader
     *
     * @return bool
     */
    public function isTblHeader()
    {
        return $this->tblHeader;
    }

    /**
     * Is tblHeader
     *
     * @param bool $value
     * @return self
     */
    public function setTblHeader($value = false)
    {
        $this->tblHeader = $this->setBoolVal($value, $this->tblHeader);
    }

    /**
     * Is cantSplit
     *
     * @return bool
     */
    public function isCantSplit()
    {
        return $this->cantSplit;
    }

    /**
     * Is cantSplit
     *
     * @param bool $value
     * @return self
     */
    public function setCantSplit($value = false)
    {
        $this->cantSplit = $this->setBoolVal($value, $this->cantSplit);
    }

    /**
     * Is exactHeight
     *
     * @return bool
     */
    public function isExactHeight()
    {
        return $this->exactHeight;
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
     * Get tblHeader
     *
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function getTblHeader()
    {
        return $this->isTblHeader();
    }

    /**
     * Get cantSplit
     *
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function getCantSplit()
    {
        return $this->isCantSplit();
    }

    /**
     * Get exactHeight
     *
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function getExactHeight()
    {
        return $this->isExactHeight();
    }
}
