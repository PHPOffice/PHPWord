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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\Common\XMLWriter;
use PhpOffice\PhpWord\Element\FormField as FormFieldElement;

/**
 * FormField element writer
 *
 * Note: DropDown is active when document protection is set to `forms`
 *
 * @since 0.12.0
 * @see  http://www.datypic.com/sc/ooxml/t-w_CT_FFData.html
 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
 */
class FormField extends Text
{
    /** @const int Length of filler when value is null */
    const FILLER_LENGTH = 30;

    /**
     * Write element.
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof FormFieldElement) {
            return;
        }

        $type = $element->getType();
        $instructions = array('textinput' => 'FORMTEXT', 'checkbox' => 'FORMCHECKBOX', 'dropdown' => 'FORMDROPDOWN');
        $instruction = $instructions[$type];
        $writeFormField = "write{$type}";
        $name = $element->getName();
        if ($name === null) {
            $name = $type . $element->getElementId();
        }
        $value = $element->getValue();
        if ($value === null) {
            $value = str_repeat(' ', self::FILLER_LENGTH);
        }

        $this->startElementP();

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'begin');
        $xmlWriter->startElement('w:ffData');
        $xmlWriter->writeElementBlock('w:enabled', 'w:val', 1);
        $xmlWriter->writeElementBlock('w:name', 'w:val', $name);
        $xmlWriter->writeElementBlock('w:calcOnExit', 'w:val', 0);
        $this->$writeFormField($xmlWriter, $element);
        $xmlWriter->endElement(); // w:ffData
        $xmlWriter->endElement(); // w:fldChar
        $xmlWriter->endElement(); // w:r

        $xmlWriter->startElement('w:r');
        $this->writeFontStyle();
        $xmlWriter->startElement('w:instrText');
        $xmlWriter->writeAttribute('xml:space', 'preserve');
        $xmlWriter->text("{$instruction}");
        $xmlWriter->endElement(); // w:instrText
        $xmlWriter->endElement(); // w:r

        $xmlWriter->startElement('w:r');
        $this->writeFontStyle();
        $xmlWriter->writeElementBlock('w:fldChar', 'w:fldCharType', 'separate');
        $xmlWriter->endElement(); // w:r

        $xmlWriter->startElement('w:r');
        $this->writeFontStyle();
        $xmlWriter->startElement('w:t');
        $xmlWriter->writeAttribute('xml:space', 'preserve');
        $this->writeText($value);
        $xmlWriter->endElement(); // w:t
        $xmlWriter->endElement(); // w:r

        $xmlWriter->startElement('w:r');
        $this->writeFontStyle();
        $xmlWriter->writeElementBlock('w:fldChar', 'w:fldCharType', 'end');
        $xmlWriter->endElement(); // w:r

        $this->endElementP(); // w:p
    }

    /**
     * Write textinput.
     *
     * @see  http://www.datypic.com/sc/ooxml/t-w_CT_FFTextInput.html
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Element\FormField $element
     */
    private function writeTextInput(XMLWriter $xmlWriter, FormFieldElement $element)
    {
        $default = $element->getDefault();

        $xmlWriter->startElement('w:textInput');
        $xmlWriter->writeElementBlock('w:default', 'w:val', $default);
        $xmlWriter->endElement();
    }

    /**
     * Write checkbox.
     *
     * @see  http://www.datypic.com/sc/ooxml/t-w_CT_FFCheckBox.html
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Element\FormField $element
     */
    private function writeCheckBox(XMLWriter $xmlWriter, FormFieldElement $element)
    {
        $default = $element->getDefault() ? 1 : 0;
        $value = $element->getValue();
        if ($value == null) {
            $value = $default;
        }
        $value = $value ? 1 : 0;

        $xmlWriter->startElement('w:checkBox');
        $xmlWriter->writeElementBlock('w:sizeAuto', 'w:val', '');
        $xmlWriter->writeElementBlock('w:default', 'w:val', $default);
        $xmlWriter->writeElementBlock('w:checked', 'w:val', $value);
        $xmlWriter->endElement();
    }

    /**
     * Write dropdown.
     *
     * @see  http://www.datypic.com/sc/ooxml/t-w_CT_FFDDList.html
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Element\FormField $element
     */
    private function writeDropDown(XMLWriter $xmlWriter, FormFieldElement $element)
    {
        $default = $element->getDefault();
        $value = $element->getValue();
        if ($value == null) {
            $value = $default;
        }
        $entries = $element->getEntries();

        $xmlWriter->startElement('w:ddList');
        $xmlWriter->writeElementBlock('w:result', 'w:val', $value);
        $xmlWriter->writeElementBlock('w:default', 'w:val', $default);
        foreach ($entries as $entry) {
            if ($entry == null || $entry == '') {
                $entry = str_repeat(' ', self::FILLER_LENGTH);
            }
            $xmlWriter->writeElementBlock('w:listEntry', 'w:val', $entry);
        }
        $xmlWriter->endElement();
    }
}
