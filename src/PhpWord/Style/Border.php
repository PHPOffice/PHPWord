<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
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
     * Set border top size
     *
     * @param int|float $value
     * @return self
     */
    public function setBorderTopSize($value = null)
    {
        $this->borderTopSize = $value;

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
     * Get border top color
     *
     * @return string
     */
    public function getBorderTopColor()
    {
        return $this->borderTopColor;
    }

    /**
     * Set border left size
     *
     * @param int|float $value
     * @return self
     */
    public function setBorderLeftSize($value = null)
    {
        $this->borderLeftSize = $value;

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
     * Get border left color
     *
     * @return string
     */
    public function getBorderLeftColor()
    {
        return $this->borderLeftColor;
    }

    /**
     * Set border right size
     *
     * @param int|float $value
     * @return self
     */
    public function setBorderRightSize($value = null)
    {
        $this->borderRightSize = $value;

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
     * Get border right color
     *
     * @return string
     */
    public function getBorderRightColor()
    {
        return $this->borderRightColor;
    }

    /**
     * Set border bottom size
     *
     * @param int|float $value
     * @return self
     */
    public function setBorderBottomSize($value = null)
    {
        $this->borderBottomSize = $value;

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
     * Get border bottom color
     *
     * @return string
     */
    public function getBorderBottomColor()
    {
        return $this->borderBottomColor;
    }
}
