<?php

/**
 * This file is part of PHPWord - A pure PHP library to read and write documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser General Public License.
 */

namespace PhpOffice\PhpWord\Writer\ODText\Element;

use PhpOffice\PhpWord\Element\Field as FieldElement;
use PhpOffice\PhpWord\Element\PreserveText as PreserveTextElement;

/**
 * PreserveText element writer.
 */
class PreserveText extends AbstractElement
{
    /**
     * ODF fields supported by PreserveText.
     *
     * @var string[]
     */
    private $supportedFields = ['DATE', 'FILENAME', 'NUMPAGES', 'PAGE'];

    public function write(): void
    {
        $element = $this->getElement();
        if (!$element instanceof PreserveTextElement) {
            return;
        }

        $xmlWriter = $this->getXmlWriter();
        if (!$this->withoutP) {
            $xmlWriter->startElement('text:p');
            $paragraphStyle = $element->getParagraphStyle();
            if (is_string($paragraphStyle)) {
                $xmlWriter->writeAttribute('text:style-name', $paragraphStyle);
            }
        }

        $texts = $element->getText();
        if (!is_array($texts)) {
            $texts = [$texts];
        }
        $fontStyle = $element->getFontStyle();
        $fontStyleName = is_string($fontStyle) ? $fontStyle : null;
        foreach ($texts as $text) {
            if (!is_string($text)) {
                continue;
            }
            if ($this->isSupportedField($text)) {
                $field = new FieldElement(strtoupper(substr($text, 1, -1)), [], [], null, $fontStyle);
                $fieldWriter = new Field($xmlWriter, $field, true);
                $fieldWriter->write();
            } else {
                $this->writeLiteral($text, $fontStyleName);
            }
        }

        if (!$this->withoutP) {
            $xmlWriter->endElement();
        }
    }

    private function isSupportedField(string $text): bool
    {
        return strlen($text) > 2
            && $text[0] === '{'
            && substr($text, -1) === '}'
            && in_array(strtoupper(substr($text, 1, -1)), $this->supportedFields, true);
    }

    private function writeLiteral(string $text, ?string $fontStyle): void
    {
        if ($text === '') {
            return;
        }

        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('text:span');
        if (is_string($fontStyle)) {
            $xmlWriter->writeAttribute('text:style-name', $fontStyle);
        }
        $this->replaceTabs($text, $xmlWriter);
        $xmlWriter->endElement();
    }
}
