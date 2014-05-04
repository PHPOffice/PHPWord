<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Writer\Word2007\Style\Font as FontStyleWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Paragraph as ParagraphStyleWriter;

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
        $titles = $this->element->getTitles();
        $fontStyle = $this->element->getStyleFont();

        $tocStyle = $this->element->getStyleTOC();
        $fIndent = $tocStyle->getIndent();
        $tabLeader = $tocStyle->getTabLeader();
        $tabPos = $tocStyle->getTabPos();

        $maxDepth = $this->element->getMaxDepth();
        $minDepth = $this->element->getMinDepth();

        $isObject = ($fontStyle instanceof Font) ? true : false;

        for ($i = 0; $i < count($titles); $i++) {
            $title = $titles[$i];
            $indent = ($title['depth'] - 1) * $fIndent;

            $this->xmlWriter->startElement('w:p');

            $this->xmlWriter->startElement('w:pPr');

            if ($isObject && !is_null($fontStyle->getParagraphStyle())) {
                $styleWriter = new ParagraphStyleWriter($this->xmlWriter, $fontStyle->getParagraphStyle());
                $styleWriter->write();
            }

            if ($indent > 0) {
                $this->xmlWriter->startElement('w:ind');
                $this->xmlWriter->writeAttribute('w:left', $indent);
                $this->xmlWriter->endElement();
            }

            if (!empty($fontStyle) && !$isObject) {
                $this->xmlWriter->startElement('w:pPr');
                $this->xmlWriter->startElement('w:pStyle');
                $this->xmlWriter->writeAttribute('w:val', $fontStyle);
                $this->xmlWriter->endElement();
                $this->xmlWriter->endElement();
            }

            $this->xmlWriter->startElement('w:tabs');
            $this->xmlWriter->startElement('w:tab');
            $this->xmlWriter->writeAttribute('w:val', 'right');
            if (!empty($tabLeader)) {
                $this->xmlWriter->writeAttribute('w:leader', $tabLeader);
            }
            $this->xmlWriter->writeAttribute('w:pos', $tabPos);
            $this->xmlWriter->endElement();
            $this->xmlWriter->endElement();

            $this->xmlWriter->endElement(); // w:pPr


            if ($i == 0) {
                $this->xmlWriter->startElement('w:r');
                $this->xmlWriter->startElement('w:fldChar');
                $this->xmlWriter->writeAttribute('w:fldCharType', 'begin');
                $this->xmlWriter->endElement();
                $this->xmlWriter->endElement();

                $this->xmlWriter->startElement('w:r');
                $this->xmlWriter->startElement('w:instrText');
                $this->xmlWriter->writeAttribute('xml:space', 'preserve');
                $this->xmlWriter->writeRaw('TOC \o "' . $minDepth . '-' . $maxDepth . '" \h \z \u');
                $this->xmlWriter->endElement();
                $this->xmlWriter->endElement();

                $this->xmlWriter->startElement('w:r');
                $this->xmlWriter->startElement('w:fldChar');
                $this->xmlWriter->writeAttribute('w:fldCharType', 'separate');
                $this->xmlWriter->endElement();
                $this->xmlWriter->endElement();
            }

            $this->xmlWriter->startElement('w:hyperlink');
            $this->xmlWriter->writeAttribute('w:anchor', $title['anchor']);
            $this->xmlWriter->writeAttribute('w:history', '1');

            $this->xmlWriter->startElement('w:r');

            if ($isObject) {
                $styleWriter = new FontStyleWriter($this->xmlWriter, $fontStyle);
                $styleWriter->write();
            }

            $this->xmlWriter->startElement('w:t');
            $this->xmlWriter->writeRaw($title['text']);
            $this->xmlWriter->endElement();
            $this->xmlWriter->endElement();

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
            $this->xmlWriter->writeRaw('PAGEREF ' . $title['anchor'] . ' \h');
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

        $this->xmlWriter->startElement('w:p');
        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:fldChar');
        $this->xmlWriter->writeAttribute('w:fldCharType', 'end');
        $this->xmlWriter->endElement();
        $this->xmlWriter->endElement();
        $this->xmlWriter->endElement();
    }
}
