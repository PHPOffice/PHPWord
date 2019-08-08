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
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Element\ListItemRun as ListItemRunElement;
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
     */
    public function write()
    {
        $element = $this->getElement();

        if (!$element instanceof ListItemRunElement) {
            return;
        }

        $this->writeParagraph($element);
    }

    private function writeParagraph(ListItemRunElement $element)
    {
        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('w:p');

        $this->writeParagraphProperties($element);

        $containerWriter = new Container($xmlWriter, $element);
        $containerWriter->write();

        $xmlWriter->endElement(); // w:p
    }

    private function writeParagraphProperties(ListItemRunElement $element)
    {
        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('w:pPr');

        $styleWriter = new ParagraphStyleWriter($xmlWriter, $element->getParagraphStyle());
        $styleWriter->setIsInline(true);
        $styleWriter->setWithoutPPR(true);
        $styleWriter->write();

        $this->writeParagraphPropertiesNumbering($element);

        $xmlWriter->endElement(); // w:pPr
    }

    private function writeParagraphPropertiesNumbering(ListItemRunElement $element)
    {
        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('w:numPr');

        $xmlWriter->writeElementBlock('w:ilvl', array(
            'w:val' => $element->getDepth(),
        ));

        $xmlWriter->writeElementBlock('w:numId', array(
            'w:val' => $element->getStyle()->getNumId(),
        ));

        $xmlWriter->endElement(); // w:numPr
    }
}
