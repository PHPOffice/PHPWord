<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\ODText\Element;

use PhpOffice\PhpWord\Element\Shape as ShapeElement;
use PhpOffice\PhpWord\Writer\ODText\Style\Graphic;

/**
 * Basic ODF shape element writer.
 */
class Shape extends AbstractElement
{
    public function write(): void
    {
        $element = $this->getElement();
        if (!$element instanceof ShapeElement || !in_array($element->getType(), ['rect', 'oval', 'polyline'], true)) {
            return;
        }

        $xmlWriter = $this->getXmlWriter();
        $style = $element->getStyle();
        $name = $element->getType() === 'oval' ? 'draw:ellipse' : 'draw:' . $element->getType();
        $xmlWriter->startElement($name);
        $xmlWriter->writeAttribute('draw:style-name', $style->getStyleName());
        $xmlWriter->writeAttribute('text:anchor-type', 'as-char');
        if ($style->getFrame() !== null) {
            Graphic::writeFrameProperties($xmlWriter, $style->getFrame());
        }
        if ($element->getType() === 'polyline') {
            $xmlWriter->writeAttributeIf($style->getPoints() !== null, 'draw:points', $style->getPoints());
        }
        $xmlWriter->endElement();
    }
}
