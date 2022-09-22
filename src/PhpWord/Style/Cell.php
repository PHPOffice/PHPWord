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
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\SimpleType\VerticalJc;

/**
 * Table cell style.
 */
class Cell extends Border
{
    //Text direction constants
    /**
     * Left to Right, Top to Bottom.
     */
    const TEXT_DIR_LRTB = 'lrTb';
    /**
     * Top to Bottom, Right to Left.
     */
    const TEXT_DIR_TBRL = 'tbRl';
    /**
     * Bottom to Top, Left to Right.
     */
    const TEXT_DIR_BTLR = 'btLr';
    /**
     * Left to Right, Top to Bottom Rotated.
     */
    const TEXT_DIR_LRTBV = 'lrTbV';
    /**
     * Top to Bottom, Right to Left Rotated.
     */
    const TEXT_DIR_TBRLV = 'tbRlV';
    /**
     * Top to Bottom, Left to Right Rotated.
     */
    const TEXT_DIR_TBLRV = 'tbLrV';

    /**
     * Vertical merge (rowspan) constants.
     *
     * @const string
     */
    const VMERGE_RESTART = 'restart';
    const VMERGE_CONTINUE = 'continue';

    /**
     * Default border color.
     *
     * @const string
     */
    const DEFAULT_BORDER_COLOR = '000000';

    /**
     * Vertical align (top, center, both, bottom).
     *
     * @var string
     */
    private $vAlign;

    /**
     * Text Direction.
     *
     * @var string
     */
    private $textDirection;

    /**
     * colspan.
     *
     * @var int
     */
    private $gridSpan;

    /**
     * rowspan (restart, continue).
     *
     * - restart: Start/restart merged region
     * - continue: Continue merged region
     *
     * @var string
     */
    private $vMerge;

    /**
     * Shading.
     *
     * @var \PhpOffice\PhpWord\Style\Shading
     */
    private $shading;

    /**
     * Width.
     *
     * @var int
     */
    private $width;

    /**
     * Width unit.
     *
     * @var string
     */
    private $unit = TblWidth::TWIP;

    /**
     * Get vertical align.
     *
     * @return string
     */
    public function getVAlign()
    {
        return $this->vAlign;
    }

    /**
     * Set vertical align.
     *
     * @param string $value
     *
     * @return self
     */
    public function setVAlign($value = null)
    {
        VerticalJc::validate($value);
        $this->vAlign = $this->setEnumVal($value, VerticalJc::values(), $this->vAlign);

        return $this;
    }

    /**
     * Get text direction.
     *
     * @return string
     */
    public function getTextDirection()
    {
        return $this->textDirection;
    }

    /**
     * Set text direction.
     *
     * @param string $value
     *
     * @return self
     */
    public function setTextDirection($value = null)
    {
        $enum = [self::TEXT_DIR_BTLR, self::TEXT_DIR_TBRL];
        $this->textDirection = $this->setEnumVal($value, $enum, $this->textDirection);

        return $this;
    }

    /**
     * Get background.
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
     * Set background.
     *
     * @param string $value
     *
     * @return self
     */
    public function setBgColor($value = null)
    {
        return $this->setShading(['fill' => $value]);
    }

    /**
     * Get grid span (colspan).
     *
     * @return int
     */
    public function getGridSpan()
    {
        return $this->gridSpan;
    }

    /**
     * Set grid span (colspan).
     *
     * @param int $value
     *
     * @return self
     */
    public function setGridSpan($value = null)
    {
        $this->gridSpan = $this->setIntVal($value, $this->gridSpan);

        return $this;
    }

    /**
     * Get vertical merge (rowspan).
     *
     * @return string
     */
    public function getVMerge()
    {
        return $this->vMerge;
    }

    /**
     * Set vertical merge (rowspan).
     *
     * @param string $value
     *
     * @return self
     */
    public function setVMerge($value = null)
    {
        $enum = [self::VMERGE_RESTART, self::VMERGE_CONTINUE];
        $this->vMerge = $this->setEnumVal($value, $enum, $this->vMerge);

        return $this;
    }

    /**
     * Get shading.
     *
     * @return \PhpOffice\PhpWord\Style\Shading
     */
    public function getShading()
    {
        return $this->shading;
    }

    /**
     * Set shading.
     *
     * @param mixed $value
     *
     * @return self
     */
    public function setShading($value = null)
    {
        $this->setObjectVal($value, 'Shading', $this->shading);

        return $this;
    }

    /**
     * Get cell width.
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set cell width.
     *
     * @param int $value
     *
     * @return self
     */
    public function setWidth($value)
    {
        $this->setIntVal($value);

        return $this;
    }

    /**
     * Get width unit.
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set width unit.
     *
     * @param string $value
     */
    public function setUnit($value)
    {
        $this->unit = $this->setEnumVal($value, [TblWidth::AUTO, TblWidth::PERCENT, TblWidth::TWIP], TblWidth::TWIP);

        return $this;
    }
}
