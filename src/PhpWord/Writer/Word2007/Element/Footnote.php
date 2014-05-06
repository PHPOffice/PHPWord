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
 * Footnote element writer
 *
 * @since 0.10.0
 */
class Footnote extends Element
{
    /**
     * Reference type footnoteReference|endnoteReference
     *
     * @var string
     */
    protected $referenceType = 'footnoteReference';

    /**
     * Write element
     */
    public function write()
    {
        if (!$this->element instanceof \PhpOffice\PhpWord\Element\Footnote) {
            return;
        }

        if (!$this->withoutP) {
            $this->xmlWriter->startElement('w:p');
        }
        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:rPr');
        $this->xmlWriter->startElement('w:rStyle');
        $this->xmlWriter->writeAttribute('w:val', ucfirst($this->referenceType));
        $this->xmlWriter->endElement(); // w:rStyle
        $this->xmlWriter->endElement(); // w:rPr
        $this->xmlWriter->startElement("w:{$this->referenceType}");
        $this->xmlWriter->writeAttribute('w:id', $this->element->getRelationId());
        $this->xmlWriter->endElement(); // w:$referenceType
        $this->xmlWriter->endElement(); // w:r
        if (!$this->withoutP) {
            $this->xmlWriter->endElement(); // w:p
        }
    }
}
