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

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

/**
 * Line numbering style writer
 *
 * @since 0.10.0
 */
class LineNumbering extends AbstractStyle
{
    /**
     * Write style
     *
     * The w:start seems to be zero based so we have to decrement by one
     */
    public function write()
    {
        if (!($this->style instanceof \PhpOffice\PhpWord\Style\LineNumbering)) {
            return;
        }

        $this->xmlWriter->startElement('w:lnNumType');
        $this->xmlWriter->writeAttribute('w:start', $this->style->getStart() - 1);
        $this->xmlWriter->writeAttribute('w:countBy', $this->style->getIncrement());
        $this->xmlWriter->writeAttribute('w:distance', $this->style->getDistance());
        $this->xmlWriter->writeAttribute('w:restart', $this->style->getRestart());
        $this->xmlWriter->endElement();
    }
}
