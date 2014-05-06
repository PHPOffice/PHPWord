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

use PhpOffice\PhpWord\Writer\Word2007\Style\Font as FontStyleWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Paragraph as ParagraphStyleWriter;

/**
 * Link element writer
 *
 * @since 0.10.0
 */
class Link extends Element
{
    /**
     * Write link element
     */
    public function write()
    {
        if (!$this->element instanceof \PhpOffice\PhpWord\Element\Link) {
            return;
        }

        $rId = $this->element->getRelationId() + ($this->element->isInSection() ? 6 : 0);
        $fontStyle = $this->element->getFontStyle();
        $paragraphStyle = $this->element->getParagraphStyle();

        if (!$this->withoutP) {
            $styleWriter = new ParagraphStyleWriter($this->xmlWriter, $paragraphStyle);
            $styleWriter->setIsInline(true);

            $this->xmlWriter->startElement('w:p');
            $styleWriter->write();
        }

        $styleWriter = new FontStyleWriter($this->xmlWriter, $fontStyle);
        $styleWriter->setIsInline(true);

        $this->xmlWriter->startElement('w:hyperlink');
        $this->xmlWriter->writeAttribute('r:id', 'rId' . $rId);
        $this->xmlWriter->writeAttribute('w:history', '1');
        $this->xmlWriter->startElement('w:r');
        $styleWriter->write();
        $this->xmlWriter->startElement('w:t');
        $this->xmlWriter->writeAttribute('xml:space', 'preserve');
        $this->xmlWriter->writeRaw($this->element->getText());
        $this->xmlWriter->endElement(); // w:t
        $this->xmlWriter->endElement(); // w:r
        $this->xmlWriter->endElement(); // w:hyperlink
        if (!$this->withoutP) {
            $this->xmlWriter->endElement(); // w:p
        }
    }
}
