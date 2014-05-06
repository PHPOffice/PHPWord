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
        $isStyleName = $this->isInline && !is_null($this->style) && is_string($this->style);
        if ($isStyleName) {
            $this->xmlWriter->startElement('w:rPr');
            $this->xmlWriter->startElement('w:rStyle');
            $this->xmlWriter->writeAttribute('w:val', $this->style);
            $this->xmlWriter->endElement();
            $this->xmlWriter->endElement();
        } else {
            $this->writeStyle();
        }
    }

    /**
     * Write full style
     */
    private function writeStyle()
    {
        if (!($this->style instanceof \PhpOffice\PhpWord\Style\Font)) {
            return;
        }

        $font = $this->style->getName();
        $color = $this->style->getColor();
        $size = $this->style->getSize();
        $underline = $this->style->getUnderline();
        $fgColor = $this->style->getFgColor();

        $this->xmlWriter->startElement('w:rPr');

        // Font name/family
        if ($font != PhpWord::DEFAULT_FONT_NAME) {
            $this->xmlWriter->startElement('w:rFonts');
            $this->xmlWriter->writeAttribute('w:ascii', $font);
            $this->xmlWriter->writeAttribute('w:hAnsi', $font);
            $this->xmlWriter->writeAttribute('w:eastAsia', $font);
            $this->xmlWriter->writeAttribute('w:cs', $font);
            //Font Content Type
            if ($this->style->getHint() != PhpWord::DEFAULT_FONT_CONTENT_TYPE) {
                $this->xmlWriter->writeAttribute('w:hint', $this->style->getHint());
            }
            $this->xmlWriter->endElement();
        }

        // Color
        $this->writeElementIf($color != PhpWord::DEFAULT_FONT_COLOR, 'w:color', 'w:val', $color);
        $this->writeElementIf($size != PhpWord::DEFAULT_FONT_SIZE, 'w:sz', 'w:val', $size * 2);
        $this->writeElementIf($size != PhpWord::DEFAULT_FONT_SIZE, 'w:szCs', 'w:val', $size * 2);

        // Bold, italic
        $this->writeElementIf($this->style->isBold(), 'w:b');
        $this->writeElementIf($this->style->isItalic(), 'w:i');
        $this->writeElementIf($this->style->isItalic(), 'w:iCs');

        // Strikethrough, double strikethrough
        $this->writeElementIf($this->style->isStrikethrough(), 'w:strike');
        $this->writeElementIf($this->style->isDoubleStrikethrough(), 'w:dstrike');

        // Small caps, all caps
        $this->writeElementIf($this->style->isSmallCaps(), 'w:smallCaps');
        $this->writeElementIf($this->style->isAllCaps(), 'w:caps');

        // Underline
        $this->writeElementIf($underline != 'none', 'w:u', 'w:val', $underline);

        // Foreground-Color
        $this->writeElementIf(!is_null($fgColor), 'w:highlight', 'w:val', $fgColor);

        // Superscript/subscript
        $this->writeElementIf($this->style->isSuperScript(), 'w:vertAlign', 'w:val', 'superscript');
        $this->writeElementIf($this->style->isSubScript(), 'w:vertAlign', 'w:val', 'subscript');

        // Background-Color
        if (!is_null($this->style->getShading())) {
            $styleWriter = new Shading($this->xmlWriter, $this->style->getShading());
            $styleWriter->write();
        }

        $this->xmlWriter->endElement();
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
