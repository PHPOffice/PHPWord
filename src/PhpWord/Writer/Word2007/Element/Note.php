<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

/**
 * Note element writer
 *
 * @since 0.10.0
 */
class Note extends Element
{
    /**
     * Write element
     *
     * @param string $referenceType footnoteReference|endnoteReference
     */
    public function write($referenceType = 'footnoteReference')
    {
        if (!$this->withoutP) {
            $this->xmlWriter->startElement('w:p');
        }
        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:rPr');
        $this->xmlWriter->startElement('w:rStyle');
        $this->xmlWriter->writeAttribute('w:val', ucfirst($referenceType));
        $this->xmlWriter->endElement(); // w:rStyle
        $this->xmlWriter->endElement(); // w:rPr
        $this->xmlWriter->startElement("w:{$referenceType}");
        $this->xmlWriter->writeAttribute('w:id', $this->element->getRelationId());
        $this->xmlWriter->endElement(); // w:$referenceType
        $this->xmlWriter->endElement(); // w:r
        if (!$this->withoutP) {
            $this->xmlWriter->endElement(); // w:p
        }
    }
}
