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
 * Section settings
 */
class Section
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
     * Page Border Top Size
     *
     * @var int
     */
    private $borderTopSize;

    /**
     * Page Border Top Color
     *
     * @var int
     */
    private $borderTopColor;

    /**
     * Page Border Left Size
     *
     * @var int
     */
    private $borderLeftSize;

    /**
     * Page Border Left Color
     *
     * @var int
     */
    private $borderLeftColor;

    /**
     * Page Border Right Size
     *
     * @var int
     */
    private $borderRightSize;

    /**
     * Page Border Right Color
     *
     * @var int
     */
    private $borderRightColor;

    /**
     * Page Border Bottom Size
     *
     * @var int
     */
    private $borderBottomSize;

    /**
     * Page Border Bottom Color
     *
     * @var int
     */
    private $borderBottomColor;

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
        if (substr($key, 0, 1) == '_') {
            $key = substr($key, 1);
        }
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
     * Set Border Size
     *
     * @param int $pValue
     */
    public function setBorderSize($pValue = null)
    {
        $this->borderTopSize = $pValue;
        $this->borderLeftSize = $pValue;
        $this->borderRightSize = $pValue;
        $this->borderBottomSize = $pValue;
    }

    /**
     * Get Border Size
     *
     * @return array
     */
    public function getBorderSize()
    {
        $t = $this->getBorderTopSize();
        $l = $this->getBorderLeftSize();
        $r = $this->getBorderRightSize();
        $b = $this->getBorderBottomSize();

        return array($t, $l, $r, $b);
    }

    /**
     * Set Border Color
     *
     * @param string $pValue
     */
    public function setBorderColor($pValue = null)
    {
        $this->borderTopColor = $pValue;
        $this->borderLeftColor = $pValue;
        $this->borderRightColor = $pValue;
        $this->borderBottomColor = $pValue;
    }

    /**
     * Get Border Color
     *
     * @return array
     */
    public function getBorderColor()
    {
        $t = $this->getBorderTopColor();
        $l = $this->getBorderLeftColor();
        $r = $this->getBorderRightColor();
        $b = $this->getBorderBottomColor();

        return array($t, $l, $r, $b);
    }

    /**
     * Set Border Top Size
     *
     * @param int $pValue
     */
    public function setBorderTopSize($pValue = null)
    {
        $this->borderTopSize = $pValue;
    }

    /**
     * Get Border Top Size
     *
     * @return int
     */
    public function getBorderTopSize()
    {
        return $this->borderTopSize;
    }

    /**
     * Set Border Top Color
     *
     * @param string $pValue
     */
    public function setBorderTopColor($pValue = null)
    {
        $this->borderTopColor = $pValue;
    }

    /**
     * Get Border Top Color
     *
     * @return string
     */
    public function getBorderTopColor()
    {
        return $this->borderTopColor;
    }

    /**
     * Set Border Left Size
     *
     * @param int $pValue
     */
    public function setBorderLeftSize($pValue = null)
    {
        $this->borderLeftSize = $pValue;
    }

    /**
     * Get Border Left Size
     *
     * @return int
     */
    public function getBorderLeftSize()
    {
        return $this->borderLeftSize;
    }

    /**
     * Set Border Left Color
     *
     * @param string $pValue
     */
    public function setBorderLeftColor($pValue = null)
    {
        $this->borderLeftColor = $pValue;
    }

    /**
     * Get Border Left Color
     *
     * @return string
     */
    public function getBorderLeftColor()
    {
        return $this->borderLeftColor;
    }

    /**
     * Set Border Right Size
     *
     * @param int $pValue
     */
    public function setBorderRightSize($pValue = null)
    {
        $this->borderRightSize = $pValue;
    }

    /**
     * Get Border Right Size
     *
     * @return int
     */
    public function getBorderRightSize()
    {
        return $this->borderRightSize;
    }

    /**
     * Set Border Right Color
     *
     * @param string $pValue
     */
    public function setBorderRightColor($pValue = null)
    {
        $this->borderRightColor = $pValue;
    }

    /**
     * Get Border Right Color
     *
     * @return string
     */
    public function getBorderRightColor()
    {
        return $this->borderRightColor;
    }

    /**
     * Set Border Bottom Size
     *
     * @param int $pValue
     */
    public function setBorderBottomSize($pValue = null)
    {
        $this->borderBottomSize = $pValue;
    }

    /**
     * Get Border Bottom Size
     *
     * @return int
     */
    public function getBorderBottomSize()
    {
        return $this->borderBottomSize;
    }

    /**
     * Set Border Bottom Color
     *
     * @param string $pValue
     */
    public function setBorderBottomColor($pValue = null)
    {
        $this->borderBottomColor = $pValue;
    }

    /**
     * Get Border Bottom Color
     *
     * @return string
     */
    public function getBorderBottomColor()
    {
        return $this->borderBottomColor;
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
