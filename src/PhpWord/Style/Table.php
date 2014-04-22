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
 * Table style
 */
class Table extends AbstractStyle
{
    /**
     * Style for first row
     *
     * @var \PhpOffice\PhpWord\Style\Table
     */
    private $firstRow = null;

    /**
     * Cell margin top
     *
     * @var int
     */
    private $cellMarginTop = null;

    /**
     * Cell margin left
     *
     * @var int
     */
    private $cellMarginLeft = null;

    /**
     * Cell margin right
     *
     * @var int
     */
    private $cellMarginRight = null;

    /**
     * Cell margin bottom
     *
     * @var int
     */
    private $cellMarginBottom = null;

    /**
     * Background color
     *
     * @var string
     */
    private $bgColor;

    /**
     * Border size top
     *
     * @var int
     */
    private $borderTopSize;

    /**
     * Border color
     *
     * @var string top
     */
    private $borderTopColor;

    /**
     * Border size left
     *
     * @var int
     */
    private $borderLeftSize;

    /**
     * Border color left
     *
     * @var string
     */
    private $borderLeftColor;

    /**
     * Border size right
     *
     * @var int
     */
    private $borderRightSize;

    /**
     * Border color right
     *
     * @var string
     */
    private $borderRightColor;

    /**
     * Border size bottom
     *
     * @var int
     */
    private $borderBottomSize;

    /**
     * Border color bottom
     *
     * @var string
     */
    private $borderBottomColor;

    /**
     * Border size inside horizontal
     *
     * @var int
     */
    private $borderInsideHSize;

    /**
     * Border color inside horizontal
     *
     * @var string
     */
    private $borderInsideHColor;

    /**
     * Border size inside vertical
     *
     * @var int
     */
    private $borderInsideVSize;

    /**
     * Border color inside vertical
     *
     * @var string
     */
    private $borderInsideVColor;

    /**
     * Create new table style
     *
     * @param mixed $styleTable
     * @param mixed $styleFirstRow
     */
    public function __construct($styleTable = null, $styleFirstRow = null)
    {
        if (!is_null($styleFirstRow) && is_array($styleFirstRow)) {
            $this->firstRow = clone $this;

            unset($this->firstRow->firstRow);
            unset($this->firstRow->cellMarginBottom);
            unset($this->firstRow->cellMarginTop);
            unset($this->firstRow->cellMarginLeft);
            unset($this->firstRow->cellMarginRight);
            unset($this->firstRow->borderInsideVColor);
            unset($this->firstRow->borderInsideVSize);
            unset($this->firstRow->borderInsideHColor);
            unset($this->firstRow->borderInsideHSize);
            foreach ($styleFirstRow as $key => $value) {
                $this->firstRow->setStyleValue($key, $value);
            }
        }

        if (!is_null($styleTable) && is_array($styleTable)) {
            foreach ($styleTable as $key => $value) {
                $this->setStyleValue($key, $value);
            }
        }
    }

    /**
     * Set style value
     *
     * @param string $key
     * @param mixed $value
     */
    public function setStyleValue($key, $value)
    {
        $key = String::removeUnderscorePrefix($key);
        if ($key == 'borderSize') {
            $this->setBorderSize($value);
        } elseif ($key == 'borderColor') {
            $this->setBorderColor($value);
        } elseif ($key == 'cellMargin') {
            $this->setCellMargin($value);
        } else {
            $this->$key = $value;
        }
    }

    /**
     * Get First Row Style
     *
     * @return \PhpOffice\PhpWord\Style\Table
     */
    public function getFirstRow()
    {
        return $this->firstRow;
    }

    /**
     * Get background
     *
     * @return string
     */
    public function getBgColor()
    {
        return $this->bgColor;
    }

    /**
     * Set background
     *
     * @param string $pValue
     * @return \PhpOffice\PhpWord\Style\Table
     */
    public function setBgColor($pValue = null)
    {
        $this->bgColor = $pValue;
    }

    /**
     * Set TLRBVH Border Size
     *
     * @param int $pValue Border size in eighths of a point (1/8 point)
     */
    public function setBorderSize($pValue = null)
    {
        $this->borderTopSize = $pValue;
        $this->borderLeftSize = $pValue;
        $this->borderRightSize = $pValue;
        $this->borderBottomSize = $pValue;
        $this->borderInsideHSize = $pValue;
        $this->borderInsideVSize = $pValue;
    }

    /**
     * Get TLRBVH Border Size
     *
     * @return array
     */
    public function getBorderSize()
    {
        $top = $this->getBorderTopSize();
        $left = $this->getBorderLeftSize();
        $right = $this->getBorderRightSize();
        $bottom = $this->getBorderBottomSize();
        $insideH = $this->getBorderInsideHSize();
        $insideV = $this->getBorderInsideVSize();

        return array($top, $left, $right, $bottom, $insideH, $insideV);
    }

    /**
     * Set TLRBVH Border Color
     * @param string $pValue
     */
    public function setBorderColor($pValue = null)
    {
        $this->borderTopColor = $pValue;
        $this->borderLeftColor = $pValue;
        $this->borderRightColor = $pValue;
        $this->borderBottomColor = $pValue;
        $this->borderInsideHColor = $pValue;
        $this->borderInsideVColor = $pValue;
    }

    /**
     * Get TLRB Border Color
     *
     * @return array
     */
    public function getBorderColor()
    {
        $top = $this->getBorderTopColor();
        $left = $this->getBorderLeftColor();
        $right = $this->getBorderRightColor();
        $bottom = $this->getBorderBottomColor();
        $insideH = $this->getBorderInsideHColor();
        $insideV = $this->getBorderInsideVColor();

        return array($top, $left, $right, $bottom, $insideH, $insideV);
    }

    /**
     * Set border size top
     *
     * @param $pValue
     */
    public function setBorderTopSize($pValue = null)
    {
        $this->borderTopSize = $pValue;
    }

    /**
     * Get border size top
     *
     * @return
     */
    public function getBorderTopSize()
    {
        return $this->borderTopSize;
    }

    /**
     * Set border color top
     *
     * @param $pValue
     */
    public function setBorderTopColor($pValue = null)
    {
        $this->borderTopColor = $pValue;
    }

    /**
     * Get border color top
     *
     * @return
     */
    public function getBorderTopColor()
    {
        return $this->borderTopColor;
    }

    /**
     * Set border size left
     *
     * @param $pValue
     */
    public function setBorderLeftSize($pValue = null)
    {
        $this->borderLeftSize = $pValue;
    }

    /**
     * Get border size left
     *
     * @return
     */
    public function getBorderLeftSize()
    {
        return $this->borderLeftSize;
    }

    /**
     * Set border color left
     *
     * @param $pValue
     */
    public function setBorderLeftColor($pValue = null)
    {
        $this->borderLeftColor = $pValue;
    }

    /**
     * Get border color left
     *
     * @return
     */
    public function getBorderLeftColor()
    {
        return $this->borderLeftColor;
    }

    /**
     * Set border size right
     *
     * @param $pValue
     */
    public function setBorderRightSize($pValue = null)
    {
        $this->borderRightSize = $pValue;
    }

    /**
     * Get border size right
     *
     * @return
     */
    public function getBorderRightSize()
    {
        return $this->borderRightSize;
    }

    /**
     * Set border color right
     *
     * @param $pValue
     */
    public function setBorderRightColor($pValue = null)
    {
        $this->borderRightColor = $pValue;
    }

    /**
     * Get border color right
     *
     * @return
     */
    public function getBorderRightColor()
    {
        return $this->borderRightColor;
    }

    /**
     * Set border size bottom
     *
     * @param $pValue
     */
    public function setBorderBottomSize($pValue = null)
    {
        $this->borderBottomSize = $pValue;
    }

    /**
     * Get border size bottom
     *
     * @return
     */
    public function getBorderBottomSize()
    {
        return $this->borderBottomSize;
    }

    /**
     * Set border color bottom
     *
     * @param $pValue
     */
    public function setBorderBottomColor($pValue = null)
    {
        $this->borderBottomColor = $pValue;
    }

    /**
     * Get border color bottom
     *
     * @return
     */
    public function getBorderBottomColor()
    {
        return $this->borderBottomColor;
    }

    /**
     * Set border color inside horizontal
     *
     * @param $pValue
     */
    public function setBorderInsideHColor($pValue = null)
    {
        $this->borderInsideHColor = $pValue;
    }

    /**
     * Get border color inside horizontal
     *
     * @return
     */
    public function getBorderInsideHColor()
    {
        return (isset($this->borderInsideHColor)) ? $this->borderInsideHColor : null;
    }

    /**
     * Set border color inside vertical
     *
     * @param $pValue
     */
    public function setBorderInsideVColor($pValue = null)
    {
        $this->borderInsideVColor = $pValue;
    }

    /**
     * Get border color inside vertical
     *
     * @return
     */
    public function getBorderInsideVColor()
    {
        return (isset($this->borderInsideVColor)) ? $this->borderInsideVColor : null;
    }

    /**
     * Set border size inside horizontal
     *
     * @param $pValue
     */
    public function setBorderInsideHSize($pValue = null)
    {
        $this->borderInsideHSize = $pValue;
    }

    /**
     * Get border size inside horizontal
     *
     * @return
     */
    public function getBorderInsideHSize()
    {
        return (isset($this->borderInsideHSize)) ? $this->borderInsideHSize : null;
    }

    /**
     * Set border size inside vertical
     *
     * @param $pValue
     */
    public function setBorderInsideVSize($pValue = null)
    {
        $this->borderInsideVSize = $pValue;
    }

    /**
     * Get border size inside vertical
     *
     * @return
     */
    public function getBorderInsideVSize()
    {
        return (isset($this->borderInsideVSize)) ? $this->borderInsideVSize : null;
    }

    /**
     * Set cell margin top
     *
     * @param int $pValue
     */
    public function setCellMarginTop($pValue = null)
    {
        $this->cellMarginTop = $pValue;
    }

    /**
     * Get cell margin top
     *
     * @return int
     */
    public function getCellMarginTop()
    {
        return $this->cellMarginTop;
    }

    /**
     * Set cell margin left
     *
     * @param int $pValue
     */
    public function setCellMarginLeft($pValue = null)
    {
        $this->cellMarginLeft = $pValue;
    }

    /**
     * Get cell margin left
     *
     * @return int
     */
    public function getCellMarginLeft()
    {
        return $this->cellMarginLeft;
    }

    /**
     * Set cell margin right
     *
     * @param int $pValue
     */
    public function setCellMarginRight($pValue = null)
    {
        $this->cellMarginRight = $pValue;
    }

    /**
     * Get cell margin right
     *
     * @return int
     */
    public function getCellMarginRight()
    {
        return $this->cellMarginRight;
    }

    /**
     * Set cell margin bottom
     *
     * @param int $pValue
     */
    public function setCellMarginBottom($pValue = null)
    {
        $this->cellMarginBottom = $pValue;
    }

    /**
     * Get cell margin bottom
     *
     * @return int
     */
    public function getCellMarginBottom()
    {
        return $this->cellMarginBottom;
    }

    /**
     * Set TLRB cell margin
     *
     * @param int $pValue Margin in twips
     */
    public function setCellMargin($pValue = null)
    {
        $this->cellMarginTop = $pValue;
        $this->cellMarginLeft = $pValue;
        $this->cellMarginRight = $pValue;
        $this->cellMarginBottom = $pValue;
    }

    /**
     * Get cell margin
     *
     * @return array
     */
    public function getCellMargin()
    {
        return array($this->cellMarginTop, $this->cellMarginLeft, $this->cellMarginRight, $this->cellMarginBottom);
    }
}
