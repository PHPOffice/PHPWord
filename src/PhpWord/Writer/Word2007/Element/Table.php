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

use PhpOffice\Common\XMLWriter;
use PhpOffice\PhpWord\Element\Cell as CellElement;
use PhpOffice\PhpWord\Element\Row as RowElement;
use PhpOffice\PhpWord\Element\Table as TableElement;
use PhpOffice\PhpWord\Style\Cell as CellStyle;
use PhpOffice\PhpWord\Style\Row as RowStyle;
use PhpOffice\PhpWord\Writer\Word2007\Style\Cell as CellStyleWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Row as RowStyleWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Table as TableStyleWriter;

/**
 * Table element writer
 *
 * @since 0.10.0
 */
class Table extends AbstractElement
{
    /**
     * Write element.
     *
     * @return void
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof TableElement) {
            return;
        }

        $rows = $element->getRows();
        $rowCount = count($rows);

        if ($rowCount > 0) {
            $xmlWriter->startElement('w:tbl');

            // Write columns
            $this->writeColumns($xmlWriter, $element);

            // Write style
            $styleWriter = new TableStyleWriter($xmlWriter, $element->getStyle());
            $styleWriter->setWidth($element->getWidth());
            $styleWriter->write();

            // Write rows
            for ($i = 0; $i < $rowCount; $i++) {
                $this->writeRow($xmlWriter, $rows[$i]);
            }

            $xmlWriter->endElement(); // w:tbl
        }
    }

    /**
     * Write column.
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Element\Table $element
     * @return void
     */
    private function writeColumns(XMLWriter $xmlWriter, TableElement $element)
    {
        $cellWidths = $this->getCellWidths($element);
        $xmlWriter->startElement('w:tblGrid');
        foreach ($cellWidths as $width) {
            $xmlWriter->startElement('w:gridCol');
            if ($width !== null) {
                $xmlWriter->writeAttribute('w:w', $width);
                $xmlWriter->writeAttribute('w:type', 'dxa');
            }
            $xmlWriter->endElement();
        }
        $xmlWriter->endElement(); // w:tblGrid
    }

    /**
     * @param TableElement $element
     * @return array
     */
    private function getCellWidths(TableElement $element)
    {
        if ($element->hasDifferentCellWidths()) {
            return $this->getDifferentCellWidths($element);
        }

        $rows = $element->getRows();
        $rowCount = count($rows);

        $cellWidths = array();
        for ($i = 0; $i < $rowCount; $i++) {
            $row = $rows[$i];
            $cells = $row->getCells();
            if (count($cells) <= count($cellWidths)) {
                continue;
            }
            $cellWidths = array();
            foreach ($cells as $cell) {
                $cellWidths[] = $cell->getWidth();
            }
        }

        return $cellWidths;
    }

    /**
     * @param TableElement $element
     * @return array
     */
    private function getDifferentCellWidths(TableElement $element)
    {
        $cellWidths = array();
        $rowCellWidths = array();

        $rows = $element->getRows();
        foreach ($rows as $row) {
            $rowWidth = 0;
            $cells = $row->getCells();
            foreach ($cells as $cell) {
                $cellWidth = $cell->getWidth();
                $rowWidth += $cellWidth;

                $key = (int)$rowWidth;
                if (!isset($rowCellWidths[$key])) {
                    $rowCellWidths[$key] = $rowWidth;
                }
            }
        }
        ksort($rowCellWidths);

        $prevCellWidth = 0;
        foreach ($rowCellWidths as $rowCellWidth) {
            if (abs($rowCellWidth - $prevCellWidth) > 0.1) {
                $cellWidths[] = round($rowCellWidth - $prevCellWidth, 2);
                $prevCellWidth = $rowCellWidth;
            }
        }

        $countCellWidths = count($cellWidths);
        // set grid spans
        foreach ($rows as $row) {
            $index = 0;
            $cells = $row->getCells();
            foreach ($cells as $cell) {
                $cellWidth = $cell->getWidth();
                $gridSpan = 0;
                $totalColumnWidth = 0;

                for ($i = $index; $i < $countCellWidths; $i++) {
                    $totalColumnWidth += $cellWidths[$i];

                    if (round($totalColumnWidth, 2) <= round($cellWidth, 2)
                        || (abs($totalColumnWidth - $cellWidth) < 0.1)
                    ) {
                        $gridSpan++;
                    }
                    else {
                        $index = $i++;
                        break;
                    }
                }

                if ($gridSpan > 1) {
                    $cell->getStyle()->setGridSpan($gridSpan);
                }
            }
        }

        return $cellWidths;
    }

    /**
     * Write row.
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Element\Row $row
     * @return void
     */
    private function writeRow(XMLWriter $xmlWriter, RowElement $row)
    {
        $xmlWriter->startElement('w:tr');

        // Write style
        $rowStyle = $row->getStyle();
        if ($rowStyle instanceof RowStyle) {
            $styleWriter = new RowStyleWriter($xmlWriter, $rowStyle);
            $styleWriter->setHeight($row->getHeight());
            $styleWriter->write();
        }

        // Write cells
        foreach ($row->getCells() as $cell) {
            $this->writeCell($xmlWriter, $cell);
        }

        $xmlWriter->endElement(); // w:tr
    }

    /**
     * Write cell.
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Element\Cell $cell
     * @return void
     */
    private function writeCell(XMLWriter $xmlWriter, CellElement $cell)
    {

        $xmlWriter->startElement('w:tc');

        // Write style
        $cellStyle = $cell->getStyle();
        if ($cellStyle instanceof CellStyle) {
            $styleWriter = new CellStyleWriter($xmlWriter, $cellStyle);
            $styleWriter->setWidth($cell->getWidth());
            $styleWriter->write();
        }

        // Write content
        $containerWriter = new Container($xmlWriter, $cell);
        $containerWriter->write();

        $xmlWriter->endElement(); // w:tc
    }
}
