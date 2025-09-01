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

        $content = '';

        // To make it easy to determine what's missing as new features are added,
        // Everything below is in the same order as array found in PhpOffice\PhpWord\Style\Font\getStyleValues
        
        // Basic
        $content .= $this->getValueIf($style->getName() !== null, '\f' . $this->nameIndex);
        $content .= $this->getValueIf($style->getSize() !== null, '\fs' . round($style->getSize() * 2));
        $content .= $this->getValueIf($style->getColor() !== null, '\cf' . $this->colorIndex);

        // Underline Keywords
        $underlines = [
            \PhpOffice\PhpWord\Style\Font::UNDERLINE_DASH => '\uldash',
            \PhpOffice\PhpWord\Style\Font::UNDERLINE_DASHHEAVY => '\ulth',
            \PhpOffice\PhpWord\Style\Font::UNDERLINE_DASHLONG => '\ulldash',
            \PhpOffice\PhpWord\Style\Font::UNDERLINE_DASHLONGHEAVY => '\ulthldash',
            \PhpOffice\PhpWord\Style\Font::UNDERLINE_DOUBLE => '\uldb',
            \PhpOffice\PhpWord\Style\Font::UNDERLINE_DOTDASH => '\uldashd',
            \PhpOffice\PhpWord\Style\Font::UNDERLINE_DOTDASHHEAVY => '\ulthdashd',
            \PhpOffice\PhpWord\Style\Font::UNDERLINE_DOTDOTDASH => '\uldashdd',
            \PhpOffice\PhpWord\Style\Font::UNDERLINE_DOTDOTDASHHEAVY => '\ulthdashdd',
            \PhpOffice\PhpWord\Style\Font::UNDERLINE_DOTTED => '\uld',
            \PhpOffice\PhpWord\Style\Font::UNDERLINE_DOTTEDHEAVY => '\ulthd',
            \PhpOffice\PhpWord\Style\Font::UNDERLINE_HEAVY => '\ulth',
            \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE => '\ul',
            \PhpOffice\PhpWord\Style\Font::UNDERLINE_WAVY => '\ulwave',
            \PhpOffice\PhpWord\Style\Font::UNDERLINE_WAVYDOUBLE => '\ululdbwave',
            \PhpOffice\PhpWord\Style\Font::UNDERLINE_WAVYHEAVY => '\ulhwave',
            \PhpOffice\PhpWord\Style\Font::UNDERLINE_WORDS => '\ulw',
        ];

        // Style
        $content .= $this->getValueIf($style->isBold(), '\b');
        $content .= $this->getValueIf($style->isItalic(), '\i');
        if (isset($underlines[$style->getUnderline()])) {
            $content .= $underlines[$style->getUnderline()];
        }
        $content .= $this->getValueIf($style->isStrikethrough(), '\strike');
        $content .= $this->getValueIf($style->isDoubleStrikethrough(), '\striked1');
        $content .= $this->getValueIf($style->isSuperScript(), '\super');
        $content .= $this->getValueIf($style->isSubScript(), '\sub');
        $content .= $this->getValueIf($style->isSmallCaps(), '\scaps');
        $content .= $this->getValueIf($style->isAllCaps(), '\caps');
        $content .= $this->getValueIf($style->getFgColor() !== null, '\highlight' . $this->fgColorIndex);
        $content .= $this->getValueIf($style->isHidden(), '\v');

        // Spacing
        $content .= $this->getValueIf($style->getScale() !== null, '\charscalex' . $style->getScale());
        $content .= $this->getValueIf($style->getSpacing() !== null, '\expnd' . $style->getSpacing());
        $content .= $this->getValueIf($style->getKerning() !== null, '\kerning' . $style->getKerning() * 2);
        $content .= $this->getValueIf($style->getPosition() !== null, '\up' . $style->getPosition());

        // General
        $content .= $this->getValueIf($style->isRTL(), '\rtlch');
        
        // Other (Font settings currently not in included in array)
        $content .= $this->getValueIf($style->isNoProof(), '\noproof');
        $content .= $this->getValueIf($style->getBgColor() !== null, '\cb' . $this->bgColorIndex);

        return $content . ' ';
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
