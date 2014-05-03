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
    const POSITION_HORIZONTAL_LEFT = 'left';
    const POSITION_HORIZONTAL_CENTER = 'centered';
    const POSITION_HORIZONTAL_RIGHT = 'right';
    const POSITION_VERTICAL_TOP = 'top';
    const POSITION_VERTICAL_CENTER = 'center';
    const POSITION_VERTICAL_BOTTOM = 'bottom';
    const POSITION_VERTICAL_INSIDE = 'inside';
    const POSITION_VERTICAL_OUTSIDE = 'outside';
    const POSITION_HORIZONTAL_RELATIVE_MARGIN = 'margin';
    const POSITION_HORIZONTAL_RELATIVE_PAGE = 'page';
    const POSITION_HORIZONTAL_RELATIVE_COLUMN = 'column';
    const POSITION_HORIZONTAL_RELATIVE_CHAR = 'char';
    const POSITION_HORIZONTAL_RELATIVE_LMARGIN = 'left-margin-area';
    const POSITION_HORIZONTAL_RELATIVE_RMARGIN = 'right-margin-area';
    const POSITION_HORIZONTAL_RELATIVE_IMARGIN = 'inner-margin-area';
    const POSITION_HORIZONTAL_RELATIVE_OMARGIN = 'outer-margin-area';
    const POSITION_VERTICAL_RELATIVE_MARGIN = 'margin';
    const POSITION_VERTICAL_RELATIVE_PAGE = 'page';
    const POSITION_VERTICAL_RELATIVE_LINE = 'line';
    const POSITION_VERTICAL_RELATIVE_TMARGIN = 'top-margin-area';
    const POSITION_VERTICAL_RELATIVE_BMARGIN = 'bottom-margin-area';
    const POSITION_VERTICAL_RELATIVE_IMARGIN = 'inner-margin-area';
    const POSITION_VERTICAL_RELATIVE_OMARGIN = 'outer-margin-area';
    const POSITION_RELATIVE = 'relative';
    const POSITION_ABSOLUTE = 'absolute';
    
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
     * Horizontal alignment
     *
     * @var string
     */
    private $posHorizontal;
    
    /**
     * Horizontal Relation
     *
     * @var string
     */
    private $posHorizontalRel;
    
    /**
     * Vertical alignment
     *
     * @var string
     */
    private $posVertical;
    
    /**
     * Vertical Relation
     *
     * @var string
     */
    private $posVerticalRel;
    
    /**
     * Positioning type (Relative or Absolute)
     *
     * @var string
     */
    private $positioning;
    
    
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
        $this->setPositioning(self::POSITION_RELATIVE);
        $this->setPosHorizontal(self::POSITION_HORIZONTAL_LEFT);
        $this->setPosHorizontalRel(self::POSITION_HORIZONTAL_RELATIVE_CHAR);
        $this->setPosVertical(self::POSITION_VERTICAL_TOP);
        $this->setPosVerticalRel(self::POSITION_VERTICAL_RELATIVE_LINE);
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

    /**
     * Set positioning type
     *
     * @param string $positioning
     * @throws \InvalidArgumentException
     * @return $this
     */
    
    public function setPositioning($positioning)
    {
    	switch ($positioning) {
    		case self::POSITION_RELATIVE:
    		case self::POSITION_ABSOLUTE:
    			$this->positioning = $positioning;
    			break;
    		default:
    			throw new InvalidArgumentException('Positioning does not exists');
    			break;
    	}
    	return $this;
    }
    
    /**
     * Get positioning type
     * 
     * @return string
     */
    public function getPositioning()
    {
    	return $this->positioning;
    }

    /**
     * Set horizontal alignment
     *
     * @param string $alignment
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setPosHorizontal($alignment)
    {
    	switch ($alignment) {
    		case self::POSITION_HORIZONTAL_LEFT:
    		case self::POSITION_HORIZONTAL_CENTER:
    		case self::POSITION_HORIZONTAL_RIGHT:
    			$this->posHorizontal = $alignment;
    			break;
    		default:
    			throw new InvalidArgumentException('Horizontal alignment does not exists');
    			break;
    	}
    	return $this;
    }
    
    /**
     * Get horizontal alignment
     * 
     * @return string
     */
    public function getPosHorizontal()
    {
    	return $this->posHorizontal;
    }

    /**
     * Set vertical alignment
     *
     * @param string $alignment
     * @throws \InvalidArgumentException
     * @return $this
     */
    
    public function setPosVertical($alignment)
    {
    	switch ($alignment) {
    		case self::POSITION_VERTICAL_TOP:
    		case self::POSITION_VERTICAL_CENTER:
    		case self::POSITION_VERTICAL_BOTTOM:
    		case self::POSITION_VERTICAL_INSIDE:
    		case self::POSITION_VERTICAL_OUTSIDE:
    			$this->posVertical = $alignment;
    			break;
    		default:
    			throw new InvalidArgumentException('Vertical alignment does not exists');
    			break;
    	}
    	return $this;
    }
    
    /**
     * Get vertical alignment
     * 
     * @return string
     */
    public function getPosVertical()
    {
    	return $this->posVertical;
    }
    
    /**
     * Set horizontal relation
     *
     * @param string $relto
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setPosHorizontalRel($relto)
    {
    	switch ($relto) {
    		case self::POSITION_HORIZONTAL_RELATIVE_MARGIN:
    		case self::POSITION_HORIZONTAL_RELATIVE_PAGE:
    		case self::POSITION_HORIZONTAL_RELATIVE_COLUMN:
    		case self::POSITION_HORIZONTAL_RELATIVE_CHAR:
    		case self::POSITION_HORIZONTAL_RELATIVE_LMARGIN:
    		case self::POSITION_HORIZONTAL_RELATIVE_RMARGIN:
    		case self::POSITION_HORIZONTAL_RELATIVE_IMARGIN:
    		case self::POSITION_HORIZONTAL_RELATIVE_OMARGIN:
    			$this->posHorizontalRel = $relto;
    			break;
    		default:
    			throw new InvalidArgumentException('Horizontal relation does not exists');
    			break;
    	}
    	return $this;
    }
    
    /**
     * Get horizontal relation
     * 
     * @return string
     */
    public function getPosHorizontalRel()
    {
    	return $this->posHorizontalRel;
    }
    
    /**
     * Set vertical relation
     *
     * @param string $relto
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setPosVerticalRel($relto)
    {
    	switch ($relto) {
    		case self::POSITION_VERTICAL_RELATIVE_MARGIN:
    		case self::POSITION_VERTICAL_RELATIVE_PAGE:
    		case self::POSITION_VERTICAL_RELATIVE_LINE:
    		case self::POSITION_VERTICAL_RELATIVE_TMARGIN:
    		case self::POSITION_VERTICAL_RELATIVE_BMARGIN:
    		case self::POSITION_VERTICAL_RELATIVE_IMARGIN:
    		case self::POSITION_VERTICAL_RELATIVE_OMARGIN:
    			$this->posVerticalRel = $relto;
    			break;
    		default:
    			throw new InvalidArgumentException('Vertical relation does not exists');
    			break;
    	}
    	return $this;
    }
    
    /**
     * Get vertical relation
     * 
     * @return string
     */
    public function getPosVerticalRel()
    {
    	return $this->posVerticalRel;
    }
}
