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

namespace PhpOffice\PhpWord\Writer\HTML\Element;

use PhpOffice\PhpWord\Writer\HTML\Style\Table as TableStyleWriter;
use PhpOffice\PhpWord\Writer\HTML\Style\Cell as CellStyleWriter;

/**
 * Table element HTML writer
 *
 * @since 0.10.0
 */
class Table extends AbstractElement
{
    /**
     * Write table
     *
     * @return string
     */
    public function write()
    {
        if (!$this->element instanceof \PhpOffice\PhpWord\Element\Table) {
            return '';
        }

        $content = '';
        $rows = $this->element->getRows();
        $style = $this->getStyle(TableStyleWriter::class, $this->element);
        $rowCount = count($rows);
        if ($rowCount > 0) {
            $content .= '<table '.$style.'>' . PHP_EOL;
            foreach ($rows as $row) {
                /** @var $row \PhpOffice\PhpWord\Element\Row Type hint */
                $rowStyle = $row->getStyle();
                // $height = $row->getHeight();
                $tblHeader = $rowStyle->isTblHeader();
                $content .= '<tr>' . PHP_EOL;
                foreach ($row->getCells() as $cell) {
                    $style = $this->getStyle(CellStyleWriter::class, $cell);
                    $writer = new Container($this->parentWriter, $cell);
                    $cellTag = $tblHeader ? 'th' : 'td';
                    $content .= "<{$cellTag}{$style}>" . PHP_EOL;
                    $content .= $writer->write();
                    $content .= "</{$cellTag}>" . PHP_EOL;
                }
                $content .= '</tr>' . PHP_EOL;
            }
            $content .= '</table>' . PHP_EOL;
        }

        return $content;
    }

    private function getStyle($classname, $element)
    {
        /** @var \PhpOffice\PhpWord\Element\Cell $element Type hint */
        /** @var \PhpOffice\PhpWord\Writer\HTML\Style\Table $styleWriter Type hint */
        $styleWriter = new $classname($element->getStyle());
        $style = $styleWriter->write();
        if ($style) {
            $style = " style=\"{$style}\"";
        }

        return $style;
    }
}
