<?php
declare(strict_types=1);
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

namespace PhpOffice\PhpWord\Writer\ODText\Style;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Style\Lengths\Absolute;
use PhpOffice\PhpWord\Style\Lengths\Auto;
use PhpOffice\PhpWord\Style\Lengths\Percent;
use PhpOffice\PhpWord\Style\Table as TableStyle;

/**
 * Table style writer
 *
 * @since 0.11.0
 */
class Table extends AbstractStyle
{
    /**
     * Write style.
     */
    public function write()
    {
        $style = $this->getStyle();
        if ($style === null) {
            return;
        } elseif (is_string($style)) {
            throw new Exception(sprintf('Incorrect value provided for style. `%s` expected, `string(%s)` provided', TableStyle::class, $style));
        } elseif (!$style instanceof TableStyle) {
            throw new Exception(sprintf('Incorrect value provided for style. %s expected, %s provided', TableStyle::class, get_class($style)));
        }
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startElement('style:style');
        $xmlWriter->writeAttribute('style:name', $style->getStyleName());
        $xmlWriter->writeAttribute('style:family', 'table');
        $xmlWriter->startElement('style:table-properties');
        //$xmlWriter->writeAttribute('style:width', 'table');
        $xmlWriter->writeAttribute('style:rel-width', 100);
        $xmlWriter->writeAttribute('table:align', 'center');
        $xmlWriter->writeAttributeIf($style->isBidiVisual(), 'style:writing-mode', 'rl-tb');
        $xmlWriter->endElement(); // style:table-properties
        $xmlWriter->endElement(); // style:style

        $cellWidths = $style->getColumnWidths();
        $countCellWidths = $cellWidths === null ? 0 : count($cellWidths);

        for ($i = 0; $i < $countCellWidths; $i++) {
            $width = $cellWidths[$i];
            if ($width instanceof Percent) {
                $width = number_format($width->toFloat(), 2) . '%';
            } elseif ($width instanceof Absolute) {
                $width = $width->toFloat('cm') . 'cm';
            } elseif ($width instanceof Auto) {
                $width = null;
            } else {
                throw new Exception('Unsupported width `' . get_class($width) . '` provided');
            }

            $xmlWriter->startElement('style:style');
            $xmlWriter->writeAttribute('style:name', $style->getStyleName() . '.' . $i);
            $xmlWriter->writeAttribute('style:family', 'table-column');
            $xmlWriter->startElement('style:table-column-properties');
            $xmlWriter->writeAttributeIf($width !== null, 'style:column-width', $width);
            $xmlWriter->endElement(); // style:table-column-properties
            $xmlWriter->endElement(); // style:style
        }
    }
}
