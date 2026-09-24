<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * document files.
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

use PhpOffice\PhpWord\Element\CheckBox as CheckBoxElement;

/**
 * CheckBox element writer.
 */
class CheckBox extends Control
{
    public function write(): void
    {
        $element = $this->getElement();
        if (!$element instanceof CheckBoxElement) {
            return;
        }
        $xmlWriter = $this->getXmlWriter();
        if (!$this->withoutP) {
            $xmlWriter->startElement('text:p');
            $xmlWriter->writeAttribute('text:style-name', $element->getParagraphStyle() ?: 'Normal');
        }
        $this->writeControlText($element, $element->getText());
        $this->writeControlAnchor('control-' . $element->getElementId(), $element->getName(), $element->getText(), $element->getFontStyle());
        if (!$this->withoutP) {
            $xmlWriter->endElement();
        }
    }
}
