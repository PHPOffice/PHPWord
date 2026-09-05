<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\ODText\Element;

/**
 * Footnote element writer.
 */
class Footnote extends AbstractElement
{
    /**
     * ODF note class.
     *
     * @var string
     */
    protected $noteClass = 'footnote';

    /**
     * ODF note ID prefix.
     *
     * @var string
     */
    protected $idPrefix = 'ftn';

    /**
     * Write footnote element.
     */
    public function write(): void
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof \PhpOffice\PhpWord\Element\Footnote) {
            return;
        }

        $number = $element->getRelationId() + 1;
        if (!$this->withoutP) {
            $xmlWriter->startElement('text:p');
        }

        $xmlWriter->startElement('text:note');
        $xmlWriter->writeAttribute('text:note-class', $this->noteClass);
        $xmlWriter->writeAttribute('text:id', $this->idPrefix . $number);

        $xmlWriter->startElement('text:note-citation');
        $xmlWriter->text((string) $number);
        $xmlWriter->endElement();

        $xmlWriter->startElement('text:note-body');
        $containerWriter = new Container($xmlWriter, $element);
        $containerWriter->write();
        $xmlWriter->endElement();

        $xmlWriter->endElement();

        if (!$this->withoutP) {
            $xmlWriter->endElement();
        }
    }
}
