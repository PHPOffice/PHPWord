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
namespace PhpOffice\PhpWord\Writer\ODText\Style;

/**
 * Table column style writer
 *
 */
class Column extends AbstractStyle
{

    /**
     * Write style
     */
    public function write()
    {
        /** @var \PhpOffice\PhpWord\Style\Column $style Type hint */
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Column) {
            return;
        }
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startElement('style:style');
        $xmlWriter->writeAttribute('style:name', $style->getStyleName());
        $xmlWriter->writeAttribute('style:family', 'table-column');
        $xmlWriter->startElement('style:table-column-properties');
        if ($width = $style->getWidth()) {
            $xmlWriter->writeAttribute('style:column-width', number_format($width * 0.0017638889, 2, '.', '') . 'cm');
        }
        $xmlWriter->endElement(); // style:table-column-properties
        $xmlWriter->endElement(); // style:style
    }

}
