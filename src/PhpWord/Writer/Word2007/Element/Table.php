<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Style\Cell;
use PhpOffice\PhpWord\Style\Table as TableStyle;
use PhpOffice\PhpWord\Writer\Word2007\Style\Table as TableStyleWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Cell as CellStyleWriter;

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
        $rows = $this->element->getRows();
        $rowCount = count($rows);

        if ($rowCount > 0) {
            $this->xmlWriter->startElement('w:tbl');

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
            $this->xmlWriter->startElement('w:tblGrid');
            foreach ($cellWidths as $width) {
                $this->xmlWriter->startElement('w:gridCol');
                if (!is_null($width)) {
                    $this->xmlWriter->writeAttribute('w:w', $width);
                    $this->xmlWriter->writeAttribute('w:type', 'dxa');
                }
                $this->xmlWriter->endElement();
            }
            $this->xmlWriter->endElement(); // w:tblGrid

            // Table style
            $tblStyle = $this->element->getStyle();
            $tblWidth = $this->element->getWidth();
            if ($tblStyle instanceof TableStyle) {
                $styleWriter = new TableStyleWriter($this->xmlWriter, $tblStyle);
                $styleWriter->setIsFullStyle(false);
                $styleWriter->write();
            } else {
                if (!empty($tblStyle)) {
                    $this->xmlWriter->startElement('w:tblPr');
                    $this->xmlWriter->startElement('w:tblStyle');
                    $this->xmlWriter->writeAttribute('w:val', $tblStyle);
                    $this->xmlWriter->endElement();
                    if (!is_null($tblWidth)) {
                        $this->xmlWriter->startElement('w:tblW');
                        $this->xmlWriter->writeAttribute('w:w', $tblWidth);
                        $this->xmlWriter->writeAttribute('w:type', 'pct');
                        $this->xmlWriter->endElement();
                    }
                    $this->xmlWriter->endElement();
                }
            }

            // Table rows
            for ($i = 0; $i < $rowCount; $i++) {
                $row = $rows[$i];
                $height = $row->getHeight();
                $rowStyle = $row->getStyle();
                $tblHeader = $rowStyle->getTblHeader();
                $cantSplit = $rowStyle->getCantSplit();
                $exactHeight = $rowStyle->getExactHeight();

                $this->xmlWriter->startElement('w:tr');
                if (!is_null($height) || !is_null($tblHeader) || !is_null($cantSplit)) {
                    $this->xmlWriter->startElement('w:trPr');
                    if (!is_null($height)) {
                        $this->xmlWriter->startElement('w:trHeight');
                        $this->xmlWriter->writeAttribute('w:val', $height);
                        $this->xmlWriter->writeAttribute('w:hRule', ($exactHeight ? 'exact' : 'atLeast'));
                        $this->xmlWriter->endElement();
                    }
                    if ($tblHeader) {
                        $this->xmlWriter->startElement('w:tblHeader');
                        $this->xmlWriter->writeAttribute('w:val', '1');
                        $this->xmlWriter->endElement();
                    }
                    if ($cantSplit) {
                        $this->xmlWriter->startElement('w:cantSplit');
                        $this->xmlWriter->writeAttribute('w:val', '1');
                        $this->xmlWriter->endElement();
                    }
                    $this->xmlWriter->endElement();
                }
                foreach ($row->getCells() as $cell) {
                    $cellStyle = $cell->getStyle();
                    $width = $cell->getWidth();
                    $this->xmlWriter->startElement('w:tc');
                    $this->xmlWriter->startElement('w:tcPr');
                    $this->xmlWriter->startElement('w:tcW');
                    $this->xmlWriter->writeAttribute('w:w', $width);
                    $this->xmlWriter->writeAttribute('w:type', 'dxa');
                    $this->xmlWriter->endElement(); // w:tcW
                    if ($cellStyle instanceof Cell) {
                        $styleWriter = new CellStyleWriter($this->xmlWriter, $cellStyle);
                        $styleWriter->write();
                    }
                    $this->xmlWriter->endElement(); // w:tcPr
                    $this->parentWriter->writeContainerElements($this->xmlWriter, $cell);
                    $this->xmlWriter->endElement(); // w:tc
                }
                $this->xmlWriter->endElement(); // w:tr
            }
            $this->xmlWriter->endElement();
        }
    }
}
