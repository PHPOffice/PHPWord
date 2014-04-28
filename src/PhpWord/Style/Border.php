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
     * @var int
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
     * @var int
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
     * @var int
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
     * @var int
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
     * @param int $pValue
     */
    public function setBorderSize($pValue = null)
    {
        $this->borderTopSize = $pValue;
        $this->borderLeftSize = $pValue;
        $this->borderRightSize = $pValue;
        $this->borderBottomSize = $pValue;
    }

    /**
     * Get border size
     */
    public function getBorderSize()
    {
        $top = $this->getBorderTopSize();
        $left = $this->getBorderLeftSize();
        $right = $this->getBorderRightSize();
        $bottom = $this->getBorderBottomSize();

        return array($top, $left, $right, $bottom);
    }

    /**
     * Set border color
     *
     * @param string $pValue
     */
    public function setBorderColor($pValue = null)
    {
        $this->borderTopColor = $pValue;
        $this->borderLeftColor = $pValue;
        $this->borderRightColor = $pValue;
        $this->borderBottomColor = $pValue;
    }

    /**
     * Get border color
     */
    public function getBorderColor()
    {
        $top = $this->getBorderTopColor();
        $left = $this->getBorderLeftColor();
        $right = $this->getBorderRightColor();
        $bottom = $this->getBorderBottomColor();

        return array($top, $left, $right, $bottom);
    }

    /**
     * Set border top size
     *
     * @param int $pValue
     */
    public function setBorderTopSize($pValue = null)
    {
        $this->borderTopSize = $pValue;
    }

    /**
     * Get border top size
     */
    public function getBorderTopSize()
    {
        return $this->borderTopSize;
    }

    /**
     * Set border top color
     *
     * @param string $pValue
     */
    public function setBorderTopColor($pValue = null)
    {
        $this->borderTopColor = $pValue;
    }

    /**
     * Get border top color
     */
    public function getBorderTopColor()
    {
        return $this->borderTopColor;
    }

    /**
     * Set border left size
     *
     * @param int $pValue
     */
    public function setBorderLeftSize($pValue = null)
    {
        $this->borderLeftSize = $pValue;
    }

    /**
     * Get border left size
     */
    public function getBorderLeftSize()
    {
        return $this->borderLeftSize;
    }

    /**
     * Set border left color
     *
     * @param string $pValue
     */
    public function setBorderLeftColor($pValue = null)
    {
        $this->borderLeftColor = $pValue;
    }

    /**
     * Get border left color
     */
    public function getBorderLeftColor()
    {
        return $this->borderLeftColor;
    }

    /**
     * Set border right size
     *
     * @param int $pValue
     */
    public function setBorderRightSize($pValue = null)
    {
        $this->borderRightSize = $pValue;
    }

    /**
     * Get border right size
     */
    public function getBorderRightSize()
    {
        return $this->borderRightSize;
    }

    /**
     * Set border right color
     *
     * @param string $pValue
     */
    public function setBorderRightColor($pValue = null)
    {
        $this->borderRightColor = $pValue;
    }

    /**
     * Get border right color
     */
    public function getBorderRightColor()
    {
        return $this->borderRightColor;
    }

    /**
     * Set border bottom size
     *
     * @param int $pValue
     */
    public function setBorderBottomSize($pValue = null)
    {
        $this->borderBottomSize = $pValue;
    }

    /**
     * Get border bottom size
     */
    public function getBorderBottomSize()
    {
        return $this->borderBottomSize;
    }

    /**
     * Set border bottom color
     *
     * @param string $pValue
     */
    public function setBorderBottomColor($pValue = null)
    {
        $this->borderBottomColor = $pValue;
    }

    /**
     * Get border bottom color
     */
    public function getBorderBottomColor()
    {
        return $this->borderBottomColor;
    }
}
