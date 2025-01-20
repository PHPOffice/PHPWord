<?php

namespace PhpOffice\PhpWord\Writer\EPub3\Element;

use PhpOffice\PhpWord\Element\Image as ImageElement;

/**
 * Image element writer for EPub3.
 */
class Image extends AbstractElement
{
    /**
     * Write element.
     */
    public function write(): void
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof ImageElement) {
            return;
        }

        $mediaIndex = $element->getMediaIndex();
        $target = 'media/image' . $mediaIndex . '.' . $element->getImageType();
        
        if (!$this->withoutP) {
            $xmlWriter->startElement('p');
        }

        $xmlWriter->startElement('img');
        $xmlWriter->writeAttribute('src', $target);
        
        $style = '';
        if ($element->getStyle()->getWidth() !== null) {
            $style .= 'width:' . $element->getStyle()->getWidth() . 'px;';
        }
        if ($element->getStyle()->getHeight() !== null) {
            $style .= 'height:' . $element->getStyle()->getHeight() . 'px;';
        }
        
        if (!empty($style)) {
            $xmlWriter->writeAttribute('style', $style);
        }
        
        $xmlWriter->endElement(); // img

        if (!$this->withoutP) {
            $xmlWriter->endElement(); // p
        }
    }
}
