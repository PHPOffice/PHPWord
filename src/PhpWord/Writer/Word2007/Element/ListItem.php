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
        if (!$this->element instanceof \PhpOffice\PhpWord\Element\ListItem) {
            return;
        }

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
