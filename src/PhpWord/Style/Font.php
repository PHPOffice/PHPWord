<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Exception\InvalidStyleException;
use PhpOffice\PhpWord\Style\Shading;

/**
 * Font style
 */
class Font extends AbstractStyle
{
    /**
     * Underline types
     *
     * @const string
     */
    const UNDERLINE_NONE = 'none';
    const UNDERLINE_DASH = 'dash';
    const UNDERLINE_DASHHEAVY = 'dashHeavy';
    const UNDERLINE_DASHLONG = 'dashLong';
    const UNDERLINE_DASHLONGHEAVY = 'dashLongHeavy';
    const UNDERLINE_DOUBLE = 'dbl';
    const UNDERLINE_DOTHASH = 'dotDash';
    const UNDERLINE_DOTHASHHEAVY = 'dotDashHeavy';
    const UNDERLINE_DOTDOTDASH = 'dotDotDash';
    const UNDERLINE_DOTDOTDASHHEAVY = 'dotDotDashHeavy';
    const UNDERLINE_DOTTED = 'dotted';
    const UNDERLINE_DOTTEDHEAVY = 'dottedHeavy';
    const UNDERLINE_HEAVY = 'heavy';
    const UNDERLINE_SINGLE = 'single';
    const UNDERLINE_WAVY = 'wavy';
    const UNDERLINE_WAVYDOUBLE = 'wavyDbl';
    const UNDERLINE_WAVYHEAVY = 'wavyHeavy';
    const UNDERLINE_WORDS = 'words';

    /**
     * Foreground colors
     *
     * @const string
     */
    const FGCOLOR_YELLOW = 'yellow';
    const FGCOLOR_LIGHTGREEN = 'green';
    const FGCOLOR_CYAN = 'cyan';
    const FGCOLOR_MAGENTA = 'magenta';
    const FGCOLOR_BLUE = 'blue';
    const FGCOLOR_RED = 'red';
    const FGCOLOR_DARKBLUE = 'darkBlue';
    const FGCOLOR_DARKCYAN = 'darkCyan';
    const FGCOLOR_DARKGREEN = 'darkGreen';
    const FGCOLOR_DARKMAGENTA = 'darkMagenta';
    const FGCOLOR_DARKRED = 'darkRed';
    const FGCOLOR_DARKYELLOW = 'darkYellow';
    const FGCOLOR_DARKGRAY = 'darkGray';
    const FGCOLOR_LIGHTGRAY = 'lightGray';
    const FGCOLOR_BLACK = 'black';

    /**
     * Font style type
     *
     * @var string
     */
    private $type;

    /**
     * Paragraph style
     *
     * @var \PhpOffice\PhpWord\Style\Paragraph
     */
    private $paragraphStyle;

    /**
     * Font name
     *
     * @var int|float
     */
    private $name = PhpWord::DEFAULT_FONT_NAME;

    /**
     * Font size
     *
     * @var int|float
     */
    private $size = PhpWord::DEFAULT_FONT_SIZE;

    /**
     * Bold
     *
     * @var bool
     */
    private $bold = false;

    /**
     * Italic
     *
     * @var bool
     */
    private $italic = false;

    /**
     * Superscript
     *
     * @var bool
     */
    private $superScript = false;

    /**
     * Subscript
     *
     * @var bool
     */
    private $subScript = false;

    /**
     * Undeline
     *
     * @var string
     */
    private $underline = self::UNDERLINE_NONE;

    /**
     * Strikethrough
     *
     * @var bool
     */
    private $strikethrough = false;

    /**
     * Double strikethrough
     *
     * @var bool
     */
    private $doubleStrikethrough = false;

    /**
     * Font color
     *
     * @var string
     */
    private $color = PhpWord::DEFAULT_FONT_COLOR;

    /**
     * Foreground/highlight
     *
     * @var string
     */
    private $fgColor = null;

    /**
     * Text line height
     *
     * @var int
     */

    /**
     * Text line height
     *
     * @var int
     */
    private $lineHeight = 1.0;

    /**
     * Font Content Type
     *
     * @var string
     */
    private $hint = PhpWord::DEFAULT_FONT_CONTENT_TYPE;

    /**
     * Small caps
     *
     * @var bool
     * @link http://www.schemacentral.com/sc/ooxml/e-w_smallCaps-1.html
     */
    private $smallCaps = false;

    /**
     * All caps
     *
     * @var bool
     * @link http://www.schemacentral.com/sc/ooxml/e-w_caps-1.html
     */
    private $allCaps = false;

    /**
     * Shading
     *
     * @var \PhpOffice\PhpWord\Style\Shading
     */
    private $shading;

    /**
     * Create new font style
     *
     * @param string $type Type of font
     * @param array $paragraphStyle Paragraph styles definition
     */
    public function __construct($type = 'text', $paragraphStyle = null)
    {
        $this->type = $type;

        if ($paragraphStyle instanceof Paragraph) {
            $this->paragraphStyle = $paragraphStyle;
        } elseif (is_array($paragraphStyle)) {
            $this->paragraphStyle = new Paragraph;
            $this->paragraphStyle->setArrayStyle($paragraphStyle);
        } else {
            $this->paragraphStyle = $paragraphStyle;
        }
    }

    /**
     * Set style using associative array
     *
     * @param array $style
     * @return $this
     */
    public function setArrayStyle(array $style = array())
    {
        foreach ($style as $key => $value) {
            if ($key === 'line-height') {
                $this->setLineHeight($value);
                null;
            }
            $this->setStyleValue($key, $value);
        }

        return $this;
    }

    /**
     * Get font name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set font name
     *
     * @param  string $value
     * @return self
     */
    public function setName($value = PhpWord::DEFAULT_FONT_NAME)
    {
        $this->name = $this->setNonEmptyVal($value, PhpWord::DEFAULT_FONT_NAME);

        return $this;
    }


    /**
     * Get font size
     *
     * @return  int|float
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set font size
     *
     * @param  int|float $value
     * @return self
     */
    public function setSize($value = PhpWord::DEFAULT_FONT_SIZE)
    {
        $this->size = $this->setNumericVal($value, PhpWord::DEFAULT_FONT_SIZE);

        return $this;
    }

    /**
     * Get bold
     *
     * @return bool
     */
    public function getBold()
    {
        return $this->bold;
    }

    /**
     * Set bold
     *
     * @param  bool $value
     * @return self
     */
    public function setBold($value = false)
    {
        $this->bold = $this->setBoolVal($value, $this->bold);

        return $this;
    }

    /**
     * Get italic
     *
     * @return bool
     */
    public function getItalic()
    {
        return $this->italic;
    }

    /**
     * Set italic
     *
     * @param  bool $value
     * @return self
     */
    public function setItalic($value = false)
    {
        $this->italic = $this->setBoolVal($value, $this->italic);

        return $this;
    }

    /**
     * Get superscript
     *
     * @return bool
     */
    public function getSuperScript()
    {
        return $this->superScript;
    }

    /**
     * Set superscript
     *
     * @param  bool $value
     * @return self
     */
    public function setSuperScript($value = false)
    {
        $this->superScript = $this->setBoolVal($value, $this->superScript);
        if ($this->superScript) {
            $this->subScript = false;
        }

        return $this;
    }

    /**
     * Get subscript
     *
     * @return bool
     */
    public function getSubScript()
    {
        return $this->subScript;
    }

    /**
     * Set subscript
     *
     * @param  bool $value
     * @return self
     */
    public function setSubScript($value = false)
    {
        $this->subScript = $this->setBoolVal($value, $this->subScript);
        if ($this->subScript) {
            $this->superScript = false;
        }

        return $this;
    }

    /**
     * Get underline
     *
     * @return string
     */
    public function getUnderline()
    {
        return $this->underline;
    }

    /**
     * Set underline
     *
     * @param  string $value
     * @return self
     */
    public function setUnderline($value = self::UNDERLINE_NONE)
    {
        $this->underline = $this->setNonEmptyVal($value, self::UNDERLINE_NONE);

        return $this;
    }

    /**
     * Get strikethrough
     *
     * @return bool
     */
    public function getStrikethrough()
    {
        return $this->strikethrough;
    }

    /**
     * Set strikethrough
     *
     * @param  bool $value
     * @return self
     */
    public function setStrikethrough($value = false)
    {
        $this->strikethrough = $this->setBoolVal($value, $this->strikethrough);
        if ($this->strikethrough) {
            $this->doubleStrikethrough = false;
        }

        return $this;
    }

    /**
     * Get double strikethrough
     *
     * @return bool
     */
    public function getDoubleStrikethrough()
    {
        return $this->doubleStrikethrough;
    }

    /**
     * Set double strikethrough
     *
     * @param  bool $value
     * @return self
     */
    public function setDoubleStrikethrough($value = false)
    {
        $this->doubleStrikethrough = $this->setBoolVal($value, $this->doubleStrikethrough);
        if ($this->doubleStrikethrough) {
            $this->strikethrough = false;
        }

        return $this;
    }

    /**
     * Get font color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set font color
     *
     * @param  string $value
     * @return self
     */
    public function setColor($value = PhpWord::DEFAULT_FONT_COLOR)
    {
        $this->color = $this->setNonEmptyVal($value, PhpWord::DEFAULT_FONT_COLOR);

        return $this;
    }

    /**
     * Get foreground/highlight color
     *
     * @return string
     */
    public function getFgColor()
    {
        return $this->fgColor;
    }

    /**
     * Set foreground/highlight color
     *
     * @param  string $value
     * @return self
     */
    public function setFgColor($value = null)
    {
        $this->fgColor = $value;

        return $this;
    }

    /**
     * Get background
     *
     * @return string
     */
    public function getBgColor()
    {
        if (!is_null($this->shading)) {
            return $this->shading->getFill();
        }
    }

    /**
     * Set background
     *
     * @param string $value
     * @return \PhpOffice\PhpWord\Style\Table
     */
    public function setBgColor($value = null)
    {
        $this->setShading(array('fill' => $value));
    }

    /**
     * Get style type
     *
     * @return string
     */
    public function getStyleType()
    {
        return $this->type;
    }

    /**
     * Get paragraph style
     *
     * @return \PhpOffice\PhpWord\Style\Paragraph
     */
    public function getParagraphStyle()
    {
        return $this->paragraphStyle;
    }

    /**
     * Set lineheight
     *
     * @param  int|float|string $lineHeight
     * @return $this
     * @throws \PhpOffice\PhpWord\Exception\InvalidStyleException
     */
    public function setLineHeight($lineHeight)
    {
        if (is_string($lineHeight)) {
            $lineHeight = floatval(preg_replace('/[^0-9\.\,]/', '', $lineHeight));
        }

        if ((!is_integer($lineHeight) && !is_float($lineHeight)) || !$lineHeight) {
            throw new InvalidStyleException('Line height must be a valid number');
        }

        $this->lineHeight = $lineHeight;
        $this->getParagraphStyle()->setLineHeight($lineHeight);
        return $this;
    }

    /**
     * Get line height
     *
     * @return int|float
     */
    public function getLineHeight()
    {
        return $this->lineHeight;
    }

    /**
     * Get Font Content Type
     *
     * @return string
     */
    public function getHint()
    {
        return $this->hint;
    }

    /**
     * Set Font Content Type
     *
     * @param  string $value
     * @return self
     */
    public function setHint($value = PhpWord::DEFAULT_FONT_CONTENT_TYPE)
    {
        $this->hint = $this->setNonEmptyVal($value, PhpWord::DEFAULT_FONT_CONTENT_TYPE);

        return $this;
    }

    /**
     * Get small caps
     *
     * @return bool
     */
    public function getSmallCaps()
    {
        return $this->smallCaps;
    }

    /**
     * Set small caps
     *
     * @param  bool $value
     * @return self
     */
    public function setSmallCaps($value = false)
    {
        $this->smallCaps = $this->setBoolVal($value, $this->smallCaps);
        if ($this->smallCaps) {
            $this->allCaps = false;
        }

        return $this;
    }

    /**
     * Get all caps
     *
     * @return bool
     */
    public function getAllCaps()
    {
        return $this->allCaps;
    }

    /**
     * Set all caps
     *
     * @param  bool $value
     * @return self
     */
    public function setAllCaps($value = false)
    {
        $this->allCaps = $this->setBoolVal($value, $this->allCaps);
        if ($this->allCaps) {
            $this->smallCaps = false;
        }

        return $this;
    }

    /**
     * Get shading
     *
     * @return \PhpOffice\PhpWord\Style\Shading
     */
    public function getShading()
    {
        return $this->shading;
    }

    /**
     * Set shading
     *
     * @param array $value
     * @return self
     */
    public function setShading($value = null)
    {
        if (is_array($value)) {
            if (!$this->shading instanceof Shading) {
                $this->shading = new Shading();
            }
            $this->shading->setStyleByArray($value);
        } else {
            $this->shading = null;
        }

        return $this;
    }
}
