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

namespace PhpOffice\PhpWord\Writer\ODText\Element;

use PhpOffice\PhpWord\Shared\Drawing;

/**
 * Image element writer
 *
 * @since 0.10.0
 */
class Image extends Element
{
    /**
     * Write element
     */
    public function write()
    {
        $mediaIndex = $this->element->getMediaIndex();
        $target = 'Pictures/' . $this->element->getTarget();
        $style = $this->element->getStyle();
        $width = Drawing::pixelsToCentimeters($style->getWidth());
        $height = Drawing::pixelsToCentimeters($style->getHeight());

        $this->xmlWriter->startElement('text:p');
        $this->xmlWriter->writeAttribute('text:style-name', 'Standard');

        $this->xmlWriter->startElement('draw:frame');
        $this->xmlWriter->writeAttribute('draw:style-name', 'fr' . $mediaIndex);
        $this->xmlWriter->writeAttribute('draw:name', $this->element->getElementId());
        $this->xmlWriter->writeAttribute('text:anchor-type', 'as-char');
        $this->xmlWriter->writeAttribute('svg:width', $width . 'cm');
        $this->xmlWriter->writeAttribute('svg:height', $height . 'cm');
        $this->xmlWriter->writeAttribute('draw:z-index', $mediaIndex);

        $this->xmlWriter->startElement('draw:image');
        $this->xmlWriter->writeAttribute('xlink:href', $target);
        $this->xmlWriter->writeAttribute('xlink:type', 'simple');
        $this->xmlWriter->writeAttribute('xlink:show', 'embed');
        $this->xmlWriter->writeAttribute('xlink:actuate', 'onLoad');
        $this->xmlWriter->endElement(); // draw:image

        $this->xmlWriter->endElement(); // draw:frame

        $this->xmlWriter->endElement(); // text:p
    }
}
