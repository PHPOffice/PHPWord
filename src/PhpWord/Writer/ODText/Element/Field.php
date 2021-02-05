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
// Not fully implemented
//     - supports only PAGE and NUMPAGES
//     - supports only default formats and options
//     - supports style only if specified by name
//     - spaces before and after field may be dropped

namespace PhpOffice\PhpWord\Writer\ODText\Element;

/**
 * Field element writer
 *
 * @since 0.11.0
 */
class Field extends Text
{
    /**
     * Write field element.
     */
    public function write()
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
                $this->writeDefault($element, $type);
                break;
        }
    }

    private function writeDefault(\PhpOffice\PhpWord\Element\Field $element, $type)
    {
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startElement('text:span');
        if (method_exists($element, 'getFontStyle')) {
            $fstyle = $element->getFontStyle();
            if (is_string($fstyle)) {
                $xmlWriter->writeAttribute('text:style-name', $fstyle);
            }
        }
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
        }
        $xmlWriter->endElement(); // text:span
    }
}
