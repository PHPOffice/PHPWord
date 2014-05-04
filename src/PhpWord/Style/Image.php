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
 * Image and memory image style
 */
class Image extends AbstractStyle
{
    const WRAPPING_STYLE_INLINE = 'inline';
    const WRAPPING_STYLE_SQUARE = 'square';
    const WRAPPING_STYLE_TIGHT = 'tight';
    const WRAPPING_STYLE_BEHIND = 'behind';
    const WRAPPING_STYLE_INFRONT = 'infront';

    /**
     * Image width
     *
     * @var int
     */
    private $width;

    /**
     * Image width
     *
     * @var int
     */
    private $height;

    /**
     * Alignment
     *
     * @var string
     */
    private $align;

    /**
     * Margin Top
     *
     * @var int
     */
    private $marginTop;

    /**
     * Margin Left
     *
     * @var int
     */
    private $marginLeft;

    /**
     * Wrapping style
     *
     * @var string
     */
    private $wrappingStyle;

    /**
     * Create new image style
     */
    public function __construct()
    {
        $this->width = null;
        $this->height = null;
        $this->align = null;
        $this->marginTop = null;
        $this->marginLeft = null;
        $this->setWrappingStyle(self::WRAPPING_STYLE_INLINE);
    }

    /**
     * Get width
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set width
     *
     * @param int $pValue
     */
    public function setWidth($pValue = null)
    {
        $this->width = $pValue;
    }

    /**
     * Get height
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set height
     *
     * @param int $pValue
     */
    public function setHeight($pValue = null)
    {
        $this->height = $pValue;
    }

    /**
     * Get alignment
     */
    public function getAlign()
    {
        return $this->align;
    }

    /**
     * Set alignment
     *
     * @param string $pValue
     */
    public function setAlign($pValue = null)
    {
        $this->align = $pValue;
    }

    /**
     * Get Margin Top
     *
     * @return int
     */
    public function getMarginTop()
    {
        return $this->marginTop;
    }

    /**
     * Set Margin Top
     *
     * @param int $pValue
     * @return $this
     */
    public function setMarginTop($pValue = null)
    {
        $this->marginTop = $pValue;
        return $this;
    }

    /**
     * Get Margin Left
     *
     * @return int
     */
    public function getMarginLeft()
    {
        return $this->marginLeft;
    }

    /**
     * Set Margin Left
     *
     * @param int $pValue
     * @return $this
     */
    public function setMarginLeft($pValue = null)
    {
        $this->marginLeft = $pValue;
        return $this;
    }

    /**
     * Set wrapping style
     *
     * @param string $wrappingStyle
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setWrappingStyle($wrappingStyle)
    {
        switch ($wrappingStyle) {
            case self::WRAPPING_STYLE_BEHIND:
            case self::WRAPPING_STYLE_INFRONT:
            case self::WRAPPING_STYLE_INLINE:
            case self::WRAPPING_STYLE_SQUARE:
            case self::WRAPPING_STYLE_TIGHT:
                $this->wrappingStyle = $wrappingStyle;
                break;
            default:
                throw new \InvalidArgumentException('Wrapping style does not exists');
        }
        return $this;
    }

    /**
     * Get wrapping style
     *
     * @return string
     */
    public function getWrappingStyle()
    {
        return $this->wrappingStyle;
    }
}
