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

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

/**
 * Font style writer
 *
 * @since 0.10.0
 */
class Font extends AbstractStyle
{
    /**
     * Is inline in element
     *
     * @var bool
     */
    private $isInline = false;

    /**
     * Write style
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();

        $isStyleName = $this->isInline && !is_null($this->style) && is_string($this->style);
        if ($isStyleName) {
            $xmlWriter->startElement('w:rPr');
            $xmlWriter->startElement('w:rStyle');
            $xmlWriter->writeAttribute('w:val', $this->style);
            $xmlWriter->endElement();
            $xmlWriter->endElement();
        } else {
            $this->writeStyle();
        }
    }

    /**
     * Write full style
     */
    private function writeStyle()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Font) {
            return;
        }
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startElement('w:rPr');

        // Style name
        $styleName = $style->getStyleName();
        $xmlWriter->writeElementIf(!is_null($styleName), 'w:rStyle', 'w:val', $styleName);

        // Font name/family
        $font = $style->getName();
        $hint = $style->getHint();
        if ($font !== null) {
            $xmlWriter->startElement('w:rFonts');
            $xmlWriter->writeAttribute('w:ascii', $font);
            $xmlWriter->writeAttribute('w:hAnsi', $font);
            $xmlWriter->writeAttribute('w:eastAsia', $font);
            $xmlWriter->writeAttribute('w:cs', $font);
            $xmlWriter->writeAttributeIf($hint !== null, 'w:hint', $hint);
            $xmlWriter->endElement();
        }

        // Color
        $color = $style->getColor();
        $xmlWriter->writeElementIf($color !== null, 'w:color', 'w:val', $color);

        // Size
        $size = $style->getSize();
        $xmlWriter->writeElementIf($size !== null, 'w:sz', 'w:val', $size * 2);
        $xmlWriter->writeElementIf($size !== null, 'w:szCs', 'w:val', $size * 2);

        // Bold, italic
        $xmlWriter->writeElementIf($style->isBold(), 'w:b');
        $xmlWriter->writeElementIf($style->isItalic(), 'w:i');
        $xmlWriter->writeElementIf($style->isItalic(), 'w:iCs');

        // Strikethrough, double strikethrough
        $xmlWriter->writeElementIf($style->isStrikethrough(), 'w:strike');
        $xmlWriter->writeElementIf($style->isDoubleStrikethrough(), 'w:dstrike');

        // Small caps, all caps
        $xmlWriter->writeElementIf($style->isSmallCaps(), 'w:smallCaps');
        $xmlWriter->writeElementIf($style->isAllCaps(), 'w:caps');

        // Underline
        $underline = $style->getUnderline();
        $xmlWriter->writeElementIf($underline != 'none', 'w:u', 'w:val', $underline);

        // Foreground-Color
        $fgColor = $style->getFgColor();
        $xmlWriter->writeElementIf(!is_null($fgColor), 'w:highlight', 'w:val', $fgColor);

        // Superscript/subscript
        $xmlWriter->writeElementIf($style->isSuperScript(), 'w:vertAlign', 'w:val', 'superscript');
        $xmlWriter->writeElementIf($style->isSubScript(), 'w:vertAlign', 'w:val', 'subscript');

        // Background-Color
        $shading = $style->getShading();
        if (!is_null($shading)) {
            $styleWriter = new Shading($xmlWriter, $shading);
            $styleWriter->write();
        }

        $xmlWriter->endElement();
    }

    /**
     * Set is inline
     *
     * @param bool $value
     */
    public function setIsInline($value)
    {
        $this->isInline = $value;
    }
}
