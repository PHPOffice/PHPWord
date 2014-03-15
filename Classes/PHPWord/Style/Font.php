<?php

/**
 * PHPWord
 *
 * Copyright (c) 2014 PHPWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.8.0
 */
use PhpOffice\PhpWord\Exceptions\InvalidStyleException;

/**
 * Class PHPWord_Style_Font
 */
class PHPWord_Style_Font
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
    private $_type;

    /**
     * Paragraph Style
     *
     * @var PHPWord_Style_Paragraph
     */
    private $_paragraphStyle;

    /**
     * Font name
     *
     * @var int|float
     */
    private $_name = PHPWord::DEFAULT_FONT_NAME;

    /**
     * Font size
     *
     * @var int|float
     */
    private $_size = PHPWord::DEFAULT_FONT_SIZE;

    /**
     * Bold
     *
     * @var bool
     */
    private $_bold = false;

    /**
     * Italics
     *
     * @var bool
     */
    private $_italic = false;

    /**
     * Superscript
     *
     * @var bool
     */
    private $_superScript = false;

    /**
     * Subscript
     *
     * @var bool
     */
    private $_subScript = false;

    /**
     * Underline mode
     *
     * @var string
     */
    private $_underline = PHPWord_Style_Font::UNDERLINE_NONE;

    /**
     * Strikethrough
     *
     * @var bool
     */
    private $_strikethrough = false;

    /**
     * Font color
     *
     * @var string
     */
    private $_color = PHPWord::DEFAULT_FONT_COLOR;

    /**
     * Foreground/highlight
     *
     * @var string
     */
    private $_fgColor = null;

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
    private $_hint = PHPWord::DEFAULT_FONT_CONTENT_TYPE;

    /**
     * New font style
     *
     * @param string $type Type of font
     * @param array $paragraphStyle Paragraph styles definition
     */
    public function __construct($type = 'text', $paragraphStyle = null)
    {
        $this->_type = $type;

        if ($paragraphStyle instanceof PHPWord_Style_Paragraph) {
            $this->_paragraphStyle = $paragraphStyle;
        } elseif (is_array($paragraphStyle)) {
            $this->_paragraphStyle = new PHPWord_Style_Paragraph;
            $this->_paragraphStyle->setArrayStyle($paragraphStyle);
        } else {
            $this->_paragraphStyle = $paragraphStyle;
        }
    }

    /**
     * @param array $style
     * @return $this
     */
    public function setArrayStyle(array $style = array())
    {
        foreach ($style as $key => $value) {
            if ($key === 'line-height') {
                $this->setLineHeight($value);
                null;
            } elseif (substr($key, 0, 1) !== '_') {
                $key = '_' . $key;
            }
            $this->setStyleValue($key, $value);
        }

        return $this;
    }

    /**
     * Set style value
     *
     * @param   string $key
     * @param   mixed $value
     */
    public function setStyleValue($key, $value)
    {
        $method = 'set' . substr($key, 1);
        if (method_exists($this, $method)) {
            $this->$method($value);
        }
    }

    /**
     * Get font name
     *
     * @return  bool
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Set font name
     *
     * @param   string $pValue
     * @return  PHPWord_Style_Font
     */
    public function setName($pValue = PHPWord::DEFAULT_FONT_NAME)
    {
        if (is_null($pValue) || $pValue == '') {
            $pValue = PHPWord::DEFAULT_FONT_NAME;
        }
        $this->_name = $pValue;
        return $this;
    }


    /**
     * Get font size
     *
     * @return  int|float
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * Set font size
     *
     * @param   int|float $pValue
     * @return  PHPWord_Style_Font
     */
    public function setSize($pValue = PHPWord::DEFAULT_FONT_SIZE)
    {
        if (!is_numeric($pValue)) {
            $pValue = PHPWord::DEFAULT_FONT_SIZE;
        }
        $this->_size = $pValue;
        return $this;
    }

    /**
     * Get bold
     *
     * @return  bool
     */
    public function getBold()
    {
        return $this->_bold;
    }

    /**
     * Set bold
     *
     * @param   bool $pValue
     * @return  PHPWord_Style_Font
     */
    public function setBold($pValue = false)
    {
        if (!is_bool($pValue)) {
            $pValue = false;
        }
        $this->_bold = $pValue;
        return $this;
    }

    /**
     * Get italics
     *
     * @return  bool
     */
    public function getItalic()
    {
        return $this->_italic;
    }

    /**
     * Set italics
     *
     * @param   bool $pValue
     * @return  PHPWord_Style_Font
     */
    public function setItalic($pValue = false)
    {
        if (!is_bool($pValue)) {
            $pValue = false;
        }
        $this->_italic = $pValue;
        return $this;
    }

    /**
     * Get superscript
     *
     * @return  bool
     */
    public function getSuperScript()
    {
        return $this->_superScript;
    }

    /**
     * Set superscript
     *
     * @param   bool $pValue
     * @return  PHPWord_Style_Font
     */
    public function setSuperScript($pValue = false)
    {
        if (!is_bool($pValue)) {
            $pValue = false;
        }
        $this->_superScript = $pValue;
        $this->_subScript = !$pValue;
        return $this;
    }

    /**
     * Get superscript
     *
     * @return  bool
     */
    public function getSubScript()
    {
        return $this->_subScript;
    }

    /**
     * Set subscript
     *
     * @param   bool $pValue
     * @return  PHPWord_Style_Font
     */
    public function setSubScript($pValue = false)
    {
        if (!is_bool($pValue)) {
            $pValue = false;
        }
        $this->_subScript = $pValue;
        $this->_superScript = !$pValue;
        return $this;
    }

    /**
     * Get underline
     *
     * @return  string
     */
    public function getUnderline()
    {
        return $this->_underline;
    }

    /**
     * Set underline
     *
     * @param   string $pValue
     * @return  PHPWord_Style_Font
     */
    public function setUnderline($pValue = PHPWord_Style_Font::UNDERLINE_NONE)
    {
        if ($pValue == '') {
            $pValue = PHPWord_Style_Font::UNDERLINE_NONE;
        }
        $this->_underline = $pValue;
        return $this;
    }

    /**
     * Get strikethrough
     *
     * @return  bool
     */
    public function getStrikethrough()
    {
        return $this->_strikethrough;
    }

    /**
     * Set strikethrough
     *
     * @param   bool $pValue
     * @return  PHPWord_Style_Font
     */
    public function setStrikethrough($pValue = false)
    {
        if (!is_bool($pValue)) {
            $pValue = false;
        }
        $this->_strikethrough = $pValue;
        return $this;
    }

    /**
     * Get font color
     *
     * @return  string
     */
    public function getColor()
    {
        return $this->_color;
    }

    /**
     * Set font color
     *
     * @param   string $pValue
     * @return  PHPWord_Style_Font
     */
    public function setColor($pValue = PHPWord::DEFAULT_FONT_COLOR)
    {
        if (is_null($pValue) || $pValue == '') {
            $pValue = PHPWord::DEFAULT_FONT_COLOR;
        }
        $this->_color = $pValue;
        return $this;
    }

    /**
     * Get foreground/highlight color
     *
     * @return  bool
     */
    public function getFgColor()
    {
        return $this->_fgColor;
    }

    /**
     * Set foreground/highlight color
     *
     * @param   string $pValue
     * @return  PHPWord_Style_Font
     */
    public function setFgColor($pValue = null)
    {
        $this->_fgColor = $pValue;
        return $this;
    }

    /**
     * Get style type
     *
     * @return string
     */
    public function getStyleType()
    {
        return $this->_type;
    }

    /**
     * Get paragraph style
     *
     * @return PHPWord_Style_Paragraph
     */
    public function getParagraphStyle()
    {
        return $this->_paragraphStyle;
    }

    /**
     * Set the line height
     *
     * @param int|float|string $lineHeight
     * @return $this
     * @throws InvalidStyleException
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
     * @return int|float
     */
    public function getLineHeight()
    {
        return $this->lineHeight;
    }

    /**
     * Get Font Content Type
     *
     * @return  bool
     */
    public function getHint()
    {
        return $this->_hint;
    }

    /**
     * Set Font Content Type
     *
     * @param   string $pValue
     * @return  PHPWord_Style_Font
     */
    public function setHint($pValue = PHPWord::DEFAULT_FONT_CONTENT_TYPE)
    {
        if (is_null($pValue) || $pValue == '') {
            $pValue = PHPWord::DEFAULT_FONT_CONTENT_TYPE;
        }
        $this->_hint = $pValue;
        return $this;
    }
}
