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

use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\Element\TOC as TOCElement;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph as ParagraphStyle;
use PhpOffice\PhpWord\Writer\Word2007\Style\Font as FontStyleWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Paragraph as ParagraphStyleWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Tab as TabStyleWriter;

/**
 * TOC element writer.
 *
 * @since 0.10.0
 */
class TOC extends AbstractElement
{
    /**
     * Write element.
     */
    public function write(): void
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof TOCElement) {
            return;
        }

        $titles = $element->getTitles();
        $writeFieldMark = true;

        foreach ($titles as $title) {
            $this->writeTitle($xmlWriter, $element, $title, $writeFieldMark);
            if ($writeFieldMark) {
                $writeFieldMark = false;
            }
        }

        $xmlWriter->startElement('w:p');
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'end');
        $xmlWriter->endElement();
        $xmlWriter->endElement();
        $xmlWriter->endElement();
    }

    /**
     * Write title.
     */
    private function writeTitle(XMLWriter $xmlWriter, TOCElement $element, Title $title, bool $writeFieldMark): void
    {
        $tocStyle = $element->getStyleTOC();
        $fontStyle = $element->getStyleFont();
        $isObject = ($fontStyle instanceof Font) ? true : false;
        $rId = $title->getRelationId();
        $indent = (int) (($title->getDepth() - 1) * $tocStyle->getIndent());

        $xmlWriter->startElement('w:p');

        // Write style and field mark
        $this->writeStyle($xmlWriter, $element, $indent);
        if ($writeFieldMark) {
            $this->writeFieldMark($xmlWriter, $element);
        }

        // Hyperlink
        $xmlWriter->startElement('w:hyperlink');
        $xmlWriter->writeAttribute('w:anchor', "_Toc{$rId}");
        $xmlWriter->writeAttribute('w:history', '1');

        // Title text
        $xmlWriter->startElement('w:r');
        if ($isObject) {
            $styleWriter = new FontStyleWriter($xmlWriter, $fontStyle);
            $styleWriter->write();
        }
        $xmlWriter->startElement('w:t');

        $titleText = $title->getText();
        $this->writeText($titleText instanceof TextRun ? $titleText->getText() : $titleText);

        $xmlWriter->endElement(); // w:t
        $xmlWriter->endElement(); // w:r

        $xmlWriter->startElement('w:r');
        $xmlWriter->writeElement('w:tab', null);
        $xmlWriter->endElement();

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'begin');
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:instrText');
        $xmlWriter->writeAttribute('xml:space', 'preserve');
        $xmlWriter->text("PAGEREF _Toc{$rId} \\h");
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        if ($title->getPageNumber() !== null) {
            $xmlWriter->startElement('w:r');
            $xmlWriter->startElement('w:fldChar');
            $xmlWriter->writeAttribute('w:fldCharType', 'separate');
            $xmlWriter->endElement();
            $xmlWriter->endElement();

            $xmlWriter->startElement('w:r');
            $xmlWriter->startElement('w:t');
            $xmlWriter->text((string) $title->getPageNumber());
            $xmlWriter->endElement();
            $xmlWriter->endElement();
        }

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'end');
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->endElement(); // w:hyperlink

        $xmlWriter->endElement(); // w:p
    }

    /**
     * Write style.
     */
    private function writeStyle(XMLWriter $xmlWriter, TOCElement $element, int $indent): void
    {
        $tocStyle = $element->getStyleTOC();
        $fontStyle = $element->getStyleFont();
        $isObject = ($fontStyle instanceof Font) ? true : false;

        // The tab stop and the indent of the entry, written where the schema names
        // them, among the other properties of the paragraph.
        $writeTabs = function () use ($xmlWriter, $tocStyle): void {
            $xmlWriter->startElement('w:tabs');
            $styleWriter = new TabStyleWriter($xmlWriter, $tocStyle);
            $styleWriter->write();
            $xmlWriter->endElement();
        };
        $writeIndent = function () use ($xmlWriter, $indent): void {
            if ($indent > 0) {
                $xmlWriter->startElement('w:ind');
                $xmlWriter->writeAttribute('w:left', $indent);
                $xmlWriter->endElement();
            }
        };

        $xmlWriter->startElement('w:pPr');

        // Paragraph
        $paragraph = $isObject ? $fontStyle->getParagraph() : null;
        if ($paragraph instanceof ParagraphStyle) {
            $styleWriter = new ParagraphStyleWriter($xmlWriter, $paragraph);
            $styleWriter->setWithoutPPR(true);
            $styleWriter->setChild('tabs', $writeTabs);
            $styleWriter->setChild('ind', $writeIndent);
            $styleWriter->write();
        } else {
            // A paragraph style that is named rather than carried brings no
            // properties of its own, so the entry writes the tab stop and the
            // indent itself.
            $writeTabs();
            $writeIndent();
        }

        // Font
        if (!empty($fontStyle) && !$isObject) {
            $xmlWriter->startElement('w:rPr');
            $xmlWriter->startElement('w:rStyle');
            $xmlWriter->writeAttribute('w:val', $fontStyle);
            $xmlWriter->endElement();
            $xmlWriter->endElement(); // w:rPr
        }

        $xmlWriter->endElement(); // w:pPr
    }

    /**
     * Write TOC Field.
     */
    private function writeFieldMark(XMLWriter $xmlWriter, TOCElement $element): void
    {
        $minDepth = $element->getMinDepth();
        $maxDepth = $element->getMaxDepth();

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'begin');
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:instrText');
        $xmlWriter->writeAttribute('xml:space', 'preserve');
        $xmlWriter->text("TOC \\o {$minDepth}-{$maxDepth} \\h \\z \\u");
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'separate');
        $xmlWriter->endElement();
        $xmlWriter->endElement();
    }
}
