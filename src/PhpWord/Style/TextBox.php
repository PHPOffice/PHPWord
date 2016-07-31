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

/**
 * TextBox style
 *
 * @since 0.11.0
 */
class TextBox extends Image
{
    /**
     * margin top
     *
     * @var int
     */
    private $innerMarginTop = null;

    /**
     * margin left
     *
     * @var int
     */
    private $innerMarginLeft = null;

    /**
     * margin right
     *
     * @var int
     */
    private $innerMarginRight = null;

    /**
     * Cell margin bottom
     *
     * @var int
     */
    private $innerMarginBottom = null;

    /**
     * border size
     *
     * @var int
     */
    private $borderSize = null;

    /**
     * border color
     *
     * @var string
     */
    private $borderColor;

    /**
     * Set margin top.
     *
     * @param int $value
     * @return void
     */
    public function setInnerMarginTop($value = null)
    {
        $this->innerMarginTop = $value;
    }

    /**
     * Get margin top
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
     * @return void
     */
    public function setInnerMarginLeft($value = null)
    {
        $this->innerMarginLeft = $value;
    }

    /**
     * Get margin left
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
     * @return void
     */
    public function setInnerMarginRight($value = null)
    {
        $this->innerMarginRight = $value;
    }

    /**
     * Get margin right
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
     * @return void
     */
    public function setInnerMarginBottom($value = null)
    {
        $this->innerMarginBottom = $value;
    }

    /**
     * Get margin bottom
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
     * @return void
     */
    public function setInnerMargin($value = null)
    {
        $this->setInnerMarginTop($value);
        $this->setInnerMarginLeft($value);
        $this->setInnerMarginRight($value);
        $this->setInnerMarginBottom($value);
    }

    /**
     * Get cell margin
     *
     * @return integer[]
     */
    public function getInnerMargin()
    {
        return array($this->innerMarginLeft, $this->innerMarginTop, $this->innerMarginRight, $this->innerMarginBottom);
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
        for ($i = 0; $i < count($margins); $i++) {
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
     * @return void
     */
    public function setBorderSize($value = null)
    {
        $this->borderSize = $value;
    }

    /**
     * Get border size
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
     * @return void
     */
    public function setBorderColor($value = null)
    {
        $this->borderColor = $value;
    }

    /**
     * Get border color
     *
     * @return string
     */
    public function getBorderColor()
    {
        return $this->borderColor;
    }
}
