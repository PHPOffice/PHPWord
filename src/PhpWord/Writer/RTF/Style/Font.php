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

namespace PhpOffice\PhpWord\Writer\RTF\Style;

use PhpOffice\PhpWord\Style\Font as FontStyle;

/**
 * RTF font style writer.
 *
 * @since 0.11.0
 */
class Font extends AbstractStyle
{
    /**
     * @var int Font name index
     */
    private $nameIndex = 0;

    /**
     * @var int Font color index
     */
    private $colorIndex = 0;

    /**
     * @var int Font foreground color index
     */
    private $fgColorIndex = 0;

    /**
     * @var int Font background color index
     */
    private $bgColorIndex = 0;

    /**
     * Write style.
     *
     * @return string
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof FontStyle) {
            return '';
        }

        // Underline Keywords
        $underlines = [
            FontStyle::UNDERLINE_DASH => '\uldash',
            FontStyle::UNDERLINE_DASHHEAVY => '\ulthdash',
            FontStyle::UNDERLINE_DASHLONG => '\ulldash',
            FontStyle::UNDERLINE_DASHLONGHEAVY => '\ulthldash',
            FontStyle::UNDERLINE_DOUBLE => '\uldb',
            FontStyle::UNDERLINE_DOTDASH => '\uldashd',
            FontStyle::UNDERLINE_DOTDASHHEAVY => '\ulthdashd',
            FontStyle::UNDERLINE_DOTDOTDASH => '\uldashdd',
            FontStyle::UNDERLINE_DOTDOTDASHHEAVY => '\ulthdashdd',
            FontStyle::UNDERLINE_DOTTED => '\uld',
            FontStyle::UNDERLINE_DOTTEDHEAVY => '\ulthd',
            FontStyle::UNDERLINE_HEAVY => '\ulth',
            FontStyle::UNDERLINE_SINGLE => '\ul',
            FontStyle::UNDERLINE_WAVY => '\ulwave',
            FontStyle::UNDERLINE_WAVYDOUBLE => '\ululdbwave',
            FontStyle::UNDERLINE_WAVYHEAVY => '\ulhwave',
            FontStyle::UNDERLINE_WORDS => '\ulw',
        ];

        $content = '';
        // Font name/family
        $content .= $this->getValueIf($style->getName() !== null, '\f' . $this->nameIndex);

        // Language
        if ($style->getLang() !== null) {
            if ($style->isNoProof()) {
                $content .= $this->getValueIf($style->getLang()->getLangId() !== 0, '\langnp' . $style->getLang()->getLangId());
            } else {
                $content .= $this->getValueIf($style->getLang()->getLangId() !== 0, '\lang' . $style->getLang()->getLangId());
            }
        }

        // Color
        $content .= $this->getValueIf($style->getColor() !== null, '\cf' . $this->colorIndex);

        // Size
        $content .= $this->getValueIf($style->getSize() !== null, '\fs' . round($style->getSize() * 2));

        // Bold, italic
        $content .= $this->getValueIf($style->isBold(), '\b');
        $content .= $this->getValueIf($style->isBold() === false, '\b0');
        $content .= $this->getValueIf($style->isItalic(), '\i');
        $content .= $this->getValueIf($style->isItalic() === false, '\i0');

        // Strikethrough, double strikethrough
        if ($style->isDoubleStrikethrough()) {
            $content .= '\striked1';
        } elseif ($style->isStrikethrough()) {
            $content .= '\strike';
        } elseif ($style->isStrikethrough() === false || $style->isDoubleStrikethrough() === false) {
            $content .= '\strike0';
        }

        // Small caps, all caps
        $content .= $this->getValueIf($style->isSmallCaps(), '\scaps');
        $content .= $this->getValueIf($style->isAllCaps(), '\caps');

        // Hidden text
        $content .= $this->getValueIf($style->isHidden(), '\v');
        $content .= $this->getValueIf($style->isHidden() === false, '\v0');

        // Underline
        if (isset($underlines[$style->getUnderline()])) {
            $content .= $underlines[$style->getUnderline()];
        }

        // Foreground color
        $content .= $this->getValueIf($style->getFgColor() !== null, '\highlight' . $this->fgColorIndex);

        // Superscript/subscript
        $content .= $this->getValueIf($style->isSuperScript(), '\super');
        $content .= $this->getValueIf($style->isSubScript(), '\sub');

        // Spacing
        $content .= $this->getValueIf($style->getScale() !== null, '\charscalex' . round($style->getScale() ?? 0));
        $content .= $this->getValueIf($style->getSpacing() !== null, '\expnd' . round($style->getSpacing() * 0.2));
        $content .= $this->getValueIf($style->getSpacing() !== null, '\expndtw' . round($style->getSpacing() ?? 0));
        $content .= $this->getValueIf($style->getKerning() !== null, '\kerning' . round($style->getKerning() * 2));

        // noProof
        // This is also specified above as \\langnp{$langId}
        // RTF spec suggests using this for backwards compatibility.
        // So perhaps earlier can be omitted, but it seems harmless regardless.
        $content .= $this->getValueIf($style->isNoProof(), '\noproof\lang1024');

        // Background-Color
        $content .= $this->getValueIf($style->getBgColor() !== null, '\chshdng0\chcbpat' . $this->bgColorIndex . '\cb' . $this->bgColorIndex);

        // RTL
        $content .= $this->getValueIf($style->isRTL(), '\rtlch');
        $content .= $this->getValueIf($style->isRTL() === false, '\ltrch');

        // Position
        $content .= $this->getValueIf($style->getPosition() !== null, '\up' . $style->getPosition());

        return ($content === '') ? '' : ("$content ");
    }

    /**
     * Set font name index.
     *
     * @param int $value
     */
    public function setNameIndex($value = 0): void
    {
        $this->nameIndex = $value;
    }

    /**
     * Set font color index.
     *
     * @param int $value
     */
    public function setColorIndex($value = 0): void
    {
        $this->colorIndex = $value;
    }

    /**
     * Set font foreground color index.
     *
     * @param int $value
     */
    public function setFgColorIndex($value = 0): void
    {
        $this->fgColorIndex = $value;
    }

    /**
     * Set font background color index.
     *
     * @param int $value
     */
    public function setBgColorIndex($value = 0): void
    {
        $this->bgColorIndex = $value;
    }
}
