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

use PhpOffice\PhpWord\Element\TextBox as TextBoxElement;
use PhpOffice\PhpWord\Writer\ODText\Style\Graphic;

/**
 * ODF text box element writer.
 */
class TextBox extends AbstractElement
{
    public function write(): void
    {
        $element = $this->getElement();
        if (!$element instanceof TextBoxElement) {
            return;
        }

        $xmlWriter = $this->getXmlWriter();
        $style = $element->getStyle();
        $xmlWriter->startElement('draw:frame');
        $xmlWriter->writeAttribute('draw:style-name', $style->getStyleName());
        $xmlWriter->writeAttribute('draw:name', $element->getElementId());
        $xmlWriter->writeAttribute('text:anchor-type', 'as-char');
        Graphic::writeFrameProperties($xmlWriter, $style);
        $xmlWriter->startElement('draw:text-box');
        $container = new Container($xmlWriter, $element);
        $container->setPart($this->getPart());
        $container->write();
        $xmlWriter->endElement();
        $xmlWriter->endElement();
    }
}
