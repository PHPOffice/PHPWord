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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Writer\Word2007\Style\Paragraph as ParagraphStyleWriter;

/**
 * TextBreak element writer.
 *
 * @since 0.10.0
 */
class TextBreak extends Text
{
    /**
     * Write text break element.
     */
    public function write(): void
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof \PhpOffice\PhpWord\Element\TextBreak) {
            return;
        }

        if (!$this->withoutP) {
            $this->startElementP();

            $this->endElementP(); // w:p
        } else {
            // w:br is a run-level element; w:p only takes runs, hyperlinks and the like
            $xmlWriter->startElement('w:r');
            $xmlWriter->writeElement('w:br');
            $xmlWriter->endElement(); // w:r
        }
    }

    /**
     * Write the properties of the paragraph.
     *
     * `CT_P` takes one `w:pPr`, and the run properties of the paragraph mark belong
     * in it: `CT_PPr` names `w:rPr` after the children of `CT_PPrBase`, so they come
     * after the properties of the paragraph.
     */
    protected function writeParagraphStyle(): void
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof \PhpOffice\PhpWord\Element\TextBreak) {
            return;
        }
        if (!$element->hasStyle()) {
            return;
        }

        $xmlWriter->startElement('w:pPr');

        $styleWriter = new ParagraphStyleWriter($xmlWriter, $element->getParagraphStyle());
        $styleWriter->setIsInline(true);
        $styleWriter->setWithoutPPR(true);
        $styleWriter->write();

        $this->writeFontStyle();

        $xmlWriter->endElement(); // w:pPr
    }
}
