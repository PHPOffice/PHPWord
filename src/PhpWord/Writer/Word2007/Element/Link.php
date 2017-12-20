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
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

/**
 * Link element writer
 *
 * @since 0.10.0
 */
class Link extends Text
{
    /**
     * Write link element.
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof \PhpOffice\PhpWord\Element\Link) {
            return;
        }

        $rId = $element->getRelationId() + ($element->isInSection() ? 6 : 0);

        $this->startElementP();

        $xmlWriter->startElement('w:hyperlink');
        if ($element->isInternal()) {
            $xmlWriter->writeAttribute('w:anchor', $element->getSource());
        } else {
            $xmlWriter->writeAttribute('r:id', 'rId' . $rId);
        }
        $xmlWriter->writeAttribute('w:history', '1');
        $xmlWriter->startElement('w:r');

        $this->writeFontStyle();

        $xmlWriter->startElement('w:t');
        $xmlWriter->writeAttribute('xml:space', 'preserve');
        $this->writeText($element->getText());
        $xmlWriter->endElement(); // w:t
        $xmlWriter->endElement(); // w:r
        $xmlWriter->endElement(); // w:hyperlink

        $this->endElementP(); // w:p
    }
}
