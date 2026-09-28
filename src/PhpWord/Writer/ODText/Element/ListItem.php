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

namespace PhpOffice\PhpWord\Writer\ODText\Element;

use PhpOffice\PhpWord\Element\ListItem as ListItemElement;

/**
 * ListItem element writer.
 */
class ListItem extends AbstractElement
{
    /**
     * Write list item element.
     */
    public function write(): void
    {
        $element = $this->getElement();
        if (!$element instanceof ListItemElement) {
            return;
        }
        $depth = $element->getDepth() + 1;
        $style = $element->getStyle();

        $xmlWriter = $this->getXmlWriter();

        for ($iDepth = 1; $iDepth <= $depth; ++$iDepth) {
            $xmlWriter->startElement('text:list');
            $xmlWriter->writeAttributeIf($style !== null, 'text:style-name', $style === null ? '' : $style->getNumStyle());
            $xmlWriter->startElement('text:list-item');
        }

        $textWriter = new Text($xmlWriter, $element->getTextObject(), false);
        $textWriter->setPart($this->getPart());
        $textWriter->write();

        for ($iDepth = 1; $iDepth <= $depth; ++$iDepth) {
            $xmlWriter->endElement(); // text:list-item
            $xmlWriter->endElement(); // text:list
        }
    }
}
