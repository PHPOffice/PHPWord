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
// Native ODF mappings are used where PHPWord fields have an equivalent. Fields
// without a meaningful ODF equivalent are intentionally omitted.

namespace PhpOffice\PhpWord\Writer\ODText\Element;

/**
 * Field element writer.
 *
 * @since 0.11.0
 */
class Field extends Text
{
    /**
     * Write field element.
     */
    public function write(): void
    {
        $element = $this->getElement();
        if (!$element instanceof \PhpOffice\PhpWord\Element\Field) {
            return;
        }

        $type = strtolower($element->getType());
        switch ($type) {
            case 'date':
            case 'page':
            case 'numpages':
            case 'filename':
                $this->writeDefault($element, $type);

                break;
            case 'ref':
                $this->writeReference($element);

                break;
            case 'xe':
                $this->writeIndexMark($element);

                break;
            case 'index':
                $this->writeIndex($element);

                break;
        }
    }

    private function writeReference(\PhpOffice\PhpWord\Element\Field $element): void
    {
        $properties = $element->getProperties();
        $options = $element->getOptions();

        $this->startSpan($element);
        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('text:reference-ref');
        $xmlWriter->writeAttribute('text:ref-name', $properties['name'] ?? '');
        $xmlWriter->writeAttribute('text:reference-format', in_array('p', $options) ? 'page' : 'text');
        $xmlWriter->endElement();
        $this->endSpan();
    }

    private function writeIndexMark(\PhpOffice\PhpWord\Element\Field $element): void
    {
        $this->startSpan($element);
        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('text:alphabetical-index-mark');
        $text = $element->getText();
        if ($text instanceof \PhpOffice\PhpWord\Element\TextRun) {
            $text = $text->getText();
        }
        $xmlWriter->writeAttribute('text:string-value', $text ?? '');
        $xmlWriter->endElement();
        $this->endSpan();
    }

    private function writeIndex(\PhpOffice\PhpWord\Element\Field $element): void
    {
        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('text:alphabetical-index');
        $xmlWriter->writeAttribute('text:name', 'Alphabetical Index');
        $xmlWriter->startElement('text:index-body');
        $xmlWriter->endElement();
        $xmlWriter->endElement();
    }

    private function startSpan(\PhpOffice\PhpWord\Element\Field $element): void
    {
        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('text:span');

        $fstyle = $element->getFontStyle();
        if (is_string($fstyle)) {
            $xmlWriter->writeAttribute('text:style-name', $fstyle);
        }
    }

    private function endSpan(): void
    {
        $this->getXmlWriter()->endElement();
    }

    private function writeDefault(\PhpOffice\PhpWord\Element\Field $element, $type): void
    {
        $xmlWriter = $this->getXmlWriter();

        $this->startSpan($element);

        switch ($type) {
            case 'date':
                $xmlWriter->startElement('text:date');
                $xmlWriter->writeAttribute('text:fixed', 'false');
                $xmlWriter->endElement();

                break;
            case 'page':
                $xmlWriter->startElement('text:page-number');
                $xmlWriter->writeAttribute('text:fixed', 'false');
                $xmlWriter->endElement();

                break;
            case 'numpages':
                $xmlWriter->startElement('text:page-count');
                $xmlWriter->endElement();

                break;
            case 'filename':
                $xmlWriter->startElement('text:file-name');
                $xmlWriter->writeAttribute('text:fixed', 'false');
                $options = $element->getOptions();
                if ($options != null && in_array('Path', $options)) {
                    $xmlWriter->writeAttribute('text:display', 'full');
                } else {
                    $xmlWriter->writeAttribute('text:display', 'name');
                }
                $xmlWriter->endElement();

                break;
        }
        $this->endSpan();
    }
}
