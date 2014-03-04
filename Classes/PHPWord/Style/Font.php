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
 * @version    0.7.0
 */

use PHPWord\Exceptions\InvalidStyleException;

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
     * @var int
     */
    private $_size = 20;

    /**
     * @var string
     */
    private $_name = 'Arial';

    /**
     * @var bool
     */
    private $_bold = false;

    /**
     * @var bool
     */
    private $_italic = false;

    /**
     * @var bool
     */
    private $_superScript = false;

    /**
     * @var bool
     */
    private $_subScript = false;

    /**
     * @var string
     */
    private $_underline = PHPWord_Style_Font::UNDERLINE_NONE;

    /**
     * @var bool
     */
    private $_strikethrough = false;

    /**
     * @var string
     */
    private $_color = '000000';

    /**
     * @var null
     */
    private $_fgColor = null;

    /**
     * Text line height
     *
     * @var int
     */
    private $lineHeight = 1.0;

    /**
     * @param string $type
     * @param null|array|PHPWord_Style_Paragraph $paragraphStyle
     */
    public function __construct($type = 'text', $paragraphStyle = null)
    {
        $this->_type = $type;

        if ($paragraphStyle instanceof PHPWord_Style_Paragraph) {
            $this->_paragraphStyle = $paragraphStyle;
        } elseif (is_array($paragraphStyle)) {
            $this->_paragraphStyle = new PHPWord_Style_Paragraph;
            $this->_paragraphStyle->setArrayStyle($paragraphStyle);
        } elseif (null === $paragraphStyle) {
            $this->_paragraphStyle = new PHPWord_Style_Paragraph;
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
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setStyleValue($key, $value)
    {
        if ($key == '_size') {
            $value *= 2;
        }
        $this->$key = $value;
    }

    /**
     * @param string $pValue
     * @return $this
     */
    public function setName($pValue = 'Arial')
    {
        if ($pValue == '') {
            $pValue = 'Arial';
        }
        $this->_name = $pValue;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * @param int $pValue
     * @return $this
     */
    public function setSize($pValue = 20)
    {
        if ($pValue == '') {
            $pValue = 20;
        }
        $this->_size = ($pValue * 2);
        return $this;
    }

    /**
     * @return bool
     */
    public function getBold()
    {
        return $this->_bold;
    }

    /**
     * @param bool $pValue
     * @return $this
     */
    public function setBold($pValue = false)
    {
        if ($pValue == '') {
            $pValue = false;
        }
        $this->_bold = $pValue;
        return $this;
    }

    /**
     * @return bool
     */
    public function getItalic()
    {
        return $this->_italic;
    }

    /**
     * @param bool $pValue
     * @return $this
     */
    public function setItalic($pValue = false)
    {
        if ($pValue == '') {
            $pValue = false;
        }
        $this->_italic = $pValue;
        return $this;
    }

    /**
     * @return bool
     */
    public function getSuperScript()
    {
        return $this->_superScript;
    }

    /**
     * @param bool $pValue
     * @return $this
     */
    public function setSuperScript($pValue = false)
    {
        if ($pValue == '') {
            $pValue = false;
        }
        $this->_superScript = $pValue;
        $this->_subScript = !$pValue;
        return $this;
    }

    /**
     * @return bool
     */
    public function getSubScript()
    {
        return $this->_subScript;
    }

    /**
     * @param bool $pValue
     * @return $this
     */
    public function setSubScript($pValue = false)
    {
        if ($pValue == '') {
            $pValue = false;
        }
        $this->_subScript = $pValue;
        $this->_superScript = !$pValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getUnderline()
    {
        return $this->_underline;
    }

    /**
     * @param string $pValue
     * @return $this
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
     * @return bool
     */
    public function getStrikethrough()
    {
        return $this->_strikethrough;
    }

    /**
     * @param bool $pValue
     * @return $this
     */
    public function setStrikethrough($pValue = false)
    {
        if ($pValue == '') {
            $pValue = false;
        }
        $this->_strikethrough = $pValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->_color;
    }

    /**
     * @param string $pValue
     * @return $this
     */
    public function setColor($pValue = '000000')
    {
        $this->_color = $pValue;
        return $this;
    }

    /**
     * @return null
     */
    public function getFgColor()
    {
        return $this->_fgColor;
    }

    /**
     * @param null $pValue
     * @return $this
     */
    public function setFgColor($pValue = null)
    {
        $this->_fgColor = $pValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getStyleType()
    {
        return $this->_type;
    }

    /**
     * Get Paragraph style
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
     * @throws \PHPWord\Exceptions\InvalidStyleException
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
}