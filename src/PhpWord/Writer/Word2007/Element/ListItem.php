<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Writer\Word2007\Element\Element as ElementWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Paragraph as ParagraphStyleWriter;

/**
 * ListItem element writer
 *
 * @since 0.10.0
 */
class ListItem extends Element
{
    /**
     * Write list item element
     */
    public function write()
    {
        $textObject = $this->element->getTextObject();
        $depth = $this->element->getDepth();
        $numId = $this->element->getStyle()->getNumId();
        $paragraphStyle = $textObject->getParagraphStyle();
        $styleWriter = new ParagraphStyleWriter($this->xmlWriter, $paragraphStyle);
        $styleWriter->setWithoutPPR(true);
        $styleWriter->setIsInline(true);

        $this->xmlWriter->startElement('w:p');

        $this->xmlWriter->startElement('w:pPr');
        $styleWriter->write();
        $this->xmlWriter->startElement('w:numPr');
        $this->xmlWriter->startElement('w:ilvl');
        $this->xmlWriter->writeAttribute('w:val', $depth);
        $this->xmlWriter->endElement(); // w:ilvl
        $this->xmlWriter->startElement('w:numId');
        $this->xmlWriter->writeAttribute('w:val', $numId);
        $this->xmlWriter->endElement(); // w:numId
        $this->xmlWriter->endElement(); // w:numPr
        $this->xmlWriter->endElement(); // w:pPr

        $elementWriter = new ElementWriter($this->xmlWriter, $this->parentWriter, $textObject, true);
        $elementWriter->write();

        $this->xmlWriter->endElement(); // w:p
    }
}
