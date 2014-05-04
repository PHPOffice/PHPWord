<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\ODText\Element;

/**
 * Text element writer
 *
 * @since 0.10.0
 */
class Link extends Element
{
    /**
     * Write element
     */
    public function write()
    {
        if (!$this->withoutP) {
            $this->xmlWriter->startElement('text:p'); // text:p
        }

        $this->xmlWriter->startElement('text:a');
        $this->xmlWriter->writeAttribute('xlink:type', 'simple');
        $this->xmlWriter->writeAttribute('xlink:href', $this->element->getTarget());
        $this->xmlWriter->writeRaw($this->element->getText());
        $this->xmlWriter->endElement(); // text:a

        if (!$this->withoutP) {
            $this->xmlWriter->endElement(); // text:p
        }
    }
}
