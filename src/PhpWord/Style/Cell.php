<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\Shared\String;

/**
 * Table cell style
 */
class Cell extends AbstractStyle
{
    const TEXT_DIR_BTLR = 'btLr';
    const TEXT_DIR_TBRL = 'tbRl';

    /**
     * Vertical align (top, center, both, bottom)
     *
     * @var string
     */
    private $valign;

    /**
     * Text Direction
     *
     * @var string
     */
    private $textDirection;

    /**
     * Background-Color
     *
     * @var string
     */
    private $bgColor;

    /**
     * Border Top Size
     *
     * @var int
     */
    private $borderTopSize;

    /**
     * Border Top Color
     *
     * @var string
     */
    private $borderTopColor;

    /**
     * Border Left Size
     *
     * @var int
     */
    private $borderLeftSize;

    /**
     * Border Left Color
     *
     * @var string
     */
    private $borderLeftColor;

    /**
     * Border Right Size
     *
     * @var int
     */
    private $borderRightSize;

    /**
     * Border Right Color
     *
     * @var string
     */
    private $borderRightColor;

    /**
     * Border Bottom Size
     *
     * @var int
     */
    private $borderBottomSize;

    /**
     * Border Bottom Color
     *
     * @var string
     */
    private $borderBottomColor;

    /**
     * Border Default Color
     *
     * @var string
     */
    private $defaultBorderColor;

    /**
     * colspan
     *
     * @var integer
     */
    private $gridSpan = null;

    /**
     * rowspan (restart, continue)
     *
     * - restart: Start/restart merged region
     * - continue: Continue merged region
     *
     * @var string
     */
    private $vMerge = null;

    /**
     * Create a new Cell Style
     */
    public function __construct()
    {
        $this->valign = null;
        $this->textDirection = null;
        $this->bgColor = null;
        $this->borderTopSize = null;
        $this->borderTopColor = null;
        $this->borderLeftSize = null;
        $this->borderLeftColor = null;
        $this->borderRightSize = null;
        $this->borderRightColor = null;
        $this->borderBottomSize = null;
        $this->borderBottomColor = null;
        $this->defaultBorderColor = '000000';
    }

    /**
     * Set style value
     *
     * @param string $key
     * @param mixed $value
     */
    public function setStyleValue($key, $value)
    {
        $key = String::removeUnderscorePrefix($key);
        if ($key == 'borderSize') {
            $this->setBorderSize($value);
        } elseif ($key == 'borderColor') {
            $this->setBorderColor($value);
        } else {
            $this->$key = $value;
        }
    }

    /**
     * Get vertical align
     */
    public function getVAlign()
    {
        return $this->valign;
    }

    /**
     * Set vertical align
     *
     * @param string $pValue
     */
    public function setVAlign($pValue = null)
    {
        $this->valign = $pValue;
    }

    /**
     * Get text direction
     */
    public function getTextDirection()
    {
        return $this->textDirection;
    }

    /**
     * Set text direction
     *
     * @param string $pValue
     */
    public function setTextDirection($pValue = null)
    {
        $this->textDirection = $pValue;
    }

    /**
     * Get background color
     */
    public function getBgColor()
    {
        return $this->bgColor;
    }

    /**
     * Set background color
     *
     * @param string $pValue
     */
    public function setBgColor($pValue = null)
    {
        $this->bgColor = $pValue;
    }

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
        $t = $this->getBorderTopSize();
        $l = $this->getBorderLeftSize();
        $r = $this->getBorderRightSize();
        $b = $this->getBorderBottomSize();

        return array($t, $l, $r, $b);
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
        $t = $this->getBorderTopColor();
        $l = $this->getBorderLeftColor();
        $r = $this->getBorderRightColor();
        $b = $this->getBorderBottomColor();

        return array($t, $l, $r, $b);
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

    /**
     * Get default border color
     */
    public function getDefaultBorderColor()
    {
        return $this->defaultBorderColor;
    }

    /**
     * Set grid span (colspan)
     *
     * @param int $pValue
     */
    public function setGridSpan($pValue = null)
    {
        $this->gridSpan = $pValue;
    }

    /**
     * Get grid span (colspan)
     */
    public function getGridSpan()
    {
        return $this->gridSpan;
    }

    /**
     * Set vertical merge (rowspan)
     *
     * @param string $pValue
     */
    public function setVMerge($pValue = null)
    {
        $this->vMerge = $pValue;
    }

    /**
     * Get vertical merge (rowspan)
     */
    public function getVMerge()
    {
        return $this->vMerge;
    }
}
