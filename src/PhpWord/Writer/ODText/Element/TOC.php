<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\ODText\Element;

use PhpOffice\PhpWord\Element\TOC as TOCElement;

/**
 * Table of contents element writer.
 */
class TOC extends AbstractElement
{
    /**
     * Write element.
     */
    public function write(): void
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof TOCElement) {
            return;
        }

        $xmlWriter->startElement('text:table-of-content');
        $fontStyle = $element->getStyleFont();
        if (is_string($fontStyle)) {
            $xmlWriter->writeAttribute('text:style-name', $fontStyle);
        }
        $xmlWriter->writeAttribute('text:name', 'Table of Contents');

        $this->writeSource($element);

        $xmlWriter->startElement('text:index-body');
        $xmlWriter->endElement();
        $xmlWriter->endElement();
    }

    private function writeSource(TOCElement $element): void
    {
        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('text:table-of-content-source');
        $xmlWriter->writeAttribute('text:index-scope', 'document');
        $xmlWriter->writeAttribute('text:use-outline-level', 'true');
        $xmlWriter->writeAttribute('text:use-index-marks', 'false');
        $xmlWriter->writeAttribute('text:use-index-source-styles', 'false');

        $maxDepth = $element->getMaxDepth();
        if ($maxDepth > 0) {
            $xmlWriter->writeAttribute('text:outline-level', $maxDepth);
            for ($level = $element->getMinDepth(); $level <= $maxDepth; ++$level) {
                $xmlWriter->startElement('text:table-of-content-entry-template');
                $xmlWriter->writeAttribute('text:outline-level', $level);
                $xmlWriter->startElement('text:index-entry-text');
                $xmlWriter->endElement();
                $xmlWriter->startElement('text:index-entry-tab-stop');
                $xmlWriter->endElement();
                $xmlWriter->startElement('text:index-entry-page-number');
                $xmlWriter->endElement();
                $xmlWriter->endElement();
            }
        }

        $xmlWriter->endElement();
    }
}
