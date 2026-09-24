<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software
 * Foundation.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpOffice\PhpWord\Writer\ODText\Element;

use PhpOffice\PhpWord\Element\SDT as SDTElement;

/**
 * Structured document tag element writer.
 */
class SDT extends Text
{
    /**
     * Write an SDT as an inline ODF form control with visible fallback text.
     */
    public function write(): void
    {
        $element = $this->getElement();
        if (!$element instanceof SDTElement) {
            return;
        }

        $xmlWriter = $this->getXmlWriter();
        $value = $element->getValue();
        $text = $value === null ? 'Pick value' : (string) $value;
        $id = 'sdt-' . $element->getElementId();

        if (!$this->withoutP) {
            $xmlWriter->startElement('text:p');
            $xmlWriter->writeAttribute('text:style-name', $element->getParagraphStyle() ?: 'Normal');
        }

        $fontStyle = $element->getFontStyle();
        if ($fontStyle) {
            $xmlWriter->startElement('text:span');
            if (is_string($fontStyle)) {
                $xmlWriter->writeAttribute('text:style-name', $fontStyle);
            }
        }
        $this->replaceTabs($text, $xmlWriter);
        if ($fontStyle) {
            $xmlWriter->endElement();
        }

        $xmlWriter->startElement('draw:control');
        $xmlWriter->writeAttribute('draw:control', $id);
        $xmlWriter->writeAttribute('draw:name', $element->getAlias() ?: $id);
        if (is_string($fontStyle)) {
            $xmlWriter->writeAttribute('draw:text-style-name', $fontStyle);
        }
        $xmlWriter->endElement();

        if (!$this->withoutP) {
            $xmlWriter->endElement();
        }
    }
}
