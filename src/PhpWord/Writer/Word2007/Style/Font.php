<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
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
        if ($color != PhpWord::DEFAULT_FONT_COLOR) {
            $this->xmlWriter->startElement('w:color');
            $this->xmlWriter->writeAttribute('w:val', $color);
            $this->xmlWriter->endElement();
        }

        // Size
        if ($size != PhpWord::DEFAULT_FONT_SIZE) {
            $this->xmlWriter->startElement('w:sz');
            $this->xmlWriter->writeAttribute('w:val', $size * 2);
            $this->xmlWriter->endElement();
            $this->xmlWriter->startElement('w:szCs');
            $this->xmlWriter->writeAttribute('w:val', $size * 2);
            $this->xmlWriter->endElement();
        }

        // Bold
        if ($this->style->getBold()) {
            $this->xmlWriter->writeElement('w:b', null);
        }

        // Italic
        if ($this->style->getItalic()) {
            $this->xmlWriter->writeElement('w:i', null);
            $this->xmlWriter->writeElement('w:iCs', null);
        }

        // Underline
        if ($this->style->getUnderline() != 'none') {
            $this->xmlWriter->startElement('w:u');
            $this->xmlWriter->writeAttribute('w:val', $this->style->getUnderline());
            $this->xmlWriter->endElement();
        }

        // Strikethrough
        if ($this->style->getStrikethrough()) {
            $this->xmlWriter->writeElement('w:strike', null);
        }

        // Double strikethrough
        if ($this->style->getDoubleStrikethrough()) {
            $this->xmlWriter->writeElement('w:dstrike', null);
        }

        // Foreground-Color
        if (!is_null($this->style->getFgColor())) {
            $this->xmlWriter->startElement('w:highlight');
            $this->xmlWriter->writeAttribute('w:val', $this->style->getFgColor());
            $this->xmlWriter->endElement();
        }

        // Background-Color
        if (!is_null($this->style->getShading())) {
            $styleWriter = new Shading($this->xmlWriter, $this->style->getShading());
            $styleWriter->write();
        }

        // Superscript/subscript
        if ($this->style->getSuperScript() || $this->style->getSubScript()) {
            $this->xmlWriter->startElement('w:vertAlign');
            $this->xmlWriter->writeAttribute('w:val', $this->style->getSuperScript() ? 'superscript' : 'subscript');
            $this->xmlWriter->endElement();
        }

        // Small caps
        if ($this->style->getSmallCaps()) {
            $this->xmlWriter->writeElement('w:smallCaps', null);
        }

        // All caps
        if ($this->style->getAllCaps()) {
            $this->xmlWriter->writeElement('w:caps', null);
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
