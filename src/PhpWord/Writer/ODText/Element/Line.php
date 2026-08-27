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

namespace PhpOffice\PhpWord\Writer\ODText\Element;

use PhpOffice\PhpWord\Element\Line as LineElement;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\Line as LineStyle;

/**
 * Line element writer.
 */
class Line extends AbstractElement
{
    public function write(): void
    {
        $element = $this->getElement();
        if (!$element instanceof LineElement) {
            return;
        }

        $style = $element->getStyle() ?: new LineStyle();
        if (!$style->getStyleName()) {
            $style->setStyleName($element->getElementId());
        }
        $width = $style->getWidth() ?? 0;
        $height = $style->getHeight() ?? 0;
        $left = $style->getMarginLeft();
        $top = $style->getMarginTop();
        $x1 = $left;
        $y1 = $top;
        $x2 = $left + $width;
        $y2 = $top + $height;
        if ($style->isFlip()) {
            [$x1, $x2] = [$x2, $x1];
            [$y1, $y2] = [$y2, $y1];
        }

        $xmlWriter = $this->getXmlWriter();
        if (!$this->withoutP) {
            $xmlWriter->startElement('text:p');
        }
        $xmlWriter->startElement('draw:line');
        $xmlWriter->writeAttribute('draw:style-name', $style->getStyleName());
        $xmlWriter->writeAttribute('svg:x1', Converter::pointToCm($x1) . 'cm');
        $xmlWriter->writeAttribute('svg:y1', Converter::pointToCm($y1) . 'cm');
        $xmlWriter->writeAttribute('svg:x2', Converter::pointToCm($x2) . 'cm');
        $xmlWriter->writeAttribute('svg:y2', Converter::pointToCm($y2) . 'cm');
        $xmlWriter->endElement(); // draw:line
        if (!$this->withoutP) {
            $xmlWriter->endElement(); // text:p
        }
    }
}
