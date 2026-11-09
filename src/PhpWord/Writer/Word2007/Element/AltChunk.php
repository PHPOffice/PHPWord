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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Element\AltChunk as AltChunkElement;

/**
 * AltChunk element writer: <w:altChunk r:id="..."/>, a block-level element
 * pointing to the embedded document (relationship type aFChunk).
 */
class AltChunk extends AbstractElement
{
    /**
     * Write altChunk element.
     */
    public function write(): void
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof AltChunkElement) {
            return;
        }

        $rId = $element->getRelationId() + ($element->isInSection() ? 6 : 0);

        $xmlWriter->startElement('w:altChunk');
        $xmlWriter->writeAttribute('r:id', 'rId' . $rId);
        $xmlWriter->endElement(); // w:altChunk
    }
}
