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
 * Spacing between lines and above/below paragraph style writer
 *
 * @since 0.10.0
 */
class Spacing extends AbstractStyle
{
    /**
     * Write style
     */
    public function write()
    {
        if (!($this->style instanceof \PhpOffice\PhpWord\Style\Spacing)) {
            return;
        }

        $this->xmlWriter->startElement('w:spacing');
        if (!is_null($this->style->getBefore())) {
            $this->xmlWriter->writeAttribute('w:before', $this->convertTwip($this->style->getBefore()));
        }
        if (!is_null($this->style->getAfter())) {
            $this->xmlWriter->writeAttribute('w:after', $this->convertTwip($this->style->getAfter()));
        }
        if (!is_null($this->style->getLine())) {
            $this->xmlWriter->writeAttribute('w:line', $this->style->getLine());
            $this->xmlWriter->writeAttribute('w:lineRule', $this->style->getRule());
        }
        $this->xmlWriter->endElement();
    }
}
