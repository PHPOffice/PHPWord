<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\Shared\String;

/**
 * Section settings
 */
class Section extends Border
{
    /**
     * Default Page Size Width
     *
     * @var int
     */
    private $defaultPageSizeW = 11906;

    /**
     * Default Page Size Height
     *
     * @var int
     */
    private $defaultPageSizeH = 16838;

    /**
     * Page Orientation
     *
     * @var string
     */
    private $orientation;

    /**
     * Page Margin Top
     *
     * @var int
     */
    private $marginTop;

    /**
     * Page Margin Left
     *
     * @var int
     */
    private $marginLeft;

    /**
     * Page Margin Right
     *
     * @var int
     */
    private $marginRight;

    /**
     * Page Margin Bottom
     *
     * @var int
     */
    private $marginBottom;

    /**
     * Page Size Width
     *
     * @var int
     */
    private $pageSizeW;

    /**
     * Page Size Height
     *
     * @var int
     */
    private $pageSizeH;

    /**
     * Page Numbering Start
     *
     * @var int
     */
    private $pageNumberingStart;

    /**
     * Header height
     *
     * @var int
     */
    private $headerHeight;

    /**
     * Footer height
     *
     * @var int
     */
    private $footerHeight;

    /**
     * Section columns count
     *
     * @var int
     */
    private $colsNum;

    /**
     * Section spacing between columns
     *
     * @var int
     */
    private $colsSpace;

    /**
     * Section break type
     *
     * Options:
     * - nextPage: Next page section break
     * - nextColumn: Column section break
     * - continuous: Continuous section break
     * - evenPage: Even page section break
     * - oddPage: Odd page section break
     *
     * @var string
     */
    private $breakType;

    /**
     * Create new Section Settings
     */
    public function __construct()
    {
        $this->orientation = null;
        $this->marginTop = 1418;
        $this->marginLeft = 1418;
        $this->marginRight = 1418;
        $this->marginBottom = 1134;
        $this->pageSizeW = $this->defaultPageSizeW;
        $this->pageSizeH = $this->defaultPageSizeH;
        $this->borderTopSize = null;
        $this->borderTopColor = null;
        $this->borderLeftSize = null;
        $this->borderLeftColor = null;
        $this->borderRightSize = null;
        $this->borderRightColor = null;
        $this->borderBottomSize = null;
        $this->borderBottomColor = null;
        $this->headerHeight = 720; // set default header and footer to 720 twips (.5 inches)
        $this->footerHeight = 720;
        $this->colsNum = 1;
        $this->colsSpace = 720;
        $this->breakType = null;
    }

    /**
     * Set Setting Value
     *
     * @param string $key
     * @param string $value
     */
    public function setSettingValue($key, $value)
    {
        $key = String::removeUnderscorePrefix($key);
        if ($key == 'orientation' && $value == 'landscape') {
            $this->setLandscape();
        } elseif ($key == 'orientation' && is_null($value)) {
            $this->setPortrait();
        } elseif ($key == 'borderSize') {
            $this->setBorderSize($value);
        } elseif ($key == 'borderColor') {
            $this->setBorderColor($value);
        } else {
            $method = 'set' . $key;
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * Get Margin Top
     *
     * @return int
     */
    public function getMarginTop()
    {
        return $this->marginTop;
    }

    /**
     * Set Margin Top
     *
     * @param int $pValue
     */
    public function setMarginTop($pValue = '')
    {
        $this->marginTop = $pValue;
        return $this;
    }

    /**
     * Get Margin Left
     *
     * @return int
     */
    public function getMarginLeft()
    {
        return $this->marginLeft;
    }

    /**
     * Set Margin Left
     *
     * @param int $pValue
     */
    public function setMarginLeft($pValue = '')
    {
        $this->marginLeft = $pValue;
        return $this;
    }

    /**
     * Get Margin Right
     *
     * @return int
     */
    public function getMarginRight()
    {
        return $this->marginRight;
    }

    /**
     * Set Margin Right
     *
     * @param int $pValue
     */
    public function setMarginRight($pValue = '')
    {
        $this->marginRight = $pValue;
        return $this;
    }

    /**
     * Get Margin Bottom
     *
     * @return int
     */
    public function getMarginBottom()
    {
        return $this->marginBottom;
    }

    /**
     * Set Margin Bottom
     *
     * @param int $pValue
     */
    public function setMarginBottom($pValue = '')
    {
        $this->marginBottom = $pValue;
        return $this;
    }

    /**
     * Set Landscape Orientation
     */
    public function setLandscape()
    {
        $this->orientation = 'landscape';
        $this->pageSizeW = $this->defaultPageSizeH;
        $this->pageSizeH = $this->defaultPageSizeW;
    }

    /**
     * Set Portrait Orientation
     */
    public function setPortrait()
    {
        $this->orientation = null;
        $this->pageSizeW = $this->defaultPageSizeW;
        $this->pageSizeH = $this->defaultPageSizeH;
    }

    /**
     * Get Page Size Width
     *
     * @return int
     */
    public function getPageSizeW()
    {
        return $this->pageSizeW;
    }

    /**
     * Get Page Size Height
     *
     * @return int
     */
    public function getPageSizeH()
    {
        return $this->pageSizeH;
    }

    /**
     * Get Page Orientation
     *
     * @return string
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * Set page numbering start
     *
     * @param null|int $pageNumberingStart
     * @return $this
     */
    public function setPageNumberingStart($pageNumberingStart = null)
    {
        $this->pageNumberingStart = $pageNumberingStart;
        return $this;
    }

    /**
     * Get page numbering start
     *
     * @return null|int
     */
    public function getPageNumberingStart()
    {
        return $this->pageNumberingStart;
    }

    /**
     * Get Header Height
     *
     * @return int
     */
    public function getHeaderHeight()
    {
        return $this->headerHeight;
    }

    /**
     * Set Header Height
     *
     * @param int $pValue
     */
    public function setHeaderHeight($pValue = '')
    {
        if (!is_numeric($pValue)) {
            $pValue = 720;
        }
        $this->headerHeight = $pValue;
        return $this;
    }

    /**
     * Get Footer Height
     *
     * @return int
     */
    public function getFooterHeight()
    {
        return $this->footerHeight;
    }

    /**
     * Set Footer Height
     *
     * @param int $pValue
     */
    public function setFooterHeight($pValue = '')
    {
        if (!is_numeric($pValue)) {
            $pValue = 720;
        }
        $this->footerHeight = $pValue;
        return $this;
    }

    /**
     * Set Section Columns Count
     *
     * @param int $pValue
     */
    public function setColsNum($pValue = '')
    {
        if (!is_numeric($pValue)) {
            $pValue = 1;
        }
        $this->colsNum = $pValue;
        return $this;
    }

    /**
     * Get Section Columns Count
     *
     * @return int
     */
    public function getColsNum()
    {
        return $this->colsNum;
    }

    /**
     * Set Section Space Between Columns
     *
     * @param int $pValue
     */
    public function setColsSpace($pValue = '')
    {
        if (!is_numeric($pValue)) {
            $pValue = 720;
        }
        $this->colsSpace = $pValue;
        return $this;
    }

    /**
     * Get Section Space Between Columns
     *
     * @return int
     */
    public function getColsSpace()
    {
        return $this->colsSpace;
    }

    /**
     * Set Break Type
     *
     * @param string $pValue
     */
    public function setBreakType($pValue = null)
    {
        $this->breakType = $pValue;
        return $this;
    }

    /**
     * Get Break Type
     *
     * @return string
     */
    public function getBreakType()
    {
        return $this->breakType;
    }
}
