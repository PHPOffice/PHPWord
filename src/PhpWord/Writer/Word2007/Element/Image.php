<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Writer\Word2007\Style\Image as ImageStyleWriter;

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
        if ($this->element->isWatermark()) {
            $this->writeWatermark();
        } else {
            $this->writeImage();
        }
    }

    /**
     * Write image element
     */
    private function writeImage()
    {
        $rId = $this->element->getRelationId() + ($this->element->isInSection() ? 6 : 0);
        $style = $this->element->getStyle();
        $styleWriter = new ImageStyleWriter($this->xmlWriter, $style);

        if (!$this->withoutP) {
            $this->xmlWriter->startElement('w:p');
            if (!is_null($style->getAlign())) {
                $this->xmlWriter->startElement('w:pPr');
                $this->xmlWriter->startElement('w:jc');
                $this->xmlWriter->writeAttribute('w:val', $style->getAlign());
                $this->xmlWriter->endElement(); // w:jc
                $this->xmlWriter->endElement(); // w:pPr
            }
        }

        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:pict');
        $this->xmlWriter->startElement('v:shape');
        $this->xmlWriter->writeAttribute('type', '#_x0000_t75');
        $styleWriter->write();
        $this->xmlWriter->startElement('v:imagedata');
        $this->xmlWriter->writeAttribute('r:id', 'rId' . $rId);
        $this->xmlWriter->writeAttribute('o:title', '');
        $this->xmlWriter->endElement(); // v:imagedata
        $styleWriter->writeW10Wrap();
        $this->xmlWriter->endElement(); // v:shape
        $this->xmlWriter->endElement(); // w:pict
        $this->xmlWriter->endElement(); // w:r

        if (!$this->withoutP) {
            $this->xmlWriter->endElement(); // w:p
        }
    }
    /**
     * Write watermark element
     */
    private function writeWatermark()
    {
        $rId = $this->element->getRelationId();
        $style = $this->element->getStyle();
        $style->setPositioning('absolute');
        $styleWriter = new ImageStyleWriter($this->xmlWriter, $style);

        $this->xmlWriter->startElement('w:p');
        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:pict');
        $this->xmlWriter->startElement('v:shape');
        $this->xmlWriter->writeAttribute('type', '#_x0000_t75');
        $styleWriter->write();
        $this->xmlWriter->startElement('v:imagedata');
        $this->xmlWriter->writeAttribute('r:id', 'rId' . $rId);
        $this->xmlWriter->writeAttribute('o:title', '');
        $this->xmlWriter->endElement(); // v:imagedata
        $this->xmlWriter->endElement(); // v:shape
        $this->xmlWriter->endElement(); // w:pict
        $this->xmlWriter->endElement(); // w:r
        $this->xmlWriter->endElement(); // w:p
    }
}
