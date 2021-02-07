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

namespace PhpOffice\PhpWord\Writer\RTF\Element;

use PhpOffice\PhpWord\Element\Cell as CellElement;
use PhpOffice\PhpWord\Element\Row as RowElement;
use PhpOffice\PhpWord\Element\Table as TableElement;

/**
 * Table element RTF writer
 *
 * @since 0.11.0
 */
class Table extends AbstractElement
{
    /**
     * Write element
     *
     * @return string
     */
    public function write()
    {
        if (!$this->element instanceof TableElement) {
            return '';
        }
        $element = $this->element;
        // No nesting table for now
        if ($element->getNestedLevel() >= 1) {
            return '';
        }

        $content = '';
        $rows = $element->getRows();
        $rowCount = count($rows);

        if ($rowCount > 0) {
            $content .= '\pard' . PHP_EOL;

            for ($i = 0; $i < $rowCount; $i++) {
                $content .= '\trowd ';
                $content .= $this->writeRowDef($rows[$i]);
                $content .= PHP_EOL;
                $content .= $this->writeRow($rows[$i]);
                $content .= '\row' . PHP_EOL;
            }
            $content .= '\pard' . PHP_EOL;
        }

        return $content;
    }

    /**
     * Write column
     *
     * @param \PhpOffice\PhpWord\Element\Row $row
     * @return string
     */
    private function writeRowDef(RowElement $row)
    {
        $content = '';

        $rightMargin = 0;
        foreach ($row->getCells() as $cell) {
            $width = $cell->getWidth();
            $vMerge = $this->getVMerge($cell->getStyle()->getVMerge());
            if ($width === null) {
                $width = 720; // Arbitrary default width
            }
            $rightMargin += $width;
            $content .= "{$vMerge}\cellx{$rightMargin} ";
        }

        return $content;
    }

    /**
     * Write row
     *
     * @param \PhpOffice\PhpWord\Element\Row $row
     * @return string
     */
    private function writeRow(RowElement $row)
    {
        $content = '';

        // Write cells
        foreach ($row->getCells() as $cell) {
            $content .= $this->writeCell($cell);
        }

        return $content;
    }

    /**
     * Write cell
     *
     * @param \PhpOffice\PhpWord\Element\Cell $cell
     * @return string
     */
    private function writeCell(CellElement $cell)
    {
        $content = '\intbl' . PHP_EOL;

        // Write content
        $writer = new Container($this->parentWriter, $cell);
        $content .= $writer->write();

        $content .= '\cell' . PHP_EOL;

        return $content;
    }

    /**
     * Get vertical merge style
     *
     * @param string $value
     * @return string
     * @todo Move to style
     */
    private function getVMerge($value)
    {
        $style = '';
        if ($value == 'restart') {
            $style = '\clvmgf';
        } elseif ($value == 'continue') {
            $style = '\clvmrg';
        }

        return $style;
    }
}
