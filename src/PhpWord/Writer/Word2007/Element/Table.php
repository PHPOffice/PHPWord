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

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Element\Cell as CellElement;
use PhpOffice\PhpWord\Element\Row as RowElement;
use PhpOffice\PhpWord\Style\Cell as CellStyle;
use PhpOffice\PhpWord\Style\Table as TableStyle;
use PhpOffice\PhpWord\Writer\Word2007\Style\Cell as CellStyleWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Table as TableStyleWriter;

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

        $rows = $element->getRows();
        $rowCount = count($rows);

        if ($rowCount > 0) {
            $xmlWriter->startElement('w:tbl');

            // Table grid
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
            $xmlWriter->startElement('w:tblGrid');
            foreach ($cellWidths as $width) {
                $xmlWriter->startElement('w:gridCol');
                if (!is_null($width)) {
                    $xmlWriter->writeAttribute('w:w', $width);
                    $xmlWriter->writeAttribute('w:type', 'dxa');
                }
                $xmlWriter->endElement();
            }
            $xmlWriter->endElement(); // w:tblGrid

            // Table style
            $tblStyle = $element->getStyle();
            $tblWidth = $element->getWidth();
            if ($tblStyle instanceof TableStyle) {
                $styleWriter = new TableStyleWriter($xmlWriter, $tblStyle);
                $styleWriter->setIsFullStyle(false);
                $styleWriter->write();
            } else {
                if (!empty($tblStyle)) {
                    $xmlWriter->startElement('w:tblPr');
                    $xmlWriter->startElement('w:tblStyle');
                    $xmlWriter->writeAttribute('w:val', $tblStyle);
                    $xmlWriter->endElement();
                    if (!is_null($tblWidth)) {
                        $xmlWriter->startElement('w:tblW');
                        $xmlWriter->writeAttribute('w:w', $tblWidth);
                        $xmlWriter->writeAttribute('w:type', 'pct');
                        $xmlWriter->endElement();
                    }
                    $xmlWriter->endElement();
                }
            }

            // Table rows
            for ($i = 0; $i < $rowCount; $i++) {
                $this->writeRow($rows[$i]);
            }
            $xmlWriter->endElement();
        }
    }

    /**
     * Write row
     */
    private function writeRow(RowElement $row)
    {
        $xmlWriter = $this->getXmlWriter();

        $height = $row->getHeight();
        $rowStyle = $row->getStyle();

        $xmlWriter->startElement('w:tr');
        if (!is_null($height) || $rowStyle->isTblHeader() || $rowStyle->isCantSplit()) {
            $xmlWriter->startElement('w:trPr');
            if (!is_null($height)) {
                $xmlWriter->startElement('w:trHeight');
                $xmlWriter->writeAttribute('w:val', $height);
                $xmlWriter->writeAttribute('w:hRule', ($rowStyle->isExactHeight() ? 'exact' : 'atLeast'));
                $xmlWriter->endElement();
            }
            if ($rowStyle->isTblHeader()) {
                $xmlWriter->startElement('w:tblHeader');
                $xmlWriter->writeAttribute('w:val', '1');
                $xmlWriter->endElement();
            }
            if ($rowStyle->isCantSplit()) {
                $xmlWriter->startElement('w:cantSplit');
                $xmlWriter->writeAttribute('w:val', '1');
                $xmlWriter->endElement();
            }
            $xmlWriter->endElement();
        }
        foreach ($row->getCells() as $cell) {
            $this->writeCell($cell);
        }
        $xmlWriter->endElement(); // w:tr
    }

    /**
     * Write cell
     */
    private function writeCell(CellElement $cell)
    {
        $xmlWriter = $this->getXmlWriter();

        $cellStyle = $cell->getStyle();

        $xmlWriter->startElement('w:tc');
        $xmlWriter->startElement('w:tcPr');
        $xmlWriter->startElement('w:tcW');
        $xmlWriter->writeAttribute('w:w', $cell->getWidth());
        $xmlWriter->writeAttribute('w:type', 'dxa');
        $xmlWriter->endElement(); // w:tcW
        if ($cellStyle instanceof CellStyle) {
            $styleWriter = new CellStyleWriter($xmlWriter, $cellStyle);
            $styleWriter->write();
        }
        $xmlWriter->endElement(); // w:tcPr

        $containerWriter = new Container($xmlWriter, $cell);
        $containerWriter->write();

        $xmlWriter->endElement(); // w:tc
    }
}
