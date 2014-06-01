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

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

/**
 * PageBreak element writer
 *
 * Originally, page break is rendered as a `w:p`, but this turns out to produce bug #150.
 * As of 0.11.0, page break is rendered as a `w:r` with `w:br` type "page" and `w:lastRenderedPageBreak`
 *
 * @since 0.10.0
 */
class PageBreak extends AbstractElement
{
    /**
     * Write element
     *
     * @usedby \PhpOffice\PhpWord\Writer\Word2007\Element\Text::writeOpeningWP()
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:br');
        $xmlWriter->writeAttribute('w:type', 'page');
        $xmlWriter->endElement(); // w:br
        $xmlWriter->writeElement('w:lastRenderedPageBreak');
        $xmlWriter->endElement(); // w:r
    }
}
