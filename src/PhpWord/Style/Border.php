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
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

/**
 * Border style
 */
class Border extends AbstractStyle
{
    /**
     * Border Top Size
     *
     * @var int|float
     */
    protected $borderTopSize;

    /**
     * Border Top Color
     *
     * @var string
     */
    protected $borderTopColor;

    /**
     * Border Top Style
     *
     * @var string
     */
    protected $borderTopStyle;

    /**
     * Border Left Size
     *
     * @var int|float
     */
    protected $borderLeftSize;

    /**
     * Border Left Color
     *
     * @var string
     */
    protected $borderLeftColor;

    /**
     * Border Left Style
     *
     * @var string
     */
    protected $borderLeftStyle;

    /**
     * Border Right Size
     *
     * @var int|float
     */
    protected $borderRightSize;

    /**
     * Border Right Color
     *
     * @var string
     */
    protected $borderRightColor;

    /**
     * Border Right Style
     *
     * @var string
     */
    protected $borderRightStyle;

    /**
     * Border Bottom Size
     *
     * @var int|float
     */
    protected $borderBottomSize;

    /**
     * Border Bottom Color
     *
     * @var string
     */
    protected $borderBottomColor;

    /**
     * Border Bottom Style
     *
     * @var string
     */
    protected $borderBottomStyle;

    /**
     * Get border size
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
        );
    }

    /**
     * Set border size
     *
     * @param int|float $value
     * @return self
     */
    public function setBorderSize($value = null)
    {
        $this->setBorderTopSize($value);
        $this->setBorderLeftSize($value);
        $this->setBorderRightSize($value);
        $this->setBorderBottomSize($value);

        return $this;
    }

    /**
     * Get border color
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
        );
    }

    /**
     * Set border color
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

        return $this;
    }

    /**
     * Get border style
     *
     * @return string[]
     */
    public function getBorderStyle()
    {
        return array(
            $this->getBorderTopStyle(),
            $this->getBorderLeftStyle(),
            $this->getBorderRightStyle(),
            $this->getBorderBottomStyle(),
        );
    }

    /**
     * Set border style
     *
     * @param string $value
     * @return self
     */
    public function setBorderStyle($value = null)
    {
        $this->setBorderTopStyle($value);
        $this->setBorderLeftStyle($value);
        $this->setBorderRightStyle($value);
        $this->setBorderBottomStyle($value);

        return $this;
    }

    /**
     * Get border top size
     *
     * @return int|float
     */
    public function getBorderTopSize()
    {
        return $this->borderTopSize;
    }

    /**
     * Set border top size
     *
     * @param int|float $value
     * @return self
     */
    public function setBorderTopSize($value = null)
    {
        $this->borderTopSize = $this->setNumericVal($value, $this->borderTopSize);

        return $this;
    }

    /**
     * Get border top color
     *
     * @return string
     */
    public function getBorderTopColor()
    {
        return $this->borderTopColor;
    }

    /**
     * Set border top color
     *
     * @param string $value
     * @return self
     */
    public function setBorderTopColor($value = null)
    {
        $this->borderTopColor = $value;

        return $this;
    }

    /**
     * Get border top style
     *
     * @return string
     */
    public function getBorderTopStyle()
    {
        return $this->borderTopStyle;
    }

    /**
     * Set border top Style
     *
     * @param string $value
     * @return self
     */
    public function setBorderTopStyle($value = null)
    {
        $this->borderTopStyle = $value;

        return $this;
    }

    /**
     * Get border left size
     *
     * @return int|float
     */
    public function getBorderLeftSize()
    {
        return $this->borderLeftSize;
    }

    /**
     * Set border left size
     *
     * @param int|float $value
     * @return self
     */
    public function setBorderLeftSize($value = null)
    {
        $this->borderLeftSize = $this->setNumericVal($value, $this->borderLeftSize);

        return $this;
    }

    /**
     * Get border left color
     *
     * @return string
     */
    public function getBorderLeftColor()
    {
        return $this->borderLeftColor;
    }

    /**
     * Set border left color
     *
     * @param string $value
     * @return self
     */
    public function setBorderLeftColor($value = null)
    {
        $this->borderLeftColor = $value;

        return $this;
    }

    /**
     * Get border left style
     *
     * @return string
     */
    public function getBorderLeftStyle()
    {
        return $this->borderLeftStyle;
    }

    /**
     * Set border left style
     *
     * @param string $value
     * @return self
     */
    public function setBorderLeftStyle($value = null)
    {
        $this->borderLeftStyle = $value;

        return $this;
    }

    /**
     * Get border right size
     *
     * @return int|float
     */
    public function getBorderRightSize()
    {
        return $this->borderRightSize;
    }

    /**
     * Set border right size
     *
     * @param int|float $value
     * @return self
     */
    public function setBorderRightSize($value = null)
    {
        $this->borderRightSize = $this->setNumericVal($value, $this->borderRightSize);

        return $this;
    }

    /**
     * Get border right color
     *
     * @return string
     */
    public function getBorderRightColor()
    {
        return $this->borderRightColor;
    }

    /**
     * Set border right color
     *
     * @param string $value
     * @return self
     */
    public function setBorderRightColor($value = null)
    {
        $this->borderRightColor = $value;

        return $this;
    }

    /**
     * Get border right style
     *
     * @return string
     */
    public function getBorderRightStyle()
    {
        return $this->borderRightStyle;
    }

    /**
     * Set border right style
     *
     * @param string $value
     * @return self
     */
    public function setBorderRightStyle($value = null)
    {
        $this->borderRightStyle = $value;

        return $this;
    }

    /**
     * Get border bottom size
     *
     * @return int|float
     */
    public function getBorderBottomSize()
    {
        return $this->borderBottomSize;
    }

    /**
     * Set border bottom size
     *
     * @param int|float $value
     * @return self
     */
    public function setBorderBottomSize($value = null)
    {
        $this->borderBottomSize = $this->setNumericVal($value, $this->borderBottomSize);

        return $this;
    }

    /**
     * Get border bottom color
     *
     * @return string
     */
    public function getBorderBottomColor()
    {
        return $this->borderBottomColor;
    }

    /**
     * Set border bottom color
     *
     * @param string $value
     * @return self
     */
    public function setBorderBottomColor($value = null)
    {
        $this->borderBottomColor = $value;

        return $this;
    }

    /**
     * Get border bottom style
     *
     * @return string
     */
    public function getBorderBottomStyle()
    {
        return $this->borderBottomStyle;
    }

    /**
     * Set border bottom style
     *
     * @param string $value
     * @return self
     */
    public function setBorderBottomStyle($value = null)
    {
        $this->borderBottomStyle = $value;

        return $this;
    }

    /**
     * Check if any of the border is not null
     *
     * @return bool
     */
    public function hasBorder()
    {
        $borders = $this->getBorderSize();

        return $borders !== array_filter($borders, 'is_null');
    }
}
