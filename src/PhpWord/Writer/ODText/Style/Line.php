<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @license http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\ODText\Style;

use PhpOffice\PhpWord\Style\Line as LineStyle;

/**
 * Line style writer.
 */
class Line extends AbstractStyle
{
    public function write(): void
    {
        $style = $this->getStyle();
        if (!$style instanceof LineStyle) {
            return;
        }

        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('style:style');
        $xmlWriter->writeAttribute('style:name', $style->getStyleName());
        $xmlWriter->writeAttribute('style:family', 'graphic');
        $xmlWriter->writeAttribute('style:parent-style-name', 'Graphics');
        $xmlWriter->startElement('style:graphic-properties');
        $xmlWriter->writeAttribute('draw:stroke', 'solid');
        if ($style->getColor() !== null) {
            $xmlWriter->writeAttribute('svg:stroke-color', '#' . ltrim($style->getColor(), '#'));
        }
        $xmlWriter->writeAttributeIf($style->getWeight() !== null, 'svg:stroke-width', $style->getWeight() . 'pt');
        $xmlWriter->endElement(); // style:graphic-properties
        $xmlWriter->endElement(); // style:style
    }
}
