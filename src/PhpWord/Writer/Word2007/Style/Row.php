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
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

/**
 * Row style writer
 *
 * @since 0.11.0
 */
class Row extends AbstractStyle
{
    /**
     * @var int Row height
     */
    private $height;

    /**
     * Write style.
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Row) {
            return;
        }

        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('w:trPr');

        if ($this->height !== null) {
            $xmlWriter->startElement('w:trHeight');
            $xmlWriter->writeAttribute('w:val', $this->height);
            $xmlWriter->writeAttribute('w:hRule', ($style->isExactHeight() ? 'exact' : 'atLeast'));
            $xmlWriter->endElement();
        }
        $xmlWriter->writeElementIf($style->isTblHeader(), 'w:tblHeader', 'w:val', '1');
        $xmlWriter->writeElementIf($style->isCantSplit(), 'w:cantSplit', 'w:val', '1');

        $xmlWriter->endElement(); // w:trPr
    }

    /**
     * Set height.
     *
     * @param int $value
     */
    public function setHeight($value = null)
    {
        $this->height = $value;
    }
}
