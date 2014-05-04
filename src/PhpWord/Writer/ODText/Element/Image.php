<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
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
