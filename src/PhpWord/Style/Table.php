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
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;

class Table extends Border
{
    /**
     * @const string Table width units http://www.schemacentral.com/sc/ooxml/t-w_ST_TblWidth.html
     */
    const WIDTH_AUTO = 'auto'; // Automatically determined width
    const WIDTH_PERCENT = 'pct'; // Width in fiftieths (1/50) of a percent (1% = 50 unit)
    const WIDTH_TWIP = 'dxa'; // Width in twentieths (1/20) of a point (twip)

    /**
     * Is this a first row style?
     *
     * @var bool
     */
    private $isFirstRow = false;

    /**
     * Style for first row
     *
     * @var \PhpOffice\PhpWord\Style\Table
     */
    private $firstRowStyle;

    /**
     * Cell margin top
     *
     * @var int
     */
    private $cellMarginTop;

    /**
     * Cell margin left
     *
     * @var int
     */
    private $cellMarginLeft;

    /**
     * Cell margin right
     *
     * @var int
     */
    private $cellMarginRight;

    /**
     * Cell margin bottom
     *
     * @var int
     */
    private $cellMarginBottom;

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
     * @var string
     */
    private $alignment = '';

    /**
     * @var int|float Width value
     */
    private $width = 0;

    /**
     * @var string Width unit
     */
    private $unit = self::WIDTH_AUTO;

    /**
     * Create new table style
     *
     * @param mixed $tableStyle
     * @param mixed $firstRowStyle
     */
    public function __construct($tableStyle = null, $firstRowStyle = null)
    {
        // Clone first row from table style, but with certain properties disabled
        if ($firstRowStyle !== null && is_array($firstRowStyle)) {
            $this->firstRowStyle = clone $this;
            $this->firstRowStyle->isFirstRow = true;
            unset($this->firstRowStyle->firstRowStyle);
            unset($this->firstRowStyle->borderInsideHSize);
            unset($this->firstRowStyle->borderInsideHColor);
            unset($this->firstRowStyle->borderInsideVSize);
            unset($this->firstRowStyle->borderInsideVColor);
            unset($this->firstRowStyle->cellMarginTop);
            unset($this->firstRowStyle->cellMarginLeft);
            unset($this->firstRowStyle->cellMarginRight);
            unset($this->firstRowStyle->cellMarginBottom);
            $this->firstRowStyle->setStyleByArray($firstRowStyle);
        }

        if ($tableStyle !== null && is_array($tableStyle)) {
            $this->setStyleByArray($tableStyle);
        }
    }

    /**
     * Set first row
     *
     * @return \PhpOffice\PhpWord\Style\Table
     */
    public function getFirstRow()
    {
        return $this->firstRowStyle;
    }

    /**
     * Get background
     *
     * @return string
     */
    public function getBgColor()
    {
        if ($this->shading !== null) {
            return $this->shading->getFill();
        }

        return null;
    }

    /**
     * Set background
     *
     * @param string $value
     * @return self
     */
    public function setBgColor($value = null)
    {
        $this->setShading(array('fill' => $value));

        return $this;
    }

    /**
     * Get TLRBHV Border Size
     *
     * @return integer[]
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
     * Get border size inside horizontal
     *
     * @return int
     */
    public function getBorderInsideHSize()
    {
        return $this->getTableOnlyProperty('borderInsideHSize');
    }

    /**
     * Set border size inside horizontal
     *
     * @param int $value
     * @return self
     */
    public function setBorderInsideHSize($value = null)
    {
        return $this->setTableOnlyProperty('borderInsideHSize', $value);
    }

    /**
     * Get border color inside horizontal
     *
     * @return string
     */
    public function getBorderInsideHColor()
    {
        return $this->getTableOnlyProperty('borderInsideHColor');
    }

    /**
     * Set border color inside horizontal
     *
     * @param string $value
     * @return self
     */
    public function setBorderInsideHColor($value = null)
    {
        return $this->setTableOnlyProperty('borderInsideHColor', $value, false);
    }

    /**
     * Get border size inside vertical
     *
     * @return int
     */
    public function getBorderInsideVSize()
    {
        return $this->getTableOnlyProperty('borderInsideVSize');
    }

    /**
     * Set border size inside vertical
     *
     * @param int $value
     * @return self
     */
    public function setBorderInsideVSize($value = null)
    {
        return $this->setTableOnlyProperty('borderInsideVSize', $value);
    }

    /**
     * Get border color inside vertical
     *
     * @return string
     */
    public function getBorderInsideVColor()
    {
        return $this->getTableOnlyProperty('borderInsideVColor');
    }

    /**
     * Set border color inside vertical
     *
     * @param string $value
     * @return self
     */
    public function setBorderInsideVColor($value = null)
    {
        return $this->setTableOnlyProperty('borderInsideVColor', $value, false);
    }

    /**
     * Get cell margin top
     *
     * @return int
     */
    public function getCellMarginTop()
    {
        return $this->getTableOnlyProperty('cellMarginTop');
    }

    /**
     * Set cell margin top
     *
     * @param int $value
     * @return self
     */
    public function setCellMarginTop($value = null)
    {
        return $this->setTableOnlyProperty('cellMarginTop', $value);
    }

    /**
     * Get cell margin left
     *
     * @return int
     */
    public function getCellMarginLeft()
    {
        return $this->getTableOnlyProperty('cellMarginLeft');
    }

    /**
     * Set cell margin left
     *
     * @param int $value
     * @return self
     */
    public function setCellMarginLeft($value = null)
    {
        return $this->setTableOnlyProperty('cellMarginLeft', $value);
    }

    /**
     * Get cell margin right
     *
     * @return int
     */
    public function getCellMarginRight()
    {
        return $this->getTableOnlyProperty('cellMarginRight');
    }

    /**
     * Set cell margin right
     *
     * @param int $value
     * @return self
     */
    public function setCellMarginRight($value = null)
    {
        return $this->setTableOnlyProperty('cellMarginRight', $value);
    }

    /**
     * Get cell margin bottom
     *
     * @return int
     */
    public function getCellMarginBottom()
    {
        return $this->getTableOnlyProperty('cellMarginBottom');
    }

    /**
     * Set cell margin bottom
     *
     * @param int $value
     * @return self
     */
    public function setCellMarginBottom($value = null)
    {
        return $this->setTableOnlyProperty('cellMarginBottom', $value);
    }

    /**
     * Get cell margin
     *
     * @return integer[]
     */
    public function getCellMargin()
    {
        return array(
            $this->cellMarginTop,
            $this->cellMarginLeft,
            $this->cellMarginRight,
            $this->cellMarginBottom
        );
    }

    /**
     * Set TLRB cell margin
     *
     * @param int $value Margin in twips
     * @return self
     */
    public function setCellMargin($value = null)
    {
        $this->setCellMarginTop($value);
        $this->setCellMarginLeft($value);
        $this->setCellMarginRight($value);
        $this->setCellMarginBottom($value);

        return $this;
    }

    /**
     * Check if any of the margin is not null
     *
     * @return bool
     */
    public function hasMargin()
    {
        $margins = $this->getCellMargin();

        return $margins !== array_filter($margins, 'is_null');
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
     * @since 0.13.0
     *
     * @return string
     */
    public function getAlignment()
    {
        return $this->alignment;
    }

    /**
     * @since 0.13.0
     *
     * @param string $value
     *
     * @return self
     */
    public function setAlignment($value)
    {
        if (JcTable::getValidator()->isValid($value) || Jc::getValidator()->isValid($value)) {
            $this->alignment = $value;
        }

        return $this;
    }

    /**
     * @deprecated 0.13.0 Use the `getAlignment` method instead.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getAlign()
    {
        return $this->getAlignment();
    }

    /**
     * @deprecated 0.13.0 Use the `setAlignment` method instead.
     *
     * @param string $value
     *
     * @return self
     *
     * @codeCoverageIgnore
     */
    public function setAlign($value = null)
    {
        return $this->setAlignment($value);
    }

    /**
     * Get width
     *
     * @return int|float
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set width
     *
     * @param int|float $value
     * @return self
     */
    public function setWidth($value = null)
    {
        $this->width = $this->setNumericVal($value, $this->width);

        return $this;
    }

    /**
     * Get width unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set width unit
     *
     * @param string $value
     * @return self
     */
    public function setUnit($value = null)
    {
        $enum = array(self::WIDTH_AUTO, self::WIDTH_PERCENT, self::WIDTH_TWIP);
        $this->unit = $this->setEnumVal($value, $enum, $this->unit);

        return $this;
    }

    /**
     * Get table style only property by checking if it's a firstRow
     *
     * This is necessary since firstRow style is cloned from table style but
     * without certain properties activated, e.g. margins
     *
     * @param string $property
     * @return int|string|null
     */
    private function getTableOnlyProperty($property)
    {
        if (false === $this->isFirstRow) {
            return $this->$property;
        }

        return null;
    }

    /**
     * Set table style only property by checking if it's a firstRow
     *
     * This is necessary since firstRow style is cloned from table style but
     * without certain properties activated, e.g. margins
     *
     * @param string $property
     * @param int|string $value
     * @param bool $isNumeric
     * @return self
     */
    private function setTableOnlyProperty($property, $value, $isNumeric = true)
    {
        if (false === $this->isFirstRow) {
            if (true === $isNumeric) {
                $this->$property = $this->setNumericVal($value, $this->$property);
            } else {
                $this->$property = $value;
            }
        }

        return $this;
    }
}
