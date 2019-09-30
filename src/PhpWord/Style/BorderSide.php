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

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Style\Colors\BasicColor;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

/**
 * Border style
 */
class BorderSide
{
    /**
     * @var Absolute
     */
    protected $size;

    /**
     * @var BasicColor
     */
    protected $color;

    /**
     * @var BorderStyle
     */
    protected $style;

    /**
     * @var Absolute
     */
    protected $space;

    /**
     * @var bool
     */
    protected $shadow;

    public function __construct(Absolute $size = null, BasicColor $color = null, BorderStyle $style = null, Absolute $space = null, bool $shadow = false)
    {
        $this->setSize($size ?? new Absolute(0));
        $this->setColor($color ?? new Hex(null));
        $this->setStyle($style ?? new BorderStyle('single'));
        $this->setSpace($space ?? new Absolute(0));
        $this->setShadow($shadow);
    }

    /**
     * Get border size
     */
    public function getSize(): Absolute
    {
        return $this->size;
    }

    /**
     * Set border size
     */
    public function setSize(Absolute $size): self
    {
        if (!$size->isSpecified()) {
            throw new Exception('Size must be specified');
        }

        $this->size = $size;

        return $this;
    }

    /**
     * Get border color
     */
    public function getColor(): BasicColor
    {
        return $this->color;
    }

    /**
     * Set border color
     */
    public function setColor(BasicColor $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get border style
     */
    public function getStyle(): BorderStyle
    {
        return $this->style;
    }

    /**
     * Set border style
     */
    public function setStyle(BorderStyle $style): self
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Get spacing offset
     */
    public function getSpace(): Absolute
    {
        return $this->space;
    }

    /**
     * Set spacing offset
     */
    public function setSpace(Absolute $space): self
    {
        if (!$space->isSpecified()) {
            throw new Exception('Space must be specified');
        }

        $this->space = $space;

        return $this;
    }

    /**
     * Get whether or not the border should have a "shadow"
     */
    public function getShadow(): bool
    {
        return $this->shadow;
    }

    /**
     * Set whether or not the border should have a "shadow"
     */
    public function setShadow(bool $shadow): self
    {
        $this->shadow = $shadow;

        return $this;
    }
}
