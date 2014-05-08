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

use PhpOffice\PhpWord\Style\TextBox as TextBoxStyle;
use PhpOffice\PhpWord\Writer\Word2007\Style\TextBox as TextBoxStyleWriter;

/**
 * TextBox element writer
 *
 */
class TextBox extends Element
{
    /**
     * Write element
     */
    public function write()
    {
        $tbxStyle = $this->element->getStyle();
        if ($tbxStyle instanceof TextBoxStyle) {
            $styleWriter = new TextBoxStyleWriter($this->xmlWriter, $tbxStyle);
            $styleWriter->write();
        }

        if (!$this->withoutP) {
            $this->xmlWriter->startElement('w:p');
            if (!is_null($tbxStyle->getAlign())) {
                $this->xmlWriter->startElement('w:pPr');
                $this->xmlWriter->startElement('w:jc');
                $this->xmlWriter->writeAttribute('w:val', $tbxStyle->getAlign());
                $this->xmlWriter->endElement(); // w:jc
                $this->xmlWriter->endElement(); // w:pPr
            }
        }

        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:pict');
        $this->xmlWriter->startElement('v:shape');
        $this->xmlWriter->writeAttribute('type', '#_x0000_t0202');
        $styleWriter->write();
        $this->xmlWriter->startElement('v:textbox');
        $margins = implode(', ', $tbxStyle->getInnerMargin());
        $this->xmlWriter->writeAttribute('inset', $margins);
        $this->xmlWriter->startElement('w:txbxContent');
        $this->xmlWriter->startElement('w:p');
        $this->parentWriter->writeContainerElements($this->xmlWriter, $this->element);
        $this->xmlWriter->endElement(); // w:p
        $this->xmlWriter->endElement(); // w:txbxContent
        $this->xmlWriter->endElement(); // v: textbox
        $styleWriter->writeW10Wrap();
        $this->xmlWriter->endElement(); // v:shape
        $this->xmlWriter->endElement(); // w:pict
        $this->xmlWriter->endElement(); // w:r

        if (!$this->withoutP) {
            $this->xmlWriter->endElement(); // w:p
        }
    }
}
