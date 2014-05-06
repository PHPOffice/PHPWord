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

namespace PhpOffice\PhpWord\Writer\HTML\Element;

/**
 * Table element HTML writer
 *
 * @since 0.10.0
 */
class Table extends Element
{
    /**
     * Write table
     *
     * @return string
     */
    public function write()
    {
        if (!$this->element instanceof \PhpOffice\PhpWord\Element\Table) {
            return;
        }

        $html = '';
        $rows = $this->element->getRows();
        $rowCount = count($rows);
        if ($rowCount > 0) {
            $html .= '<table>' . PHP_EOL;
            foreach ($rows as $row) {
                // $height = $row->getHeight();
                $rowStyle = $row->getStyle();
                $tblHeader = $rowStyle->getTblHeader();
                $html .= '<tr>' . PHP_EOL;
                foreach ($row->getCells() as $cell) {
                    $cellTag = $tblHeader ? 'th' : 'td';
                    $cellContents = $cell->getElements();
                    $html .= "<{$cellTag}>" . PHP_EOL;
                    if (count($cellContents) > 0) {
                        foreach ($cellContents as $content) {
                            $writer = new Element($this->parentWriter, $content, false);
                            $html .= $writer->write();
                        }
                    } else {
                        $writer = new Element($this->parentWriter, new \PhpOffice\PhpWord\Element\TextBreak(), false);
                        $html .= $writer->write();
                    }
                    $html .= '</td>' . PHP_EOL;
                }
                $html .= '</tr>' . PHP_EOL;
            }
            $html .= '</table>' . PHP_EOL;
        }

        return $html;
    }
}
