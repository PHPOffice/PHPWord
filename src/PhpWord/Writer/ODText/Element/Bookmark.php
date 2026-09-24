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
 * Bookmark element writer.
 */
class Bookmark extends AbstractElement
{
    /**
     * Write bookmark element.
     */
    public function write(): void
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof \PhpOffice\PhpWord\Element\Bookmark) {
            return;
        }

        if (!$this->withoutP) {
            $xmlWriter->startElement('text:p');
        }

        $xmlWriter->startElement('text:bookmark');
        $xmlWriter->writeAttribute('text:name', $element->getName());
        $xmlWriter->endElement();

        if (!$this->withoutP) {
            $xmlWriter->endElement();
        }
    }
}
