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
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

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
     * Aliases
     *
     * @var array
     */
    protected $aliases = array('line-height' => 'lineHeight');

    /**
     * Font style type
     *
     * @var string
     */
    private $type;

    /**
     * Font name
     *
     * @var string
     */
    private $name;

    /**
     * Font Content Type
     *
     * @var string
     */
    private $hint;

    /**
     * Font size
     *
     * @var int|float
     */
    private $size;

    /**
     * Font color
     *
     * @var string
     */
    private $color;

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
     * Undeline
     *
     * @var string
     */
    private $underline = self::UNDERLINE_NONE;

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
     * Foreground/highlight
     *
     * @var string
     */
    private $fgColor;

    /**
     * Expanded/compressed text: 0-600 (percent)
     *
     * @var int
     * @since 0.12.0
     * @link http://www.schemacentral.com/sc/ooxml/e-w_w-1.html
     */
    private $scale;

    /**
     * Character spacing adjustment: twip
     *
     * @var int|float
     * @since 0.12.0
     * @link http://www.schemacentral.com/sc/ooxml/e-w_spacing-2.html
     */
    private $spacing;

    /**
     * Font kerning: halfpoint
     *
     * @var int|float
     * @since 0.12.0
     * @link http://www.schemacentral.com/sc/ooxml/e-w_kern-1.html
     */
    private $kerning;

    /**
     * Paragraph style
     *
     * @var \PhpOffice\PhpWord\Style\Paragraph
     */
    private $paragraph;

    /**
     * Shading
     *
     * @var \PhpOffice\PhpWord\Style\Shading
     */
    private $shading;

    /**
     * Right to left languages
     * @var boolean
     */
    private $rtl = false;

    /**
     * Languages 
     * @var \PhpOffice\PhpWord\Style\Language
     */
    private $lang;

    /**
     * Create new font style
     *
     * @param string $type Type of font
     * @param array $paragraph Paragraph styles definition
     */
    public function __construct($type = 'text', $paragraph = null)
    {
        $this->type = $type;
        $this->setParagraph($paragraph);
    }

    /**
     * Get style values
     *
     * @return array
     * @since 0.12.0
     */
    public function getStyleValues()
    {
        $styles = array(
            'name'          => $this->getStyleName(),
            'basic'         => array(
                'name'      => $this->getName(),
                'size'      => $this->getSize(),
                'color'     => $this->getColor(),
                'hint'      => $this->getHint(),
            ),
            'style'         => array(
                'bold'      => $this->isBold(),
                'italic'    => $this->isItalic(),
                'underline' => $this->getUnderline(),
                'strike'    => $this->isStrikethrough(),
                'dStrike'   => $this->isDoubleStrikethrough(),
                'super'     => $this->isSuperScript(),
                'sub'       => $this->isSubScript(),
                'smallCaps' => $this->isSmallCaps(),
                'allCaps'   => $this->isAllCaps(),
                'fgColor'   => $this->getFgColor(),
            ),
            'spacing'       => array(
                'scale'     => $this->getScale(),
                'spacing'   => $this->getSpacing(),
                'kerning'   => $this->getKerning(),
            ),
            'paragraph'     => $this->getParagraph(),
            'rtl'           => $this->isRTL(),
            'shading'       => $this->getShading(),
            'lang'          => $this->getLang(),
        );

        return $styles;
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
     * @param string $value
     * @return self
     */
    public function setName($value = null)
    {
        $this->name = $value;

        return $this;
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
     * @param string $value
     * @return self
     */
    public function setHint($value = null)
    {
        $this->hint = $value;

        return $this;
    }

    /**
     * Get font size
     *
     * @return int|float
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set font size
     *
     * @param int|float $value
     * @return self
     */
    public function setSize($value = null)
    {
        $this->size = $this->setNumericVal($value, $this->size);

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
     * @param string $value
     * @return self
     */
    public function setColor($value = null)
    {
        $this->color = $value;

        return $this;
    }

    /**
     * Get bold
     *
     * @return bool
     */
    public function isBold()
    {
        return $this->bold;
    }

    /**
     * Set bold
     *
     * @param bool $value
     * @return self
     */
    public function setBold($value = true)
    {
        $this->bold = $this->setBoolVal($value, $this->bold);

        return $this;
    }

    /**
     * Get italic
     *
     * @return bool
     */
    public function isItalic()
    {
        return $this->italic;
    }

    /**
     * Set italic
     *
     * @param bool $value
     * @return self
     */
    public function setItalic($value = true)
    {
        $this->italic = $this->setBoolVal($value, $this->italic);

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
     * @param string $value
     * @return self
     */
    public function setUnderline($value = self::UNDERLINE_NONE)
    {
        $this->underline = $this->setNonEmptyVal($value, self::UNDERLINE_NONE);

        return $this;
    }

    /**
     * Get superscript
     *
     * @return bool
     */
    public function isSuperScript()
    {
        return $this->superScript;
    }

    /**
     * Set superscript
     *
     * @param bool $value
     * @return self
     */
    public function setSuperScript($value = true)
    {
        return $this->setPairedVal($this->superScript, $this->subScript, $value);
    }

    /**
     * Get subscript
     *
     * @return bool
     */
    public function isSubScript()
    {
        return $this->subScript;
    }

    /**
     * Set subscript
     *
     * @param bool $value
     * @return self
     */
    public function setSubScript($value = true)
    {
        return $this->setPairedVal($this->subScript, $this->superScript, $value);
    }

    /**
     * Get strikethrough
     *
     * @return bool
     */
    public function isStrikethrough()
    {
        return $this->strikethrough;
    }

    /**
     * Set strikethrough
     *
     * @param bool $value
     * @return self
     */
    public function setStrikethrough($value = true)
    {
        return $this->setPairedVal($this->strikethrough, $this->doubleStrikethrough, $value);
    }

    /**
     * Get double strikethrough
     *
     * @return bool
     */
    public function isDoubleStrikethrough()
    {
        return $this->doubleStrikethrough;
    }

    /**
     * Set double strikethrough
     *
     * @param bool $value
     * @return self
     */
    public function setDoubleStrikethrough($value = true)
    {
        return $this->setPairedVal($this->doubleStrikethrough, $this->strikethrough, $value);
    }

    /**
     * Get small caps
     *
     * @return bool
     */
    public function isSmallCaps()
    {
        return $this->smallCaps;
    }

    /**
     * Set small caps
     *
     * @param bool $value
     * @return self
     */
    public function setSmallCaps($value = true)
    {
        return $this->setPairedVal($this->smallCaps, $this->allCaps, $value);
    }

    /**
     * Get all caps
     *
     * @return bool
     */
    public function isAllCaps()
    {
        return $this->allCaps;
    }

    /**
     * Set all caps
     *
     * @param bool $value
     * @return self
     */
    public function setAllCaps($value = true)
    {
        return $this->setPairedVal($this->allCaps, $this->smallCaps, $value);
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
     * @param string $value
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
        return $this->getChildStyleValue($this->shading, 'fill');
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
     * Get scale
     *
     * @return int
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * Set scale
     *
     * @param int $value
     * @return self
     */
    public function setScale($value = null)
    {
        $this->scale = $this->setIntVal($value, null);

        return $this;
    }

    /**
     * Get font spacing
     *
     * @return int|float
     */
    public function getSpacing()
    {
        return $this->spacing;
    }

    /**
     * Set font spacing
     *
     * @param int|float $value
     * @return self
     */
    public function setSpacing($value = null)
    {
        $this->spacing = $this->setNumericVal($value, null);

        return $this;
    }

    /**
     * Get font kerning
     *
     * @return int|float
     */
    public function getKerning()
    {
        return $this->kerning;
    }

    /**
     * Set font kerning
     *
     * @param int|float $value
     * @return self
     */
    public function setKerning($value = null)
    {
        $this->kerning = $this->setNumericVal($value, null);

        return $this;
    }

    /**
     * Get line height
     *
     * @return int|float
     */
    public function getLineHeight()
    {
        return $this->getParagraph()->getLineHeight();
    }

    /**
     * Set lineheight
     *
     * @param int|float|string $value
     * @return self
     */
    public function setLineHeight($value)
    {
        $this->setParagraph(array('lineHeight' => $value));

        return $this;
    }

    /**
     * Get paragraph style
     *
     * @return \PhpOffice\PhpWord\Style\Paragraph
     */
    public function getParagraph()
    {
        return $this->paragraph;
    }

    /**
     * Set Paragraph
     *
     * @param mixed $value
     * @return self
     */
    public function setParagraph($value = null)
    {
        $this->setObjectVal($value, 'Paragraph', $this->paragraph);

        return $this;
    }

    /**
     * Get rtl
     *
     * @return bool
     */
    public function isRTL()
    {
        return $this->rtl;
    }

    /**
     * Set rtl
     *
     * @param bool $value
     * @return self
     */
    public function setRTL($value = true)
    {
        $this->rtl = $this->setBoolVal($value, $this->rtl);

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
     * @param mixed $value
     * @return self
     */
    public function setShading($value = null)
    {
        $this->setObjectVal($value, 'Shading', $this->shading);

        return $this;
    }

    /**
     * Get language
     *
     * @return \PhpOffice\PhpWord\Style\Language
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set language
     *
     * @param mixed $value
     * @return self
     */
    public function setLang($value = null)
    {
        if (is_string($value) && $value != '') {
            $value = new Language($value);
        }
        $this->setObjectVal($value, 'Language', $this->lang);
        
        return $this;
    }

    /**
     * Get bold
     *
     * @deprecated 0.10.0
     *
     * @codeCoverageIgnore
     */
    public function getBold()
    {
        return $this->isBold();
    }

    /**
     * Get italic
     *
     * @deprecated 0.10.0
     *
     * @codeCoverageIgnore
     */
    public function getItalic()
    {
        return $this->isItalic();
    }

    /**
     * Get superscript
     *
     * @deprecated 0.10.0
     *
     * @codeCoverageIgnore
     */
    public function getSuperScript()
    {
        return $this->isSuperScript();
    }

    /**
     * Get subscript
     *
     * @deprecated 0.10.0
     *
     * @codeCoverageIgnore
     */
    public function getSubScript()
    {
        return $this->isSubScript();
    }

    /**
     * Get strikethrough
     *
     * @deprecated 0.10.0
     *
     * @codeCoverageIgnore
     */
    public function getStrikethrough()
    {
        return $this->isStrikethrough();
    }

    /**
     * Get paragraph style
     *
     * @deprecated 0.11.0
     *
     * @codeCoverageIgnore
     */
    public function getParagraphStyle()
    {
        return $this->getParagraph();
    }
}
