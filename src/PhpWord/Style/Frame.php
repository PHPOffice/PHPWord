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
 * Frame defines the size and position of an object
 *
 * Width, height, left/hpos, top/vpos, hrel, vrel, wrap, zindex
 *
 * @since 0.12.0
 * @todo Make existing style (image, textbox, etc) use this style
 */
class Frame extends AbstractStyle
{
    /**
     * Width
     *
     * @var int|float
     */
    private $width;

    /**
     * Height
     *
     * @var int|float
     */
    private $height;

    /**
     * Leftmost (horizontal) position
     *
     * @var int|float
     */
    private $left = 0;

    /**
     * Topmost (vertical) position
     *
     * @var int|float
     */
    private $top = 0;

    /**
     * Create a new instance
     *
     * @param array $style
     */
    public function __construct($style = array())
    {
        $this->setStyleByArray($style);
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
        $this->width = $this->setNumericVal($value, null);

        return $this;
    }

    /**
     * Get height
     *
     * @return int|float
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set height
     *
     * @param int|float $value
     * @return self
     */
    public function setHeight($value = null)
    {
        $this->height = $this->setNumericVal($value, null);

        return $this;
    }

    /**
     * Get left
     *
     * @return int|float
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * Set left
     *
     * @param int|float $value
     * @return self
     */
    public function setLeft($value = 0)
    {
        $this->left = $this->setNumericVal($value, 0);

        return $this;
    }

    /**
     * Get topmost position
     *
     * @return int|float
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * Set topmost position
     *
     * @param int|float $value
     * @return self
     */
    public function setTop($value = 0)
    {
        $this->top = $this->setNumericVal($value, 0);

        return $this;
    }
}
