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

/**
 * Font style
 */
class Font extends AbstractStyle
{
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
     * Background color
     *
     * @var string
     */
    private $bgColor = null;
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
     * @return bool
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set font name
     *
     * @param  string $pValue
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function setName($pValue = PhpWord::DEFAULT_FONT_NAME)
    {
        if (is_null($pValue) || $pValue == '') {
            $pValue = PhpWord::DEFAULT_FONT_NAME;
        }
        $this->name = $pValue;
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
     * @param  int|float $pValue
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function setSize($pValue = PhpWord::DEFAULT_FONT_SIZE)
    {
        if (!is_numeric($pValue)) {
            $pValue = PhpWord::DEFAULT_FONT_SIZE;
        }
        $this->size = $pValue;
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
     * @param  bool $pValue
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function setBold($pValue = false)
    {
        if (!is_bool($pValue)) {
            $pValue = false;
        }
        $this->bold = $pValue;
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
     * @param  bool $pValue
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function setItalic($pValue = false)
    {
        if (!is_bool($pValue)) {
            $pValue = false;
        }
        $this->italic = $pValue;
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
     * @param  bool $pValue
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function setSuperScript($pValue = false)
    {
        if (!is_bool($pValue)) {
            $pValue = false;
        }
        $this->superScript = $pValue;
        $this->subScript = !$pValue;
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
     * @param  bool $pValue
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function setSubScript($pValue = false)
    {
        if (!is_bool($pValue)) {
            $pValue = false;
        }
        $this->subScript = $pValue;
        $this->superScript = !$pValue;
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
     * @param  string $pValue
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function setUnderline($pValue = self::UNDERLINE_NONE)
    {
        if ($pValue == '') {
            $pValue = self::UNDERLINE_NONE;
        }
        $this->underline = $pValue;
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
     * @param  bool $pValue
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function setStrikethrough($pValue = false)
    {
        if (!is_bool($pValue)) {
            $pValue = false;
        }
        $this->strikethrough = $pValue;
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
     * @param  string $pValue
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function setColor($pValue = PhpWord::DEFAULT_FONT_COLOR)
    {
        if (is_null($pValue) || $pValue == '') {
            $pValue = PhpWord::DEFAULT_FONT_COLOR;
        }
        $this->color = $pValue;
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
     * @param  string $pValue
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function setFgColor($pValue = null)
    {
        $this->fgColor = $pValue;
        return $this;
    }

    /**
     * Get background color
     *
     * @return  string
     */
    public function getBgColor()
    {
        return $this->bgColor;
    }

    /**
     * Set background color
     *
     * @param string $pValue
     * @return $this
     */
    public function setBgColor($pValue = null)
    {
        $this->bgColor = $pValue;
        return $this;
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
     * @param  string $pValue
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function setHint($pValue = PhpWord::DEFAULT_FONT_CONTENT_TYPE)
    {
        if (is_null($pValue) || $pValue == '') {
            $pValue = PhpWord::DEFAULT_FONT_CONTENT_TYPE;
        }
        $this->hint = $pValue;
        return $this;
    }
}
