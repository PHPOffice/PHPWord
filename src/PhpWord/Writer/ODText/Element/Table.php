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
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\ODText\Element;

use PhpOffice\PhpWord\Element\TextBreak as TextBreakElement;
use PhpOffice\PhpWord\Writer\ODText\Element\Element as ElementWriter;

/**
 * Table element writer
 *
 * @since 0.10.0
 */
class Table extends Element
{
    /**
     * Write element
     */
    public function write()
    {
        if (!$this->element instanceof \PhpOffice\PhpWord\Element\Table) {
            return;
        }

        $rows = $this->element->getRows();
        $rowCount = count($rows);
        $colCount = $this->element->countColumns();
        if ($rowCount > 0) {
            $this->xmlWriter->startElement('table:table');
            $this->xmlWriter->writeAttribute('table:name', $this->element->getElementId());
            $this->xmlWriter->writeAttribute('table:style', $this->element->getElementId());

            $this->xmlWriter->startElement('table:table-column');
            $this->xmlWriter->writeAttribute('table:number-columns-repeated', $colCount);
            $this->xmlWriter->endElement(); // table:table-column

            foreach ($rows as $row) {
                $this->xmlWriter->startElement('table:table-row');
                foreach ($row->getCells() as $cell) {
                    $this->xmlWriter->startElement('table:table-cell');
                    $this->xmlWriter->writeAttribute('office:value-type', 'string');
                    $elements = $cell->getElements();
                    if (count($elements) > 0) {
                        foreach ($elements as $element) {
                            $elementWriter = new ElementWriter($this->xmlWriter, $this->parentWriter, $element);
                            $elementWriter->write();
                        }
                    } else {
                        $element = new TextBreakElement();
                        $elementWriter = new ElementWriter($this->xmlWriter, $this->parentWriter, $element);
                        $elementWriter->write();
                    }
                    $this->xmlWriter->endElement(); // table:table-cell
                }
                $this->xmlWriter->endElement(); // table:table-row
            }
            $this->xmlWriter->endElement(); // table:table
        }
    }
}
