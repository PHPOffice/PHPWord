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
    const POSITION_RELATIVE_TO_TEXT = 'text'; // vertical only
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
     * @var \PhpOffice\PhpWord\Style\Alignment
     */
    private $alignment;

    /**
     * Margin Top
     *
     * @var int|float
     */
    private $marginTop = 0;

    /**
     * Margin Left
     *
     * @var int|float
     */
    private $marginLeft = 0;

    /**
     * Wrapping style
     *
     * @var string
     */
    private $wrappingStyle = self::WRAPPING_STYLE_INLINE;

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
    private $posHorizontal = self::POSITION_HORIZONTAL_LEFT;

    /**
     * Horizontal Relation
     *
     * @var string
     */
    private $posHorizontalRel = self::POSITION_RELATIVE_TO_CHAR;

    /**
     * Vertical alignment
     *
     * @var string
     */
    private $posVertical = self::POSITION_VERTICAL_TOP;

    /**
     * Vertical Relation
     *
     * @var string
     */
    private $posVerticalRel = self::POSITION_RELATIVE_TO_LINE;

    /**
     * Create new instance
     */
    public function __construct()
    {
        $this->alignment = new Alignment();
    }

    /**
     * Get width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set width
     *
     * @param int $value
     * @return self
     */
    public function setWidth($value = null)
    {
        $this->width = $value;

        return $this;
    }

    /**
     * Get height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set height
     *
     * @param int $value
     * @return self
     */
    public function setHeight($value = null)
    {
        $this->height = $value;

        return $this;
    }

    /**
     * Get alignment
     *
     * @return string
     */
    public function getAlign()
    {
        return $this->alignment->getValue();
    }

    /**
     * Set alignment
     *
     * @param string $value
     * @return self
     */
    public function setAlign($value = null)
    {
        $this->alignment->setValue($value);

        return $this;
    }

    /**
     * Get margin top
     *
     * @return int|float
     */
    public function getMarginTop()
    {
        return $this->marginTop;
    }

    /**
     * Set margin top
     *
     * @ignoreScrutinizerPatch
     * @param int|float $value
     * @return self
     */
    public function setMarginTop($value = 0)
    {
        $this->marginTop = $this->setNumericVal($value, 0);

        return $this;
    }

    /**
     * Get margin left
     *
     * @return int|float
     */
    public function getMarginLeft()
    {
        return $this->marginLeft;
    }

    /**
     * Set margin left
     *
     * @ignoreScrutinizerPatch
     * @param int|float $value
     * @return self
     */
    public function setMarginLeft($value = 0)
    {
        $this->marginLeft = $this->setNumericVal($value, 0);

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
        $enum = array(
            self::WRAPPING_STYLE_INLINE,
            self::WRAPPING_STYLE_INFRONT, self::WRAPPING_STYLE_BEHIND,
            self::WRAPPING_STYLE_SQUARE, self::WRAPPING_STYLE_TIGHT,
        );
        $this->wrappingStyle = $this->setEnumVal($wrappingStyle, $enum, $this->wrappingStyle);

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
        $this->positioning = $this->setEnumVal($positioning, $enum, $this->positioning);

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
        $enum = array(
            self::POSITION_HORIZONTAL_LEFT, self::POSITION_HORIZONTAL_CENTER,
            self::POSITION_HORIZONTAL_RIGHT, self::POSITION_ABSOLUTE
        );
        $this->posHorizontal = $this->setEnumVal($alignment, $enum, $this->posHorizontal);

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
        $enum = array(
            self::POSITION_VERTICAL_TOP, self::POSITION_VERTICAL_CENTER,
            self::POSITION_VERTICAL_BOTTOM, self::POSITION_VERTICAL_INSIDE,
            self::POSITION_VERTICAL_OUTSIDE, self::POSITION_ABSOLUTE
        );
        $this->posVertical = $this->setEnumVal($alignment, $enum, $this->posVertical);

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
        $enum = array(
            self::POSITION_RELATIVE_TO_MARGIN, self::POSITION_RELATIVE_TO_PAGE,
            self::POSITION_RELATIVE_TO_COLUMN, self::POSITION_RELATIVE_TO_CHAR,
            self::POSITION_RELATIVE_TO_LMARGIN, self::POSITION_RELATIVE_TO_RMARGIN,
            self::POSITION_RELATIVE_TO_IMARGIN, self::POSITION_RELATIVE_TO_OMARGIN,
        );
        $this->posHorizontalRel = $this->setEnumVal($relto, $enum, $this->posHorizontalRel);

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
        $enum = array(
            self::POSITION_RELATIVE_TO_MARGIN, self::POSITION_RELATIVE_TO_PAGE,
            self::POSITION_RELATIVE_TO_TEXT, self::POSITION_RELATIVE_TO_LINE,
            self::POSITION_RELATIVE_TO_TMARGIN, self::POSITION_RELATIVE_TO_BMARGIN,
            self::POSITION_RELATIVE_TO_IMARGIN, self::POSITION_RELATIVE_TO_OMARGIN,
        );
        $this->posVerticalRel = $this->setEnumVal($relto, $enum, $this->posVerticalRel);

        return $this;
    }
}
