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

        $methodName = 'write' . ucfirst(strtolower($element->getType()));
        if (method_exists($this, $methodName)) {
            $this->$methodName($element);
        } else {
            $this->writeDefault($element);
        }
    }

    private function writeDefault(\PhpOffice\PhpWord\Element\Field $element): void
    {
        $xmlWriter = $this->getXmlWriter();
        $this->startElementP();

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'begin');
        $xmlWriter->endElement(); // w:fldChar
        $xmlWriter->endElement(); // w:r

        $instruction = ' ' . $element->getType() . ' ';
        if ($element->getText() != null) {
            if (is_string($element->getText())) {
                $instruction .= '"' . $element->getText() . '" ';
                $instruction .= $this->buildPropertiesAndOptions($element);
            } else {
                $instruction .= '"';
            }
        } else {
            $instruction .= $this->buildPropertiesAndOptions($element);
        }
        $xmlWriter->startElement('w:r');
        $this->writeFontStyle();
        $xmlWriter->startElement('w:instrText');
        $xmlWriter->writeAttribute('xml:space', 'preserve');
        $xmlWriter->text($instruction);
        $xmlWriter->endElement(); // w:instrText
        $xmlWriter->endElement(); // w:r

        if ($element->getText() != null) {
            if ($element->getText() instanceof \PhpOffice\PhpWord\Element\TextRun) {
                $containerWriter = new Container($xmlWriter, $element->getText(), true);
                $containerWriter->write();

                $xmlWriter->startElement('w:r');
                $xmlWriter->startElement('w:instrText');
                $xmlWriter->text('"' . $this->buildPropertiesAndOptions($element));
                $xmlWriter->endElement(); // w:instrText
                $xmlWriter->endElement(); // w:r

                $xmlWriter->startElement('w:r');
                $xmlWriter->startElement('w:instrText');
                $xmlWriter->writeAttribute('xml:space', 'preserve');
                $xmlWriter->text(' ');
                $xmlWriter->endElement(); // w:instrText
                $xmlWriter->endElement(); // w:r
            }
        }

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'separate');
        $xmlWriter->endElement(); // w:fldChar
        $xmlWriter->endElement(); // w:r

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:rPr');
        $xmlWriter->startElement('w:noProof');
        $xmlWriter->endElement(); // w:noProof
        $xmlWriter->endElement(); // w:rPr
        $xmlWriter->writeElement('w:t', $element->getText() != null && is_string($element->getText()) ? $element->getText() : '1');
        $xmlWriter->endElement(); // w:r

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'end');
        $xmlWriter->endElement(); // w:fldChar
        $xmlWriter->endElement(); // w:r

        $this->endElementP(); // w:p
    }

    /**
     * Writes a macrobutton field.
     *
     * //TODO A lot of code duplication with general method, should maybe be refactored
     */
    protected function writeMacrobutton(\PhpOffice\PhpWord\Element\Field $element): void
    {
        $xmlWriter = $this->getXmlWriter();
        $this->startElementP();

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'begin');
        $xmlWriter->endElement(); // w:fldChar
        $xmlWriter->endElement(); // w:r

        $instruction = ' ' . $element->getType() . ' ' . $this->buildPropertiesAndOptions($element);
        if (is_string($element->getText())) {
            $instruction .= $element->getText() . ' ';
        }

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:instrText');
        $xmlWriter->writeAttribute('xml:space', 'preserve');
        $xmlWriter->text($instruction);
        $xmlWriter->endElement(); // w:instrText
        $xmlWriter->endElement(); // w:r

        if ($element->getText() != null) {
            if ($element->getText() instanceof \PhpOffice\PhpWord\Element\TextRun) {
                $containerWriter = new Container($xmlWriter, $element->getText(), true);
                $containerWriter->write();
            }
        }

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'end');
        $xmlWriter->endElement(); // w:fldChar
        $xmlWriter->endElement(); // w:r

        $this->endElementP(); // w:p
    }

    private function buildPropertiesAndOptions(\PhpOffice\PhpWord\Element\Field $element)
    {
        $propertiesAndOptions = '';
        $properties = $element->getProperties();
        foreach ($properties as $propkey => $propval) {
            switch ($propkey) {
                case 'format':
                    $propertiesAndOptions .= '\\* ' . $propval . ' ';

                    break;
                case 'numformat':
                    $propertiesAndOptions .= '\\# ' . $propval . ' ';

                    break;
                case 'dateformat':
                    $propertiesAndOptions .= '\\@ "' . $propval . '" ';

                    break;
                case 'macroname':
                    $propertiesAndOptions .= $propval . ' ';

                    break;
                default:
                    $propertiesAndOptions .= '"' . $propval . '" ';

                    break;
            }
        }

        $options = $element->getOptions();
        foreach ($options as $option) {
            switch ($option) {
                case 'PreserveFormat':
                    $propertiesAndOptions .= '\\* MERGEFORMAT ';

                    break;
                case 'LunarCalendar':
                    $propertiesAndOptions .= '\\h ';

                    break;
                case 'SakaEraCalendar':
                    $propertiesAndOptions .= '\\s ';

                    break;
                case 'LastUsedFormat':
                    $propertiesAndOptions .= '\\l ';

                    break;
                case 'Bold':
                    $propertiesAndOptions .= '\\b ';

                    break;
                case 'Italic':
                    $propertiesAndOptions .= '\\i ';

                    break;
                case 'Path':
                    $propertiesAndOptions .= '\\p ';

                    break;
                default:
                    $propertiesAndOptions .= $option . ' ';
            }
        }

        return $propertiesAndOptions;
    }
}
