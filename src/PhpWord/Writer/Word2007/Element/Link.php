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
 * Link element writer
 *
 * @since 0.10.0
 */
class Link extends Element
{
    /**
     * Write link element
     */
    public function write()
    {
        $rId = $this->element->getRelationId() + ($this->element->isInSection() ? 6 : 0);
        $linkName = $this->element->getLinkName();
        if (is_null($linkName)) {
            $linkName = $this->element->getLinkSrc();
        }
        $fStyle = $this->element->getFontStyle();
        $pStyle = $this->element->getParagraphStyle();

        if (!$this->withoutP) {
            $this->xmlWriter->startElement('w:p');
            $this->parentWriter->writeInlineParagraphStyle($this->xmlWriter, $pStyle);
        }
        $this->xmlWriter->startElement('w:hyperlink');
        $this->xmlWriter->writeAttribute('r:id', 'rId' . $rId);
        $this->xmlWriter->writeAttribute('w:history', '1');
        $this->xmlWriter->startElement('w:r');
        $this->parentWriter->writeInlineFontStyle($this->xmlWriter, $fStyle);
        $this->xmlWriter->startElement('w:t');
        $this->xmlWriter->writeAttribute('xml:space', 'preserve');
        $this->xmlWriter->writeRaw($linkName);
        $this->xmlWriter->endElement(); // w:t
        $this->xmlWriter->endElement(); // w:r
        $this->xmlWriter->endElement(); // w:hyperlink
        if (!$this->withoutP) {
            $this->xmlWriter->endElement(); // w:p
        }
    }
}
