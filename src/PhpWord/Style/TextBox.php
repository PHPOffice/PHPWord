<?php
declare(strict_types=1);
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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\Style\Colors\BasicColor;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

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
     * @var Absolute
     */
    private $innerMarginTop = null;

    /**
     * margin left
     *
     * @var Absolute
     */
    private $innerMarginLeft = null;

    /**
     * margin right
     *
     * @var Absolute
     */
    private $innerMarginRight = null;

    /**
     * Cell margin bottom
     *
     * @var Absolute
     */
    private $innerMarginBottom = null;

    /**
     * border size
     *
     * @var Absolute
     */
    private $borderSize = null;

    /**
     * border color
     *
     * @var BasicColor
     */
    private $borderColor;

    /**
     * Set margin top.
     */
    public function setInnerMarginTop(Absolute $value): self
    {
        $this->innerMarginTop = $value;

        return $this;
    }

    /**
     * Get margin top
     */
    public function getInnerMarginTop(): Absolute
    {
        if ($this->innerMarginTop === null) {
            $this->setInnerMarginTop(new Absolute(null));
        }

        return $this->innerMarginTop;
    }

    /**
     * Set margin left.
     */
    public function setInnerMarginLeft(Absolute $value): self
    {
        $this->innerMarginLeft = $value;

        return $this;
    }

    /**
     * Get margin left
     */
    public function getInnerMarginLeft(): Absolute
    {
        if ($this->innerMarginLeft === null) {
            $this->setInnerMarginLeft(new Absolute(null));
        }

        return $this->innerMarginLeft;
    }

    /**
     * Set margin right.
     */
    public function setInnerMarginRight(Absolute $value): self
    {
        $this->innerMarginRight = $value;

        return $this;
    }

    /**
     * Get margin right
     */
    public function getInnerMarginRight(): Absolute
    {
        if ($this->innerMarginRight === null) {
            $this->setInnerMarginRight(new Absolute(null));
        }

        return $this->innerMarginRight;
    }

    /**
     * Set margin bottom.
     */
    public function setInnerMarginBottom(Absolute $value): self
    {
        $this->innerMarginBottom = $value;

        return $this;
    }

    /**
     * Get margin bottom
     */
    public function getInnerMarginBottom(): Absolute
    {
        if ($this->innerMarginBottom === null) {
            $this->setInnerMarginBottom(new Absolute(null));
        }

        return $this->innerMarginBottom;
    }

    /**
     * Set TLRB cell margin.
     *
     * @param Absolute $value Margin
     */
    public function setInnerMargin(Absolute $value): self
    {
        $this->setInnerMarginTop($value);
        $this->setInnerMarginLeft($value);
        $this->setInnerMarginRight($value);
        $this->setInnerMarginBottom($value);

        return $this;
    }

    /**
     * Get cell margin
     *
     * @return Absolute[]
     */
    public function getInnerMargin()
    {
        return array($this->getInnerMarginLeft(), $this->getInnerMarginTop(), $this->getInnerMarginRight(), $this->getInnerMarginBottom());
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
        for ($i = 0; $i < $numMargins; $i++) {
            if ($margins[$i]->toInt('twip') !== null) {
                $hasInnerMargins = true;
            }
        }

        return $hasInnerMargins;
    }

    /**
     * Set border size.
     *
     * @param Absolute $value Size
     */
    public function setBorderSize(Absolute $value): self
    {
        $this->borderSize = $value;

        return $this;
    }

    /**
     * Get border size
     */
    public function getBorderSize(): Absolute
    {
        if ($this->borderSize === null) {
            $this->borderSize = new Absolute(null);
        }

        return $this->borderSize;
    }

    /**
     * Set border color.
     */
    public function setBorderColor(BasicColor $value): self
    {
        $this->borderColor = $value;

        return $this;
    }

    /**
     * Get border color
     */
    public function getBorderColor(): BasicColor
    {
        if ($this->borderColor === null) {
            $this->borderColor = new Hex(null);
        }

        return $this->borderColor;
    }
}
