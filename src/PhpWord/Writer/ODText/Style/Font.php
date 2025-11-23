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

namespace PhpOffice\PhpWord\Writer\ODText\Style;

use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font as FontStyle;

/**
 * Font style writer.
 *
 * @since 0.10.0
 */
class Font extends AbstractStyle
{
    private const UNDERLINES = [
        FontStyle::UNDERLINE_DASH => 'dash',
        FontStyle::UNDERLINE_DASHDOTDOTHEAVY => 'dot-dot-dash',
        FontStyle::UNDERLINE_DASHDOTHEAVY => 'dot-dash',
        FontStyle::UNDERLINE_DASHEDHEAVY => 'dash',
        FontStyle::UNDERLINE_DASHLONG => 'long-dash',
        FontStyle::UNDERLINE_DASHLONGHEAVY => 'long-dash',
        FontStyle::UNDERLINE_DOTDASH => 'dot-dash',
        FontStyle::UNDERLINE_DOTDOTDASH => 'dot-dot-dash',
        FontStyle::UNDERLINE_DOTTED => 'dotted',
        FontStyle::UNDERLINE_DOTTEDHEAVY => 'dotted',
        FontStyle::UNDERLINE_DOUBLE => 'solid',
        FontStyle::UNDERLINE_HEAVY => 'solid',
        FontStyle::UNDERLINE_SINGLE => 'solid',
        FontStyle::UNDERLINE_WAVY => 'wave',
        FontStyle::UNDERLINE_WAVYDOUBLE => 'wave',
        FontStyle::UNDERLINE_WAVYHEAVY => 'wave',
        FontStyle::UNDERLINE_WORDS => 'solid',
    ];

    /**
     * Write style.
     */
    public function write(): void
    {
        $style = $this->getStyle();
        if ($style instanceof FontStyle) {
            $this->writeStyle($style);
        }
    }

    private function writeStyle(FontStyle $style): void
    {
        $xmlWriter = $this->getXmlWriter();

        $stylep = $style->getParagraph();
        if ($stylep instanceof Style\Paragraph) {
            $temp1 = clone $stylep;
            $temp1->setStyleName($style->getStyleName());
            $temp2 = new Paragraph($xmlWriter, $temp1);
            $temp2->write();
        }

        $xmlWriter->startElement('style:style');
        $xmlWriter->writeAttribute('style:name', Style::alternateName($style->getStyleName()));
        $xmlWriter->writeAttribute('style:family', 'text');
        $xmlWriter->startElement('style:text-properties');

        // Name
        $font = $style->getName();
        $xmlWriter->writeAttributeIf($font != '', 'style:font-name', $font);
        $xmlWriter->writeAttributeIf($font != '', 'style:font-name-complex', $font);
        $size = $style->getSize();

        // Size
        $xmlWriter->writeAttributeIf(is_numeric($size), 'fo:font-size', $size . 'pt');
        $xmlWriter->writeAttributeIf(is_numeric($size), 'style:font-size-asian', $size . 'pt');
        $xmlWriter->writeAttributeIf(is_numeric($size), 'style:font-size-complex', $size . 'pt');

        // Color
        $color = (string) $style->getColor();
        $xmlWriter->writeAttributeIf($color !== '', 'fo:color', '#' . \PhpOffice\PhpWord\Shared\Converter::stringToRgb($color));

        // Bold & italic
        $xmlWriter->writeAttributeIf($style->isBold(), 'fo:font-weight', 'bold');
        $xmlWriter->writeAttributeIf($style->isBold(), 'style:font-weight-asian', 'bold');
        $xmlWriter->writeAttributeIf($style->isItalic(), 'fo:font-style', 'italic');
        $xmlWriter->writeAttributeIf($style->isItalic(), 'style:font-style-asian', 'italic');
        $xmlWriter->writeAttributeIf($style->isItalic(), 'style:font-style-complex', 'italic');

        // Underline
        $underline = $style->getUnderline();
        if (isset(self::UNDERLINES[$underline])) {
            $xmlWriter->writeAttribute('style:text-underline-style', self::UNDERLINES[$underline]);
            $xmlWriter->writeAttributeIf(strpos(strtolower($underline), 'heavy') !== false, 'style:text-underline-width', 'bold');
            $xmlWriter->writeAttributeIf(strpos(strtolower($underline), 'thick') !== false, 'style:text-underline-width', 'bold');
            $xmlWriter->writeAttributeIf(strpos(strtolower($underline), 'double') !== false, 'style:text-underline-type', 'double');
            $xmlWriter->writeAttributeIf(strpos(strtolower($underline), 'words') !== false, 'style:text-underline-mode', 'skip-white-space');
        }

        // Strikethrough, double strikethrough
        $xmlWriter->writeAttributeIf($style->isStrikethrough(), 'style:text-line-through-type', 'single');
        $xmlWriter->writeAttributeIf($style->isDoubleStrikethrough(), 'style:text-line-through-type', 'double');

        // Small caps, all caps
        $xmlWriter->writeAttributeIf($style->isSmallCaps(), 'fo:font-variant', 'small-caps');
        $xmlWriter->writeAttributeIf($style->isAllCaps(), 'fo:text-transform', 'uppercase');

        //Hidden text
        $xmlWriter->writeAttributeIf($style->isHidden(), 'text:display', 'none');

        // Superscript/subscript
        $xmlWriter->writeAttributeIf($style->isSuperScript(), 'style:text-position', 'super');
        $xmlWriter->writeAttributeIf($style->isSubScript(), 'style:text-position', 'sub');

        if ($style->isNoProof()) {
            $xmlWriter->writeAttribute('fo:language', 'zxx');
            $xmlWriter->writeAttribute('style:language-asian', 'zxx');
            $xmlWriter->writeAttribute('style:language-complex', 'zxx');
            $xmlWriter->writeAttribute('fo:country', 'none');
            $xmlWriter->writeAttribute('style:country-asian', 'none');
            $xmlWriter->writeAttribute('style:country-complex', 'none');
        }

        // Foreground-Color (which is really background color)
        $fgColor = (string) $style->getFgColor();
        $xmlWriter->writeAttributeIf($fgColor !== '', 'fo:background-color', '#' . \PhpOffice\PhpWord\Shared\Converter::stringToRgb($fgColor));

        $xmlWriter->endElement(); // style:text-properties
        $xmlWriter->endElement(); // style:style
    }
}
