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

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

/**
 * Outline style writer.
 *
 * @since 0.12.0
 */
class Outline extends AbstractStyle
{
    /**
     * Write style.
     */
    public function write(): void
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Outline) {
            return;
        }
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startElement('v:stroke');
        $xmlWriter->writeAttribute('on', 't');
        $xmlWriter->writeAttributeIf($style->getColor() !== null, 'color', $style->getColor());
        $xmlWriter->writeAttributeIf($style->getWeight() !== null, 'weight', $style->getWeight() . $style->getUnit());
        $xmlWriter->writeAttributeIf($style->getDash() !== null, 'dashstyle', $style->getDash());
        $xmlWriter->writeAttributeIf($style->getLine() !== null, 'linestyle', $style->getLine());
        $xmlWriter->writeAttributeIf($style->getEndCap() !== null, 'endcap', $style->getEndCap());
        $xmlWriter->writeAttributeIf($style->getStartArrow() !== null, 'startarrow', $style->getStartArrow());
        $xmlWriter->writeAttributeIf($style->getEndArrow() !== null, 'endarrow', $style->getEndArrow());
        $xmlWriter->endElement();
    }
}
