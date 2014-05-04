<?php

/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */
namespace PhpOffice\PhpWord\Style;

/**
 * Image and memory image style
 */
class Image extends AbstractStyle
{
    /**
     * Wrapping styles
     *
     * @const string
     */
    const WRAPPING_STYLE_INLINE = 'inline';
    const WRAPPING_STYLE_SQUARE = 'square';
    const WRAPPING_STYLE_TIGHT = 'tight';
    const WRAPPING_STYLE_BEHIND = 'behind';
    const WRAPPING_STYLE_INFRONT = 'infront';

    /**
     * Horizontal alignment
     *
     * @const string
     */
    const POSITION_HORIZONTAL_LEFT = 'left';
    const POSITION_HORIZONTAL_CENTER = 'center';
    const POSITION_HORIZONTAL_RIGHT = 'right';

    /**
     * Vertical alignment
     *
     * @const string
     */
    const POSITION_VERTICAL_TOP = 'top';
    const POSITION_VERTICAL_CENTER = 'center';
    const POSITION_VERTICAL_BOTTOM = 'bottom';
    const POSITION_VERTICAL_INSIDE = 'inside';
    const POSITION_VERTICAL_OUTSIDE = 'outside';

    /**
     * Position relative to
     *
     * @const string
     */
    const POSITION_RELATIVE_TO_MARGIN = 'margin';
    const POSITION_RELATIVE_TO_PAGE = 'page';
    const POSITION_RELATIVE_TO_COLUMN = 'column'; // horizontal only
    const POSITION_RELATIVE_TO_CHAR = 'char'; // horizontal only
    const POSITION_RELATIVE_TO_LINE = 'line'; // vertical only
    const POSITION_RELATIVE_TO_LMARGIN = 'left-margin-area'; // horizontal only
    const POSITION_RELATIVE_TO_RMARGIN = 'right-margin-area'; // horizontal only
    const POSITION_RELATIVE_TO_TMARGIN = 'top-margin-area'; // vertical only
    const POSITION_RELATIVE_TO_BMARGIN = 'bottom-margin-area'; // vertical only
    const POSITION_RELATIVE_TO_IMARGIN = 'inner-margin-area';
    const POSITION_RELATIVE_TO_OMARGIN = 'outer-margin-area';

    /**
     * Position type, relative/absolute
     *
     * @const string
     */
    const POSITION_ABSOLUTE = 'absolute';
    const POSITION_RELATIVE = 'relative';

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
     * Positioning type (relative or absolute)
     *
     * @var string
     */
    private $positioning;

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
     * Create new image style
     */
    public function __construct()
    {
        $this->setWrappingStyle(self::WRAPPING_STYLE_INLINE);
        $this->setPosHorizontal(self::POSITION_HORIZONTAL_LEFT);
        $this->setPosHorizontalRel(self::POSITION_RELATIVE_TO_CHAR);
        $this->setPosVertical(self::POSITION_VERTICAL_TOP);
        $this->setPosVerticalRel(self::POSITION_RELATIVE_TO_LINE);
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
     * @param int $value
     */
    public function setWidth($value = null)
    {
        $this->width = $value;
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
     * @param int $value
     */
    public function setHeight($value = null)
    {
        $this->height = $value;
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
     * @param string $value
     */
    public function setAlign($value = null)
    {
        $this->align = $value;
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
     * @param int $value
     * @return self
     */
    public function setMarginTop($value = null)
    {
        $this->marginTop = $value;
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
     * @param int $value
     * @return self
     */
    public function setMarginLeft($value = null)
    {
        $this->marginLeft = $value;
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
     * Set wrapping style
     *
     * @param string $wrappingStyle
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setWrappingStyle($wrappingStyle)
    {
        $enum = array(self::WRAPPING_STYLE_INLINE, self::WRAPPING_STYLE_INFRONT, self::WRAPPING_STYLE_BEHIND,
            self::WRAPPING_STYLE_SQUARE, self::WRAPPING_STYLE_TIGHT);

        if (in_array($wrappingStyle, $enum)) {
            $this->wrappingStyle = $wrappingStyle;
        } else {
            throw new \InvalidArgumentException('Invalid wrapping style.');
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
     * Set positioning type
     *
     * @param string $positioning
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setPositioning($positioning)
    {
        $enum = array(self::POSITION_RELATIVE, self::POSITION_ABSOLUTE);

        if (in_array($positioning, $enum)) {
            $this->positioning = $positioning;
        } else {
            throw new \InvalidArgumentException('Invalid positioning.');
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
     * Set horizontal alignment
     *
     * @param string $alignment
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setPosHorizontal($alignment)
    {
        $enum = array(self::POSITION_HORIZONTAL_LEFT, self::POSITION_HORIZONTAL_CENTER,
            self::POSITION_HORIZONTAL_RIGHT);

        if (in_array($alignment, $enum)) {
            $this->posHorizontal = $alignment;
        } else {
            throw new \InvalidArgumentException('Invalid horizontal alignment.');
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
     * Set vertical alignment
     *
     * @param string $alignment
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setPosVertical($alignment)
    {
        $enum = array(self::POSITION_VERTICAL_TOP, self::POSITION_VERTICAL_CENTER,
            self::POSITION_VERTICAL_BOTTOM, self::POSITION_VERTICAL_INSIDE, self::POSITION_VERTICAL_OUTSIDE);

        if (in_array($alignment, $enum)) {
            $this->posVertical = $alignment;
        } else {
            throw new \InvalidArgumentException('Invalid vertical alignment.');
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
     * Set horizontal relation
     *
     * @param string $relto
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setPosHorizontalRel($relto)
    {
        $enum = array(self::POSITION_RELATIVE_TO_MARGIN, self::POSITION_RELATIVE_TO_PAGE,
            self::POSITION_RELATIVE_TO_COLUMN, self::POSITION_RELATIVE_TO_CHAR,
            self::POSITION_RELATIVE_TO_LMARGIN, self::POSITION_RELATIVE_TO_RMARGIN,
            self::POSITION_RELATIVE_TO_IMARGIN, self::POSITION_RELATIVE_TO_OMARGIN);

        if (in_array($relto, $enum)) {
            $this->posHorizontalRel = $relto;
        } else {
            throw new \InvalidArgumentException('Invalid relative horizontal alignment.');
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

    /**
     * Set vertical relation
     *
     * @param string $relto
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setPosVerticalRel($relto)
    {
        $enum = array(self::POSITION_RELATIVE_TO_MARGIN, self::POSITION_RELATIVE_TO_PAGE,
            self::POSITION_RELATIVE_TO_LINE,
            self::POSITION_RELATIVE_TO_TMARGIN, self::POSITION_RELATIVE_TO_BMARGIN,
            self::POSITION_RELATIVE_TO_IMARGIN, self::POSITION_RELATIVE_TO_OMARGIN);

        if (in_array($relto, $enum)) {
            $this->posVerticalRel = $relto;
        } else {
            throw new \InvalidArgumentException('Invalid relative vertical alignment.');
        }

        return $this;
    }
}
