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

use PhpOffice\PhpWord\PhpWord;

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
        if (is_null($style = $this->getStyle())) {
            return;
        }
        $xmlWriter = $this->getXmlWriter();
        $font = $style->getName();
        $color = $style->getColor();
        $size = $style->getSize();
        $underline = $style->getUnderline();
        $fgColor = $style->getFgColor();
        $hint = $style->getHint();

        $xmlWriter->startElement('w:rPr');

        // Font name/family
        if ($font != PhpWord::DEFAULT_FONT_NAME) {
            $xmlWriter->startElement('w:rFonts');
            $xmlWriter->writeAttribute('w:ascii', $font);
            $xmlWriter->writeAttribute('w:hAnsi', $font);
            $xmlWriter->writeAttribute('w:eastAsia', $font);
            $xmlWriter->writeAttribute('w:cs', $font);
            $xmlWriter->writeAttributeIf($hint != PhpWord::DEFAULT_FONT_CONTENT_TYPE, 'w:hint', $hint);
            $xmlWriter->endElement();
        }

        // Color
        $xmlWriter->writeElementIf($color != PhpWord::DEFAULT_FONT_COLOR, 'w:color', 'w:val', $color);
        $xmlWriter->writeElementIf($size != PhpWord::DEFAULT_FONT_SIZE, 'w:sz', 'w:val', $size * 2);
        $xmlWriter->writeElementIf($size != PhpWord::DEFAULT_FONT_SIZE, 'w:szCs', 'w:val', $size * 2);

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
        $xmlWriter->writeElementIf($underline != 'none', 'w:u', 'w:val', $underline);

        // Foreground-Color
        $xmlWriter->writeElementIf(!is_null($fgColor), 'w:highlight', 'w:val', $fgColor);

        // Superscript/subscript
        $xmlWriter->writeElementIf($style->isSuperScript(), 'w:vertAlign', 'w:val', 'superscript');
        $xmlWriter->writeElementIf($style->isSubScript(), 'w:vertAlign', 'w:val', 'subscript');

        // Background-Color
        if (!is_null($style->getShading())) {
            $styleWriter = new Shading($xmlWriter, $style->getShading());
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
