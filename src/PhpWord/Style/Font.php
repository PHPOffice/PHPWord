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
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\SimpleType\Jc;

/**
 * Font style.
 */
class Font extends AbstractStyle
{
    /**
     * Underline types.
     *
     * @const string
     */
    const UNDERLINE_NONE = 'none';
    const UNDERLINE_DASH = 'dash';
    const UNDERLINE_DASHHEAVY = 'dashHeavy';
    const UNDERLINE_DASHLONG = 'dashLong';
    const UNDERLINE_DASHLONGHEAVY = 'dashLongHeavy';
    const UNDERLINE_DOUBLE = 'dbl';
    const UNDERLINE_DOTDASH = 'dotDash';
    const UNDERLINE_DOTDASHHEAVY = 'dotDashHeavy';
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
     * Foreground colors.
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
     * Aliases.
     *
     * @var array
     */
    protected $aliases = ['line-height' => 'lineHeight', 'letter-spacing' => 'spacing'];


    /**
     * Font style type.
     *
     * @var string
     */
    private $type;

    /**
     * Font name.
     *
     * @var string
     */
    private $name;

    /**
     * Font ascii.
     *
     * @var string
     */
    private $ascii;

    /**
     * Font hAnsi.
     *
     * @var string
     */
    private $hAnsi;

    /**
     * Font cs.
     *
     * @var string
     */
    private $cs;

    /**
     * Font Content Type.
     *
     * @var string
     */
    private $hint;

    /**
     * Font size.
     *
     * @var float|int
     */
    private $size;

    /**
     * 字体复合.
     *
     * @var float|int
     */
    private $sizeCs;

    /**
     * Font color.
     *
     * @var string
     */
    private $color;

    /**
     * Font themeColor.
     *
     * @var string
     */
    private $themeColor;

    /**
     * Font themeShade.
     *
     * @var string
     */
    private $themeShade;

    /**
     * Bold.
     *
     * @var bool
     */
    private $bold;

    /**
     * Italic.
     *
     * @var bool
     */
    private $italic;

    /**
     * Undeline.
     *
     * @var string
     */
    private $underline = self::UNDERLINE_NONE;

    /**
     * u_value.
     *
     * @var string
     */
    private $u_value = null;

    /**
     * u_value.
     *
     * @var string
     */
    private $u_color;


    /**
     * Superscript.
     *
     * @var bool
     */
    private $superScript = false;

    /**
     * Subscript.
     *
     * @var bool
     */
    private $subScript = false;

    /**
     * Strikethrough.
     *
     * @var bool
     */
    private $strikethrough;

    /**
     * Double strikethrough.
     *
     * @var bool
     */
    private $doubleStrikethrough;

    /**
     * Small caps.
     *
     * @var bool
     *
     * @see  http://www.schemacentral.com/sc/ooxml/e-w_smallCaps-1.html
     */
    private $smallCaps;

    /**
     * All caps.
     *
     * @var bool
     *
     * @see  http://www.schemacentral.com/sc/ooxml/e-w_caps-1.html
     */
    private $allCaps;

    /**
     * Foreground/highlight.
     *
     * @var string
     */
    private $fgColor;

    /**
     * Expanded/compressed text: 0-600 (percent).
     *
     * @var int
     *
     * @since 0.12.0
     * @see  http://www.schemacentral.com/sc/ooxml/e-w_w-1.html
     */
    private $scale;

    /**
     * Character spacing adjustment: twip.
     *
     * @var float|int
     *
     * @since 0.12.0
     * @see  http://www.schemacentral.com/sc/ooxml/e-w_spacing-2.html
     */
    private $spacing;

    /**
     * Font kerning: halfpoint.
     *
     * @var float|int
     *
     * @since 0.12.0
     * @see  http://www.schemacentral.com/sc/ooxml/e-w_kern-1.html
     */
    private $kerning;

    /**
     * Paragraph style.
     *
     * @var \PhpOffice\PhpWord\Style\Paragraph
     */
    private $paragraph;

    /**
     * Shading.
     *
     * @var \PhpOffice\PhpWord\Style\Shading
     */
    private $shading;

    /**
     * Right to left languages.
     *
     * @var bool
     */
    private $rtl;

    /**
     * noProof (disables AutoCorrect).
     *
     * @var bool
     * http://www.datypic.com/sc/ooxml/e-w_noProof-1.html
     */
    private $noProof;

    /**
     * Languages.
     *
     * @var \PhpOffice\PhpWord\Style\Language
     */
    private $lang;

    /**
     * Hidden text.
     *
     * @var bool
     *
     * @see  http://www.datypic.com/sc/ooxml/e-w_vanish-1.html
     */
    private $hidden;

    private $tab;

    /**
     * Vertically Raised or Lowered Text.
     *
     * @var int Signed Half-Point Measurement
     *
     * @see http://www.datypic.com/sc/ooxml/e-w_position-1.html
     */
    private $position;

    private $styleId = null;

    /**
     * 段落内的主样式
     *
     * @var bool
     */
    private $isParagraphStyle = false;

    /**
     * Create new font style.
     *
     * @param string $type Type of font
     * @param array|\PhpOffice\PhpWord\Style\AbstractStyle|string $paragraph Paragraph styles definition
     */
    public function __construct($type = 'text', $paragraph = null)
    {
        $this->type = $type;
        $this->setParagraph($paragraph);
    }

    /**
     * Get style values.
     *
     * @return array
     *
     * @since 0.12.0
     */
    public function getStyleValues()
    {
        $styles = [
            'name' => $this->getStyleName(),
            'kern' => $this->getKerning(),
            'styleId' => $this->getStyleId(),
            'basic' => [
                'name' => $this->getName(),
                'ascii' => $this->getAscii(),
                'hAnsi' => $this->getHAnsi(),
                'cs' => $this->getCs(),
                'size' => $this->getSize(),
                'sizeCs' => $this->getSizeCs(),
                'color' => $this->getColor(),
                'hint' => $this->getHint(),
            ],
            'style' => [
                'bold' => $this->isBold(),
                'italic' => $this->isItalic(),
                'underline' => $this->getUnderline(),
                'u_value' => $this->getUValue(),
                'u_color' => $this->getUColor(),
                'strike' => $this->isStrikethrough(),
                'dStrike' => $this->isDoubleStrikethrough(),
                'super' => $this->isSuperScript(),
                'sub' => $this->isSubScript(),
                'smallCaps' => $this->isSmallCaps(),
                'allCaps' => $this->isAllCaps(),
                'fgColor' => $this->getFgColor(),
                'hidden' => $this->isHidden(),
            ],
            'spacing' => [
                'scale' => $this->getScale(),
                'spacing' => $this->getSpacing(),
                'kerning' => $this->getKerning(),
                'position' => $this->getPosition(),
            ],
            'paragraph' => $this->getParagraph(),
            'rtl' => $this->isRTL(),
            'shading' => $this->getShading(),
            'lang' => $this->getLang(),
            'tab' => $this->getTab(),
            'isParagraphStyle' => $this->checkIsParagraphStyle()
        ];

        return $styles;
    }

    /**
     * @since 0.13.0
     *
     * @return string
     */
    public function getStyleId()
    {
        return $this->styleId;
    }

    /**
     * @since 0.13.0
     *
     * @param string $value
     *
     * @return self
     */
    public function setStyleId($value = null)
    {
        if ($value) $this->styleId = $value;

        return $this;
    }

    /**
     * Get style type.
     *
     * @return string
     */
    public function getStyleType()
    {
        return $this->type;
    }

    /**
     * Get font name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set font name.
     *
     * @param string $value
     *
     * @return self
     */
    public function setName($value = null)
    {
        $this->name = $value;

        return $this;
    }

    /**
     * Get font name.
     *
     * @return string
     */
    public function getAscii()
    {
        return $this->ascii;
    }

    /**
     * Set font name.
     *
     * @param string $value
     *
     * @return self
     */
    public function setAscii($value = null)
    {
        $this->ascii = $value;

        return $this;
    }

    /**
     * Get font name.
     *
     * @return string
     */
    public function getHAnsi()
    {
        return $this->hAnsi;
    }

    /**
     * Set font name.
     *
     * @param string $value
     *
     * @return self
     */
    public function setHAnsi($value = null)
    {
        $this->hAnsi = $value;

        return $this;
    }

    /**
     * Get font name.
     *
     * @return string
     */
    public function getCs()
    {
        return $this->cs;
    }

    /**
     * Set font name.
     *
     * @param string $value
     *
     * @return self
     */
    public function setCs($value = null)
    {
        $this->cs = $value;

        return $this;
    }

    /**
     * Get Font Content Type.
     *
     * @return string
     */
    public function getHint()
    {
        return $this->hint;
    }

    /**
     * Set Font Content Type.
     *
     * @param string $value
     *
     * @return self
     */
    public function setHint($value = null)
    {
        $this->hint = $value;

        return $this;
    }

    /**
     * Get font size.
     *
     * @return float|int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set font size.
     *
     * @param float|int $value
     *
     * @return self
     */
    public function setSize($value = null)
    {
        $this->size = $this->setNumericVal($value, $this->size);

        return $this;
    }

    /**
     * Get font sizeCs.
     *
     * @return float|int
     */
    public function getSizeCs()
    {
        return $this->sizeCs;
    }

    /**
     * Set font sizeCs.
     *
     * @param float|int $value
     *
     * @return self
     */
    public function setSizeCs($value = null)
    {
        $this->sizeCs = $this->setNumericVal($value, $this->size);

        return $this;
    }

    /**
     * Get font color.
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set font color.
     *
     * @param string $value
     *
     * @return self
     */
    public function setColor($value = null)
    {
        $this->color = $value;

        return $this;
    }

    /**
     * Get font themeColor.
     *
     * @return string
     */
    public function getThemeColor()
    {
        return $this->themeColor;
    }

    /**
     * Set font themeColor.
     *
     * @param string $value
     *
     * @return self
     */
    public function setThemeColor($value = null)
    {
        $this->themeColor = $value;

        return $this;
    }

    /**
     * Get font themeColor.
     *
     * @return string
     */
    public function getThemeShade()
    {
        return $this->themeShade;
    }

    /**
     * Set font themeColor.
     *
     * @param string $value
     *
     * @return self
     */
    public function setThemeShade($value = null)
    {
        $this->themeShade = $value;
        return $this;
    }

    /**
     * Get bold.
     *
     * @return bool
     */
    public function isBold()
    {
        return $this->bold;
    }

    /**
     * Set bold.
     *
     * @param bool $value
     *
     * @return self
     */
    public function setBold($value = true)
    {
        $this->bold = $this->setBoolVal($value, $this->bold);

        return $this;
    }

    /**
     * Get italic.
     *
     * @return bool
     */
    public function isItalic()
    {
        return $this->italic;
    }

    /**
     * Set italic.
     *
     * @param bool $value
     *
     * @return self
     */
    public function setItalic($value = true)
    {
        $this->italic = $this->setBoolVal($value, $this->italic);

        return $this;
    }

    /**
     * Get underline.
     *
     * @return string
     */
    public function getUnderline()
    {
        return $this->underline;
    }

    /**
     * Set underline.
     *
     * @param string $value
     *
     * @return self
     */
    public function setUnderline($value = self::UNDERLINE_NONE)
    {
        $this->underline = $this->setNonEmptyVal($value, self::UNDERLINE_NONE);

        return $this;
    }

    /**
     * Get underline value.
     *
     * @return string
     */
    public function getUValue()
    {
        return $this->u_value;
    }

    /**
     * Set underline color.
     *
     * @param string $value
     *
     * @return self
     */
    public function setUValue($value)
    {
        $this->u_value = $value;

        return $this;
    }

    /**
     * Get underline color.
     *
     * @return string
     */
    public function getUColor()
    {
        return $this->u_color;
    }

    /**
     * Set underline color.
     *
     * @param string $value
     *
     * @return self
     */
    public function setUColor($value)
    {
        $this->u_color = $value;

        return $this;
    }

    /**
     * Get superscript.
     *
     * @return bool
     */
    public function isSuperScript()
    {
        return $this->superScript;
    }

    /**
     * Set superscript.
     *
     * @param bool $value
     *
     * @return self
     */
    public function setSuperScript($value = true)
    {
        return $this->setPairedVal($this->superScript, $this->subScript, $value);
    }

    /**
     * Get subscript.
     *
     * @return bool
     */
    public function isSubScript()
    {
        return $this->subScript;
    }

    /**
     * Set subscript.
     *
     * @param bool $value
     *
     * @return self
     */
    public function setSubScript($value = true)
    {
        return $this->setPairedVal($this->subScript, $this->superScript, $value);
    }

    /**
     * Get strikethrough.
     *
     * @return bool
     */
    public function isStrikethrough()
    {
        return $this->strikethrough;
    }

    /**
     * Set strikethrough.
     *
     * @param bool $value
     *
     * @return self
     */
    public function setStrikethrough($value = true)
    {
        return $this->setPairedVal($this->strikethrough, $this->doubleStrikethrough, $value);
    }

    /**
     * Get double strikethrough.
     *
     * @return bool
     */
    public function isDoubleStrikethrough()
    {
        return $this->doubleStrikethrough;
    }

    /**
     * Set double strikethrough.
     *
     * @param bool $value
     *
     * @return self
     */
    public function setDoubleStrikethrough($value = true)
    {
        return $this->setPairedVal($this->doubleStrikethrough, $this->strikethrough, $value);
    }

    /**
     * Get small caps.
     *
     * @return bool
     */
    public function isSmallCaps()
    {
        return $this->smallCaps;
    }

    /**
     * Set small caps.
     *
     * @param bool $value
     *
     * @return self
     */
    public function setSmallCaps($value = true)
    {
        return $this->setPairedVal($this->smallCaps, $this->allCaps, $value);
    }

    /**
     * Get all caps.
     *
     * @return bool
     */
    public function isAllCaps()
    {
        return $this->allCaps;
    }

    /**
     * Set all caps.
     *
     * @param bool $value
     *
     * @return self
     */
    public function setAllCaps($value = true)
    {
        return $this->setPairedVal($this->allCaps, $this->smallCaps, $value);
    }

    /**
     * Get foreground/highlight color.
     *
     * @return string
     */
    public function getFgColor()
    {
        return $this->fgColor;
    }

    /**
     * Set foreground/highlight color.
     *
     * @param string $value
     *
     * @return self
     */
    public function setFgColor($value = null)
    {
        $this->fgColor = $value;

        return $this;
    }

    /**
     * Get background.
     *
     * @return string
     */
    public function getBgColor()
    {
        return $this->getChildStyleValue($this->shading, 'fill');
    }

    /**
     * Set background.
     *
     * @param string $value
     *
     * @return \PhpOffice\PhpWord\Style\Table
     */
    public function setBgColor($value = null)
    {
        $this->setShading(['fill' => $value]);
    }

    /**
     * Get scale.
     *
     * @return int
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * Set scale.
     *
     * @param int $value
     *
     * @return self
     */
    public function setScale($value = null)
    {
        $this->scale = $this->setIntVal($value, null);

        return $this;
    }

    /**
     * Get font spacing.
     *
     * @return float|int
     */
    public function getSpacing()
    {
        return $this->spacing;
    }

    /**
     * Set font spacing.
     *
     * @param float|int $value
     *
     * @return self
     */
    public function setSpacing($value = null)
    {
        $this->spacing = $this->setNumericVal($value, null);

        return $this;
    }

    /**
     * Get font kerning.
     *
     * @return float|int
     */
    public function getKerning()
    {
        return $this->kerning;
    }

    /**
     * Set font kerning.
     *
     * @param float|int $value
     *
     * @return self
     */
    public function setKerning($value = null)
    {
        $this->kerning = $this->setNumericVal($value, null);

        return $this;
    }

    /**
     * Get noProof (disables autocorrect).
     *
     * @return bool
     */
    public function isNoProof()
    {
        return $this->noProof;
    }

    /**
     * Set noProof (disables autocorrect).
     *
     * @param bool $value
     *
     * @return $this
     */
    public function setNoProof($value = false)
    {
        $this->noProof = $value;

        return $this;
    }

    /**
     * Get line height.
     *
     * @return float|int
     */
    public function getLineHeight()
    {
        return $this->getParagraph()->getLineHeight();
    }

    /**
     * Set lineheight.
     *
     * @param float|int|string $value
     *
     * @return self
     */
    public function setLineHeight($value)
    {
        $this->setParagraph(['lineHeight' => $value]);

        return $this;
    }

    /**
     * Get paragraph style.
     *
     * @return \PhpOffice\PhpWord\Style\Paragraph
     */
    public function getParagraph()
    {
        return $this->paragraph;
    }

    /**
     * Set Paragraph.
     *
     * @param mixed $value
     *
     * @return self
     */
    public function setParagraph($value = null)
    {
        $this->setObjectVal($value, 'Paragraph', $this->paragraph);

        return $this;
    }

    /**
     * Get rtl.
     *
     * @return bool
     */
    public function isRTL()
    {
        return $this->rtl;
    }

    /**
     * Set rtl.
     *
     * @param bool $value
     *
     * @return self
     */
    public function setRTL($value = true)
    {
        $this->rtl = $this->setBoolVal($value, $this->rtl);

        return $this;
    }

    /**
     * Get shading.
     *
     * @return \PhpOffice\PhpWord\Style\Shading
     */
    public function getShading()
    {
        return $this->shading;
    }

    /**
     * Set shading.
     *
     * @param mixed $value
     *
     * @return self
     */
    public function setShading($value = null)
    {
        $this->setObjectVal($value, 'Shading', $this->shading);

        return $this;
    }

    /**
     * Get tab
     * @param $value
     * @author <presleylee@qq.com>
     * @since 2023/7/11 3:50 下午
     */
    public function setTab($value) {
        $this->tab = $value;
    }

    public function getTab()
    {
        return $this->tab;
    }

    /**
     * Get language.
     *
     * @return \PhpOffice\PhpWord\Style\Language
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set language.
     *
     * @param mixed $value
     *
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
     * Get hidden text.
     *
     * @return bool
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * Set hidden text.
     *
     * @param bool $value
     *
     * @return self
     */
    public function setHidden($value = true)
    {
        $this->hidden = $this->setBoolVal($value, $this->hidden);

        return $this;
    }

    /**
     * Get position.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set position.
     *
     * @param int $value
     *
     * @return self
     */
    public function setPosition($value = null)
    {
        $this->position = $this->setIntVal($value, null);

        return $this;
    }

    public function setIsParagraphStyle($value = false)
    {
        $this->isParagraphStyle = $value;
    }

    public function checkIsParagraphStyle()
    {
        return $this->isParagraphStyle;
    }
}
