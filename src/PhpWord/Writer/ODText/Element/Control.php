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

use PhpOffice\PhpWord\Element\Text;

/**
 * Base writer for inline ODF form controls.
 */
abstract class Control extends AbstractElement
{
    /**
     * @param mixed $fontStyle
     */
    protected function writeControlAnchor(string $id, ?string $name, ?string $text, $fontStyle = null): void
    {
        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('draw:control');
        $xmlWriter->writeAttribute('draw:control', $id);
        $xmlWriter->writeAttribute('draw:name', $name ?: $id);
        if ($fontStyle && is_string($fontStyle)) {
            $xmlWriter->writeAttribute('draw:text-style-name', $fontStyle);
        }
        $xmlWriter->endElement();
    }

    protected function writeControlText(Text $element, ?string $text): void
    {
        if ($text === null || $text === '') {
            return;
        }
        $xmlWriter = $this->getXmlWriter();
        $fontStyle = $element->getFontStyle();
        if ($fontStyle) {
            $xmlWriter->startElement('text:span');
            if (is_string($fontStyle)) {
                $xmlWriter->writeAttribute('text:style-name', $fontStyle);
            }
        }
        $this->replaceTabs($text, $xmlWriter);
        if ($fontStyle) {
            $xmlWriter->endElement();
        }
    }
}
