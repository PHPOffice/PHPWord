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

use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Writer\Word2007\Style\Font as FontStyleWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Paragraph as ParagraphStyleWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Tab as TabStyleWriter;

/**
 * TOC element writer
 *
 * @since 0.10.0
 */
class TOC extends Element
{
    /**
     * Write element
     */
    public function write()
    {
        if (!$this->element instanceof \PhpOffice\PhpWord\Element\TOC) {
            return;
        }

        $titles = $this->element->getTitles();
        $writeFieldMark = true;

        foreach ($titles as $title) {
            $this->writeTitle($title, $writeFieldMark);
            if ($writeFieldMark) {
                $writeFieldMark = false;
            }
        }

        $this->xmlWriter->startElement('w:p');
        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:fldChar');
        $this->xmlWriter->writeAttribute('w:fldCharType', 'end');
        $this->xmlWriter->endElement();
        $this->xmlWriter->endElement();
        $this->xmlWriter->endElement();
    }

    /**
     * Write title
     *
     * @param \PhpOffice\PhpWord\Element\Title $title
     * @param bool $writeFieldMark
     */
    private function writeTitle($title, $writeFieldMark)
    {
        $tocStyle = $this->element->getStyleTOC();
        $fontStyle = $this->element->getStyleFont();
        $isObject = ($fontStyle instanceof Font) ? true : false;
        $anchor = '_Toc' . ($title->getBookmarkId() + 252634154);
        $indent = ($title->getDepth() - 1) * $tocStyle->getIndent();

        $this->xmlWriter->startElement('w:p');

        // Write style and field mark
        $this->writeStyle($indent);
        if ($writeFieldMark) {
            $this->writeFieldMark();
        }

        // Hyperlink
        $this->xmlWriter->startElement('w:hyperlink');
        $this->xmlWriter->writeAttribute('w:anchor', $anchor);
        $this->xmlWriter->writeAttribute('w:history', '1');

        // Title text
        $this->xmlWriter->startElement('w:r');
        if ($isObject) {
            $styleWriter = new FontStyleWriter($this->xmlWriter, $fontStyle);
            $styleWriter->write();
        }
        $this->xmlWriter->startElement('w:t');
        $this->xmlWriter->writeRaw($title->getText());
        $this->xmlWriter->endElement();
        $this->xmlWriter->endElement(); // w:r

        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->writeElement('w:tab', null);
        $this->xmlWriter->endElement();

        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:fldChar');
        $this->xmlWriter->writeAttribute('w:fldCharType', 'begin');
        $this->xmlWriter->endElement();
        $this->xmlWriter->endElement();

        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:instrText');
        $this->xmlWriter->writeAttribute('xml:space', 'preserve');
        $this->xmlWriter->writeRaw('PAGEREF ' . $anchor . ' \h');
        $this->xmlWriter->endElement();
        $this->xmlWriter->endElement();

        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:fldChar');
        $this->xmlWriter->writeAttribute('w:fldCharType', 'end');
        $this->xmlWriter->endElement();
        $this->xmlWriter->endElement();

        $this->xmlWriter->endElement(); // w:hyperlink

        $this->xmlWriter->endElement(); // w:p
    }

    /**
     * Write style
     *
     * @param int $indent
     */
    private function writeStyle($indent)
    {
        $tocStyle = $this->element->getStyleTOC();
        $fontStyle = $this->element->getStyleFont();
        $isObject = ($fontStyle instanceof Font) ? true : false;

        $this->xmlWriter->startElement('w:pPr');

        // Paragraph
        if ($isObject && !is_null($fontStyle->getParagraphStyle())) {
            $styleWriter = new ParagraphStyleWriter($this->xmlWriter, $fontStyle->getParagraphStyle());
            $styleWriter->write();
        }

        // Font
        if (!empty($fontStyle) && !$isObject) {
            $this->xmlWriter->startElement('w:rPr');
            $this->xmlWriter->startElement('w:rStyle');
            $this->xmlWriter->writeAttribute('w:val', $fontStyle);
            $this->xmlWriter->endElement();
            $this->xmlWriter->endElement(); // w:rPr
        }

        // Tab
        $this->xmlWriter->startElement('w:tabs');
        $styleWriter = new TabStyleWriter($this->xmlWriter, $tocStyle);
        $styleWriter->write();
        $this->xmlWriter->endElement();

        // Indent
        if ($indent > 0) {
            $this->xmlWriter->startElement('w:ind');
            $this->xmlWriter->writeAttribute('w:left', $indent);
            $this->xmlWriter->endElement();
        }

        $this->xmlWriter->endElement(); // w:pPr
    }

    /**
     * Write TOC Field
     */
    private function writeFieldMark()
    {
        $minDepth = $this->element->getMinDepth();
        $maxDepth = $this->element->getMaxDepth();

        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:fldChar');
        $this->xmlWriter->writeAttribute('w:fldCharType', 'begin');
        $this->xmlWriter->endElement();
        $this->xmlWriter->endElement();

        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:instrText');
        $this->xmlWriter->writeAttribute('xml:space', 'preserve');
        $this->xmlWriter->writeRaw("TOC \o {$minDepth}-{$maxDepth} \h \z \u");
        $this->xmlWriter->endElement();
        $this->xmlWriter->endElement();

        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:fldChar');
        $this->xmlWriter->writeAttribute('w:fldCharType', 'separate');
        $this->xmlWriter->endElement();
        $this->xmlWriter->endElement();
    }
}
