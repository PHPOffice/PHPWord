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

namespace PhpOffice\PhpWord\Writer\Word2007\Part;

use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Numbering as NumberingStyle;
use PhpOffice\PhpWord\Style\NumberingLevel;
use PhpOffice\PhpWord\Writer\Word2007\Style\Shading;

/**
 * Word2007 numbering part writer: word/numbering.xml.
 *
 * @since 0.10.0
 */
class Numbering extends AbstractPart
{
    /**
     * Write part.
     *
     * @return string
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();
        $styles = Style::getStyles();
        $drawingSchema = 'http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing';

        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement('w:numbering');
        $xmlWriter->writeAttribute('xmlns:ve', 'http://schemas.openxmlformats.org/markup-compatibility/2006');
        $xmlWriter->writeAttribute('xmlns:o', 'urn:schemas-microsoft-com:office:office');
        $xmlWriter->writeAttribute('xmlns:r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $xmlWriter->writeAttribute('xmlns:m', 'http://schemas.openxmlformats.org/officeDocument/2006/math');
        $xmlWriter->writeAttribute('xmlns:v', 'urn:schemas-microsoft-com:vml');
        $xmlWriter->writeAttribute('xmlns:wp', $drawingSchema);
        $xmlWriter->writeAttribute('xmlns:w10', 'urn:schemas-microsoft-com:office:word');
        $xmlWriter->writeAttribute('xmlns:w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $xmlWriter->writeAttribute('xmlns:wne', 'http://schemas.microsoft.com/office/word/2006/wordml');

        // Abstract numbering definitions
        foreach ($styles as $style) {
            if ($style instanceof NumberingStyle) {
                $levels = $style->getLevels();

                $xmlWriter->startElement('w:abstractNum');
                $xmlWriter->writeAttribute('w:abstractNumId', $style->getIndex());

                $xmlWriter->startElement('w:nsid');
                $xmlWriter->writeAttribute('w:val', $this->getRandomHexNumber());
                $xmlWriter->endElement(); // w:nsid

                $xmlWriter->startElement('w:multiLevelType');
                $xmlWriter->writeAttribute('w:val', $style->getType());
                $xmlWriter->endElement(); // w:multiLevelType

                if (is_array($levels)) {
                    foreach ($levels as $level) {
                        $this->writeLevel($xmlWriter, $level);
                    }
                }
                $xmlWriter->endElement(); // w:abstractNum
            }
        }

        // Numbering definition instances
        foreach ($styles as $style) {
            if ($style instanceof NumberingStyle) {
                $xmlWriter->startElement('w:num');
                $xmlWriter->writeAttribute('w:numId', $style->getIndex());
                $xmlWriter->startElement('w:abstractNumId');
                $xmlWriter->writeAttribute('w:val', $style->getIndex());
                $xmlWriter->endElement(); // w:abstractNumId
                $xmlWriter->endElement(); // w:num
            }
        }

        $xmlWriter->endElement(); // w:numbering

        return $xmlWriter->getData();
    }

    /**
     * Write level.
     */
    private function writeLevel(XMLWriter $xmlWriter, NumberingLevel $level): void
    {
        $xmlWriter->startElement('w:lvl');
        $xmlWriter->writeAttribute('w:ilvl', $level->getLevel());

        // Numbering level properties
        $properties = [
            'start' => 'start',
            'format' => 'numFmt',
            'restart' => 'lvlRestart',
            'pStyle' => 'pStyle',
            'suffix' => 'suff',
            'text' => 'lvlText',
            'alignment' => 'lvlJc',
        ];
        foreach ($properties as $property => $nodeName) {
            $getMethod = "get{$property}";
            if ('' !== $level->$getMethod()         // this condition is now supported by `alignment` only
                && null !== $level->$getMethod()) {
                $xmlWriter->startElement("w:{$nodeName}");
                $xmlWriter->writeAttribute('w:val', $level->$getMethod());
                $xmlWriter->endElement(); // w:start
            }
        }

        // Paragraph & font styles
        $this->writeParagraph($xmlWriter, $level);
        $this->writeFont($xmlWriter, $level);

        $xmlWriter->endElement(); // w:lvl
    }

    /**
     * Write level paragraph.
     *
     * @since 0.11.0
     *
     * @todo Use paragraph style writer
     */
    private function writeParagraph(XMLWriter $xmlWriter, NumberingLevel $level): void
    {
        $tabPos = $level->getTabPos();
        $left = $level->getLeft();
        $hanging = $level->getHanging();

        $xmlWriter->startElement('w:pPr');

        $xmlWriter->startElement('w:tabs');
        $xmlWriter->startElement('w:tab');
        $xmlWriter->writeAttribute('w:val', 'num');
        $xmlWriter->writeAttributeIf($tabPos !== null, 'w:pos', $tabPos);
        $xmlWriter->endElement(); // w:tab
        $xmlWriter->endElement(); // w:tabs

        $xmlWriter->startElement('w:ind');
        $xmlWriter->writeAttributeIf($left !== null, 'w:left', $left);
        $xmlWriter->writeAttributeIf($hanging !== null, 'w:hanging', $hanging);
        $xmlWriter->endElement(); // w:ind

        $xmlWriter->endElement(); // w:pPr
    }

    /**
     * Write level font.
     *
     * @since 0.11.0
     *
     * @todo Use font style writer
     */
    private function writeFont(XMLWriter $xmlWriter, NumberingLevel $level): void
    {
        $font = $level->getFont();
        $hint = $level->getHint();

        $xmlWriter->startElement('w:rPr');

        $style = Style::getStyle('List '. $level->getLevel());
        if ($style instanceof Font) {
            // Color
            $color = $style->getColor();
            $xmlWriter->writeElementIf($color !== null, 'w:color', 'w:val', $color);

            // Size
            $size = $style->getSize();
            $xmlWriter->writeElementIf($size !== null, 'w:sz', 'w:val', $size * 2);
            $xmlWriter->writeElementIf($size !== null, 'w:szCs', 'w:val', $size * 2);

            // Bold, italic
            $xmlWriter->writeElementIf($style->isBold() !== null, 'w:b', 'w:val', $this->writeOnOf($style->isBold()));
            $xmlWriter->writeElementIf($style->isBold() !== null, 'w:bCs', 'w:val', $this->writeOnOf($style->isBold()));
            $xmlWriter->writeElementIf($style->isItalic() !== null, 'w:i', 'w:val', $this->writeOnOf($style->isItalic()));
            $xmlWriter->writeElementIf($style->isItalic() !== null, 'w:iCs', 'w:val', $this->writeOnOf($style->isItalic()));

            // Strikethrough, double strikethrough
            $xmlWriter->writeElementIf($style->isStrikethrough() !== null, 'w:strike', 'w:val', $this->writeOnOf($style->isStrikethrough()));
            $xmlWriter->writeElementIf($style->isDoubleStrikethrough() !== null, 'w:dstrike', 'w:val', $this->writeOnOf($style->isDoubleStrikethrough()));

            // Small caps, all caps
            $xmlWriter->writeElementIf($style->isSmallCaps() !== null, 'w:smallCaps', 'w:val', $this->writeOnOf($style->isSmallCaps()));
            $xmlWriter->writeElementIf($style->isAllCaps() !== null, 'w:caps', 'w:val', $this->writeOnOf($style->isAllCaps()));

            //Hidden text
            $xmlWriter->writeElementIf($style->isHidden(), 'w:vanish', 'w:val', $this->writeOnOf($style->isHidden()));

            // Underline
            $xmlWriter->writeElementIf($style->getUnderline() != 'none', 'w:u', 'w:val', $style->getUnderline());

            // Foreground-Color
            $xmlWriter->writeElementIf($style->getFgColor() !== null, 'w:highlight', 'w:val', $style->getFgColor());

            // Superscript/subscript
            $xmlWriter->writeElementIf($style->isSuperScript(), 'w:vertAlign', 'w:val', 'superscript');
            $xmlWriter->writeElementIf($style->isSubScript(), 'w:vertAlign', 'w:val', 'subscript');

            // Spacing
            $xmlWriter->writeElementIf($style->getScale() !== null, 'w:w', 'w:val', $style->getScale());
            $xmlWriter->writeElementIf($style->getSpacing() !== null, 'w:spacing', 'w:val', $style->getSpacing());
            $xmlWriter->writeElementIf($style->getKerning() !== null, 'w:kern', 'w:val', $style->getKerning() * 2);

            // noProof
            $xmlWriter->writeElementIf($style->isNoProof() !== null, 'w:noProof', 'w:val', $this->writeOnOf($style->isNoProof()));

            // Background-Color
            $shading = $style->getShading();
            if (null !== $shading) {
                $styleWriter = new Shading($xmlWriter, $shading);
                $styleWriter->write();
            }
        }

        $xmlWriter->startElement('w:rFonts');
        $xmlWriter->writeAttributeIf($font !== null, 'w:ascii', $font);
        $xmlWriter->writeAttributeIf($font !== null, 'w:hAnsi', $font);
        $xmlWriter->writeAttributeIf($font !== null, 'w:cs', $font);
        $xmlWriter->writeAttributeIf($hint !== null, 'w:hint', $hint);
        $xmlWriter->endElement(); // w:rFonts

        $xmlWriter->endElement(); // w:rPr
    }

    /**
     * Get random hexadecimal number value.
     *
     * @param int $length
     *
     * @return string
     */
    private function getRandomHexNumber($length = 8)
    {
        return strtoupper(substr(md5(mt_rand()), 0, $length));
    }
}
