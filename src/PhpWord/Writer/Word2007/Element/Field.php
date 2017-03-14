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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Settings;

/**
 * Field element writer
 *
 * @since 0.11.0
 */
class Field extends Text
{
    protected static $simpleFields = array('PAGE', 'NUMPAGES', 'DATE');

    /**
     * Write field element.
     *
     * @return void
     */
    public function write()
    {
        $element   = $this->getElement();
        if (!$element instanceof \PhpOffice\PhpWord\Element\Field) {
            return;
        }

        if (in_array($element->getType(), self::$simpleFields)){
            $writeField = "writeSimpleField";
        } else {
            $type       = ucfirst(strtolower($element->getType()));
            $writeField = "write{$type}";
        }
        $this->$writeField();
    }

    /**
     * write MACROBUTTON
     */
    private function writeMacrobutton(){
        $xmlWriter = $this->getXmlWriter();
        $element   = $this->getElement();
        $properties  = $element->getProperties();

        $macroName = $properties['MacroName'];
        $displayText = $properties['DisplayText'];

        $this->startElementP();

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'begin');
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:instrText');
        $xmlWriter->writeAttribute('xml:space', 'preserve');
        $xmlWriter->text("MACROBUTTON {$macroName} ");
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->startElement('w:r');
        $this->writeFontStyle();
        $xmlWriter->startElement('w:instrText');
        if (Settings::isOutputEscapingEnabled()) {
            $xmlWriter->text($this->getText($displayText));
        } else {
            $xmlWriter->writeRaw($this->getText($displayText));
        }
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'end');
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $this->endElementP(); // w:p
    }

    /**
     * write one of 'PAGE', 'NUMPAGES' or 'DATE'
     */
    private function writeSimpleField()
    {
        $xmlWriter = $this->getXmlWriter();
        $element   = $this->getElement();

        $instruction = ' ' . $element->getType() . ' ';
        $properties  = $element->getProperties();
        foreach ($properties as $propkey => $propval) {
            switch ($propkey) {
                case 'format':
                case 'numformat':
                    $instruction .= '\* ' . $propval . ' ';
                    break;
                case 'dateformat':
                    $instruction .= '\@ "' . $propval . '" ';
                    break;
            }
        }

        $options = $element->getOptions();
        foreach ($options as $option) {
            switch ($option) {
                case 'PreserveFormat':
                    $instruction .= '\* MERGEFORMAT ';
                    break;
                case 'LunarCalendar':
                    $instruction .= '\h ';
                    break;
                case 'SakaEraCalendar':
                    $instruction .= '\s ';
                    break;
                case 'LastUsedFormat':
                    $instruction .= '\l ';
                    break;
            }
        }

        $this->startElementP();

        $xmlWriter->startElement('w:fldSimple');
        $xmlWriter->writeAttribute('w:instr', $instruction);
        $xmlWriter->startElement('w:r');
        $this->writeFontStyle();
        $xmlWriter->writeElement('w:t', '1');
        $xmlWriter->endElement(); // w:r
        $xmlWriter->endElement(); // w:fldSimple

        $this->endElementP(); // w:p
    }
}
