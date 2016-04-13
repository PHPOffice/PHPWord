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
 * @copyright   2010-2015 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\ODText\Element;

/**
 * Table element writer
 *
 * @since 0.10.0
 */
class Table extends AbstractElement
{
    /**
     * Write element
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof \PhpOffice\PhpWord\Element\Table) {
            return;
        }
        $rows = $element->getRows();
        $rowCount = count($rows);
        $colCount = $element->countColumns();

        if ($rowCount > 0) {
            $xmlWriter->startElement('table:table');
            $xmlWriter->writeAttribute('table:name', $element->getElementId());
            $xmlWriter->writeAttribute('table:style', $element->getElementId());

            $xmlWriter->startElement('table:table-column');
            $xmlWriter->writeAttribute('table:number-columns-repeated', $colCount);
            $xmlWriter->endElement(); // table:table-column

            foreach ($rows as $row) {
                $xmlWriter->startElement('table:table-row');
                /** @var $row \PhpOffice\PhpWord\Element\Row Type hint */
                foreach ($row->getCells() as $cell) {
                    $xmlWriter->startElement('table:table-cell');
                    $xmlWriter->writeAttribute('office:value-type', 'string');

                    $containerWriter = new Container($xmlWriter, $cell);
                    $containerWriter->write();

                    $xmlWriter->endElement(); // table:table-cell
                }
                $xmlWriter->endElement(); // table:table-row
            }
            $xmlWriter->endElement(); // table:table
        }
    }
}
