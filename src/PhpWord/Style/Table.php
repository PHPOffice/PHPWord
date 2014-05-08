<?php
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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

/**
 * Table style
 */
class Table extends Border
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
     * Shading
     *
     * @var \PhpOffice\PhpWord\Style\Shading
     */
    private $shading;

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
        if (!is_null($this->shading)) {
            return $this->shading->getFill();
        } else {
            return null;
        }
    }

    /**
     * Set background
     *
     * @param string $value
     * @return \PhpOffice\PhpWord\Style\Table
     */
    public function setBgColor($value = null)
    {
        $this->setShading(array('fill' => $value));
    }

    /**
     * Set TLRBHV Border Size
     *
     * @param int $value Border size in eighths of a point (1/8 point)
     * @return self
     */
    public function setBorderSize($value = null)
    {
        $this->setBorderTopSize($value);
        $this->setBorderLeftSize($value);
        $this->setBorderRightSize($value);
        $this->setBorderBottomSize($value);
        $this->setBorderInsideHSize($value);
        $this->setBorderInsideVSize($value);

        return $this;
    }

    /**
     * Get TLRBHV Border Size
     *
     * @return int[]
     */
    public function getBorderSize()
    {
        return array(
            $this->getBorderTopSize(),
            $this->getBorderLeftSize(),
            $this->getBorderRightSize(),
            $this->getBorderBottomSize(),
            $this->getBorderInsideHSize(),
            $this->getBorderInsideVSize(),
        );
    }

    /**
     * Set TLRBHV Border Color
     *
     * @param string $value
     * @return self
     */
    public function setBorderColor($value = null)
    {
        $this->setBorderTopColor($value);
        $this->setBorderLeftColor($value);
        $this->setBorderRightColor($value);
        $this->setBorderBottomColor($value);
        $this->setBorderInsideHColor($value);
        $this->setBorderInsideVColor($value);

        return $this;
    }

    /**
     * Get TLRBHV Border Color
     *
     * @return string[]
     */
    public function getBorderColor()
    {
        return array(
            $this->getBorderTopColor(),
            $this->getBorderLeftColor(),
            $this->getBorderRightColor(),
            $this->getBorderBottomColor(),
            $this->getBorderInsideHColor(),
            $this->getBorderInsideVColor(),
        );
    }

    /**
     * Set border size inside horizontal
     *
     * @param int $value
     */
    public function setBorderInsideHSize($value = null)
    {
        $this->borderInsideHSize = $value;
    }

    /**
     * Get border size inside horizontal
     *
     * @return int
     */
    public function getBorderInsideHSize()
    {
        return (isset($this->borderInsideHSize)) ? $this->borderInsideHSize : null;
    }

    /**
     * Set border size inside vertical
     *
     * @param int $value
     */
    public function setBorderInsideVSize($value = null)
    {
        $this->borderInsideVSize = $value;
    }

    /**
     * Get border size inside vertical
     *
     * @return int
     */
    public function getBorderInsideVSize()
    {
        return (isset($this->borderInsideVSize)) ? $this->borderInsideVSize : null;
    }

    /**
     * Set border color inside horizontal
     *
     * @param string $value
     */
    public function setBorderInsideHColor($value = null)
    {
        $this->borderInsideHColor = $value;
    }

    /**
     * Get border color inside horizontal
     *
     * @return string
     */
    public function getBorderInsideHColor()
    {
        return (isset($this->borderInsideHColor)) ? $this->borderInsideHColor : null;
    }

    /**
     * Set border color inside vertical
     *
     * @param string $value
     */
    public function setBorderInsideVColor($value = null)
    {
        $this->borderInsideVColor = $value;
    }

    /**
     * Get border color inside vertical
     *
     * @return string
     */
    public function getBorderInsideVColor()
    {
        return (isset($this->borderInsideVColor)) ? $this->borderInsideVColor : null;
    }

    /**
     * Set cell margin top
     *
     * @param int $value
     */
    public function setCellMarginTop($value = null)
    {
        $this->cellMarginTop = $value;
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
     * @param int $value
     */
    public function setCellMarginLeft($value = null)
    {
        $this->cellMarginLeft = $value;
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
     * @param int $value
     */
    public function setCellMarginRight($value = null)
    {
        $this->cellMarginRight = $value;
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
     * @param int $value
     */
    public function setCellMarginBottom($value = null)
    {
        $this->cellMarginBottom = $value;
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
     * @param int $value Margin in twips
     */
    public function setCellMargin($value = null)
    {
        $this->setCellMarginTop($value);
        $this->setCellMarginLeft($value);
        $this->setCellMarginRight($value);
        $this->setCellMarginBottom($value);
    }

    /**
     * Get cell margin
     *
     * @return int[]
     */
    public function getCellMargin()
    {
        return array($this->cellMarginTop, $this->cellMarginLeft, $this->cellMarginRight, $this->cellMarginBottom);
    }

    /**
     * Get shading
     *
     * @return \PhpOffice\PhpWord\Style\Shading
     */
    public function getShading()
    {
        return $this->shading;
    }

    /**
     * Set shading
     *
     * @param mixed $value
     * @return self
     */
    public function setShading($value = null)
    {
        $this->setObjectVal($value, 'Shading', $this->shading);

        return $this;
    }

    /**
     * Has margins?
     *
     * @return bool
     */
    public function hasMargins()
    {
        $hasMargins = false;
        $margins = $this->getCellMargin();
        for ($i = 0; $i < count($margins); $i++) {
            if (!is_null($margins[$i])) {
                $hasMargins = true;
            }
        }

        return $hasMargins;
    }
}
