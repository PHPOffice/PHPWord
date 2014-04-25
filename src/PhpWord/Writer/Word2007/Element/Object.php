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
 * Object element writer
 *
 * @since 0.10.0
 */
class Object extends Element
{
    /**
     * Write object element
     */
    public function write()
    {
        $rIdObject = $this->element->getRelationId() + ($this->element->isInSection() ? 6 : 0);
        $rIdImage = $this->element->getImageRelationId() + ($this->element->isInSection() ? 6 : 0);
        $shapeId = md5($rIdObject . '_' . $rIdImage);
        $objectId = $this->element->getRelationId() + 1325353440;
        $style = $this->element->getStyle();
        $align = $style->getAlign();

        if (!$this->withoutP) {
            $this->xmlWriter->startElement('w:p');
        }
        if (!is_null($align)) {
            $this->xmlWriter->startElement('w:pPr');
            $this->xmlWriter->startElement('w:jc');
            $this->xmlWriter->writeAttribute('w:val', $align);
            $this->xmlWriter->endElement();
            $this->xmlWriter->endElement();
        }
        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:object');
        $this->xmlWriter->writeAttribute('w:dxaOrig', '249');
        $this->xmlWriter->writeAttribute('w:dyaOrig', '160');
        $this->xmlWriter->startElement('v:shape');
        $this->xmlWriter->writeAttribute('id', $shapeId);
        $this->xmlWriter->writeAttribute('type', '#_x0000_t75');
        $this->xmlWriter->writeAttribute('style', 'width:104px;height:67px');
        $this->xmlWriter->writeAttribute('o:ole', '');
        $this->xmlWriter->startElement('v:imagedata');
        $this->xmlWriter->writeAttribute('r:id', 'rId' . $rIdImage);
        $this->xmlWriter->writeAttribute('o:title', '');
        $this->xmlWriter->endElement(); // v:imagedata
        $this->xmlWriter->endElement(); // v:shape
        $this->xmlWriter->startElement('o:OLEObject');
        $this->xmlWriter->writeAttribute('Type', 'Embed');
        $this->xmlWriter->writeAttribute('ProgID', 'Package');
        $this->xmlWriter->writeAttribute('ShapeID', $shapeId);
        $this->xmlWriter->writeAttribute('DrawAspect', 'Icon');
        $this->xmlWriter->writeAttribute('ObjectID', '_' . $objectId);
        $this->xmlWriter->writeAttribute('r:id', 'rId' . $rIdObject);
        $this->xmlWriter->endElement(); // o:OLEObject
        $this->xmlWriter->endElement(); // w:object
        $this->xmlWriter->endElement(); // w:r
        if (!$this->withoutP) {
            $this->xmlWriter->endElement(); // w:p
        }
    }
}
