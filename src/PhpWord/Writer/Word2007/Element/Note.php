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
     * Reference type footnoteReference|endnoteReference
     *
     * @var string
     */
    protected $referenceType = 'footnoteReference';

    /**
     * Write element
     */
    public function write()
    {
        if (!$this->withoutP) {
            $this->xmlWriter->startElement('w:p');
        }
        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:rPr');
        $this->xmlWriter->startElement('w:rStyle');
        $this->xmlWriter->writeAttribute('w:val', ucfirst($this->referenceType));
        $this->xmlWriter->endElement(); // w:rStyle
        $this->xmlWriter->endElement(); // w:rPr
        $this->xmlWriter->startElement("w:{$this->referenceType}");
        $this->xmlWriter->writeAttribute('w:id', $this->element->getRelationId());
        $this->xmlWriter->endElement(); // w:$referenceType
        $this->xmlWriter->endElement(); // w:r
        if (!$this->withoutP) {
            $this->xmlWriter->endElement(); // w:p
        }
    }
}
