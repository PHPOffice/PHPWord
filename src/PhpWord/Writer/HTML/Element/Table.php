<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
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
