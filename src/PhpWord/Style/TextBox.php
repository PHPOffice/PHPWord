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

/**
 * TextBox style.
 *
 * @since 0.11.0
 */
class TextBox extends Image
{
    /**
     * margin top.
     *
     * @var int
     */
    private $innerMarginTop;

    /**
     * margin left.
     *
     * @var int
     */
    private $innerMarginLeft;

    /**
     * margin right.
     *
     * @var int
     */
    private $innerMarginRight;

    /**
     * Cell margin bottom.
     *
     * @var int
     */
    private $innerMarginBottom;

    /**
     * border size.
     *
     * @var int
     */
    private $borderSize;

    /**
     * border color.
     *
     * @var string
     */
    private $borderColor;

    /**
     * Set margin top.
     *
     * @param int $value
     */
    public function setInnerMarginTop($value = null): void
    {
        $this->innerMarginTop = $value;
    }

    /**
     * Get margin top.
     *
     * @return int
     */
    public function getInnerMarginTop()
    {
        return $this->innerMarginTop;
    }

    /**
     * Set margin left.
     *
     * @param int $value
     */
    public function setInnerMarginLeft($value = null): void
    {
        $this->innerMarginLeft = $value;
    }

    /**
     * Get margin left.
     *
     * @return int
     */
    public function getInnerMarginLeft()
    {
        return $this->innerMarginLeft;
    }

    /**
     * Set margin right.
     *
     * @param int $value
     */
    public function setInnerMarginRight($value = null): void
    {
        $this->innerMarginRight = $value;
    }

    /**
     * Get margin right.
     *
     * @return int
     */
    public function getInnerMarginRight()
    {
        return $this->innerMarginRight;
    }

    /**
     * Set margin bottom.
     *
     * @param int $value
     */
    public function setInnerMarginBottom($value = null): void
    {
        $this->innerMarginBottom = $value;
    }

    /**
     * Get margin bottom.
     *
     * @return int
     */
    public function getInnerMarginBottom()
    {
        return $this->innerMarginBottom;
    }

    /**
     * Set TLRB cell margin.
     *
     * @param int $value Margin in twips
     */
    public function setInnerMargin($value = null): void
    {
        $this->setInnerMarginTop($value);
        $this->setInnerMarginLeft($value);
        $this->setInnerMarginRight($value);
        $this->setInnerMarginBottom($value);
    }

    /**
     * Get cell margin.
     *
     * @return int[]
     */
    public function getInnerMargin()
    {
        return [$this->innerMarginLeft, $this->innerMarginTop, $this->innerMarginRight, $this->innerMarginBottom];
    }

    /**
     * Has inner margin?
     *
     * @return bool
     */
    public function hasInnerMargins()
    {
        $hasInnerMargins = false;
        $margins = $this->getInnerMargin();
        $numMargins = count($margins);
        for ($i = 0; $i < $numMargins; ++$i) {
            if ($margins[$i] !== null) {
                $hasInnerMargins = true;
            }
        }

        return $hasInnerMargins;
    }

    /**
     * Set border size.
     *
     * @param int $value Size in points
     */
    public function setBorderSize($value = null): void
    {
        $this->borderSize = $value;
    }

    /**
     * Get border size.
     *
     * @return int
     */
    public function getBorderSize()
    {
        return $this->borderSize;
    }

    /**
     * Set border color.
     *
     * @param string $value
     */
    public function setBorderColor($value = null): void
    {
        $this->borderColor = $value;
    }

    /**
     * Get border color.
     *
     * @return string
     */
    public function getBorderColor()
    {
        return $this->borderColor;
    }
}
