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
 * @copyright   2010-2015 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Writer\Word2007\Style\Paragraph as ParagraphStyleWriter;

/**
 * ListItemRun element writer
 *
 * @since 0.10.0
 */
class ListItemRun extends AbstractElement
{
    /**
     * Write list item element.
     *
     * @return void
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof \PhpOffice\PhpWord\Element\ListItemRun) {
            return;
        }

        $xmlWriter->startElement('w:p');

        $xmlWriter->startElement('w:pPr');
        $paragraphStyle = $element->getParagraphStyle();
        $styleWriter = new ParagraphStyleWriter($xmlWriter, $paragraphStyle);
        $styleWriter->setIsInline(true);
        $styleWriter->write();

        $xmlWriter->startElement('w:numPr');
        $xmlWriter->startElement('w:ilvl');
        $xmlWriter->writeAttribute('w:val', $element->getDepth());
        $xmlWriter->endElement(); // w:ilvl
        $xmlWriter->startElement('w:numId');
        $xmlWriter->writeAttribute('w:val', $element->getStyle()->getNumId());
        $xmlWriter->endElement(); // w:numId
        $xmlWriter->endElement(); // w:numPr

        $xmlWriter->endElement(); // w:pPr

        $containerWriter = new Container($xmlWriter, $element);
        $containerWriter->write();

        $xmlWriter->endElement(); // w:p
    }
}
