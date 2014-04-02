<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TOC;
use PhpOffice\PhpWord\Container\Section;
use PhpOffice\PhpWord\Element\PageBreak;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Word2007 document part writer
 */
class Document extends Base
{
    /**
     * Write word/document.xml
     *
     * @param PhpWord $phpWord
     */
    public function writeDocument(PhpWord $phpWord = null)
    {
        // Create XML writer
        $xmlWriter = $this->getXmlWriter();

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');

        // w:document
        $xmlWriter->startElement('w:document');

        $xmlWriter->writeAttribute('xmlns:ve', 'http://schemas.openxmlformats.org/markup-compatibility/2006');
        $xmlWriter->writeAttribute('xmlns:o', 'urn:schemas-microsoft-com:office:office');
        $xmlWriter->writeAttribute('xmlns:r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $xmlWriter->writeAttribute('xmlns:m', 'http://schemas.openxmlformats.org/officeDocument/2006/math');
        $xmlWriter->writeAttribute('xmlns:v', 'urn:schemas-microsoft-com:vml');
        $xmlWriter->writeAttribute('xmlns:wp', 'http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing');
        $xmlWriter->writeAttribute('xmlns:w10', 'urn:schemas-microsoft-com:office:word');
        $xmlWriter->writeAttribute('xmlns:w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $xmlWriter->writeAttribute('xmlns:wne', 'http://schemas.microsoft.com/office/word/2006/wordml');

        $xmlWriter->startElement('w:body');

        $sections = $phpWord->getSections();
        $countSections = count($sections);
        $pSection = 0;

        if ($countSections > 0) {
            foreach ($sections as $section) {
                $pSection++;

                $this->writeContainerElements($xmlWriter, $section);

                if ($pSection == $countSections) {
                    $this->writeEndSection($xmlWriter, $section);
                } else {
                    $this->writeSection($xmlWriter, $section);
                }
            }
        }

        $xmlWriter->endElement(); // End w:body
        $xmlWriter->endElement(); // End w:document

        // Return
        return $xmlWriter->getData();
    }

    /**
     * Write begin section
     *
     * @param XMLWriter $xmlWriter
     * @param Section $section
     */
    private function writeSection(XMLWriter $xmlWriter, Section $section)
    {
        $xmlWriter->startElement('w:p');
        $xmlWriter->startElement('w:pPr');
        $this->writeEndSection($xmlWriter, $section, 3);
        $xmlWriter->endElement();
        $xmlWriter->endElement();
    }

    /**
     * Write end section
     *
     * @param XMLWriter $xmlWriter
     * @param Section $section
     */
    private function writeEndSection(XMLWriter $xmlWriter, Section $section)
    {
        $settings = $section->getSettings();
        $headers = $section->getHeaders();
        $footer = $section->getFooter();
        $pgSzW = $settings->getPageSizeW();
        $pgSzH = $settings->getPageSizeH();
        $orientation = $settings->getOrientation();

        $marginTop = $settings->getMarginTop();
        $marginLeft = $settings->getMarginLeft();
        $marginRight = $settings->getMarginRight();
        $marginBottom = $settings->getMarginBottom();

        $headerHeight = $settings->getHeaderHeight();
        $footerHeight = $settings->getFooterHeight();

        $borders = $settings->getBorderSize();

        $colsNum = $settings->getColsNum();
        $colsSpace = $settings->getColsSpace();
        $breakType = $settings->getBreakType();

        $xmlWriter->startElement('w:sectPr');

        // Section break
        if (!is_null($breakType)) {
            $xmlWriter->startElement('w:type');
            $xmlWriter->writeAttribute('w:val', $breakType);
            $xmlWriter->endElement();
        }

        // Header reference
        foreach ($headers as &$header) {
            $rId = $header->getRelationId();
            $xmlWriter->startElement('w:headerReference');
            $xmlWriter->writeAttribute('w:type', $header->getType());
            $xmlWriter->writeAttribute('r:id', 'rId' . $rId);
            $xmlWriter->endElement();
        }
        if ($section->hasDifferentFirstPage()) {
            $xmlWriter->startElement('w:titlePg');
            $xmlWriter->endElement();
        }

        // Footer reference
        if (!is_null($footer)) {
            $rId = $footer->getRelationId();
            $xmlWriter->startElement('w:footerReference');
            $xmlWriter->writeAttribute('w:type', 'default');
            $xmlWriter->writeAttribute('r:id', 'rId' . $rId);
            $xmlWriter->endElement();
        }

        // Page size & orientation
        $xmlWriter->startElement('w:pgSz');
        $xmlWriter->writeAttribute('w:w', $pgSzW);
        $xmlWriter->writeAttribute('w:h', $pgSzH);
        if (!is_null($orientation) && strtolower($orientation) != 'portrait') {
            $xmlWriter->writeAttribute('w:orient', $orientation);
        }
        $xmlWriter->endElement(); // w:pgSz

        // Margins
        $xmlWriter->startElement('w:pgMar');
        $xmlWriter->writeAttribute('w:top', $marginTop);
        $xmlWriter->writeAttribute('w:right', $marginRight);
        $xmlWriter->writeAttribute('w:bottom', $marginBottom);
        $xmlWriter->writeAttribute('w:left', $marginLeft);
        $xmlWriter->writeAttribute('w:header', $headerHeight);
        $xmlWriter->writeAttribute('w:footer', $footerHeight);
        $xmlWriter->writeAttribute('w:gutter', '0');
        $xmlWriter->endElement();

        // Borders
        $hasBorders = false;
        for ($i = 0; $i < 4; $i++) {
            if (!is_null($borders[$i])) {
                $hasBorders = true;
                break;
            }
        }
        if ($hasBorders) {
            $borderColor = $settings->getBorderColor();
            $xmlWriter->startElement('w:pgBorders');
            $xmlWriter->writeAttribute('w:offsetFrom', 'page');
            $this->writeMarginBorder($xmlWriter, $borders, $borderColor, array('space' => '24'));
            $xmlWriter->endElement();
        }

        // Page numbering
        if (null !== $settings->getPageNumberingStart()) {
            $xmlWriter->startElement('w:pgNumType');
            $xmlWriter->writeAttribute('w:start', $section->getSettings()->getPageNumberingStart());
            $xmlWriter->endElement();
        }

        // Columns
        $xmlWriter->startElement('w:cols');
        $xmlWriter->writeAttribute('w:num', $colsNum);
        $xmlWriter->writeAttribute('w:space', $colsSpace);
        $xmlWriter->endElement();

        $xmlWriter->endElement();
    }

    /**
     * Write page break element
     *
     * @param XMLWriter $xmlWriter
     */
    protected function writePageBreak(XMLWriter $xmlWriter, PageBreak $pagebreak)
    {
        $xmlWriter->startElement('w:p');
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:br');
        $xmlWriter->writeAttribute('w:type', 'page');
        $xmlWriter->endElement();
        $xmlWriter->endElement();
        $xmlWriter->endElement();
    }

    /**
     * Write TOC element
     *
     * @param XMLWriter $xmlWriter
     */
    protected function writeTOC(XMLWriter $xmlWriter, TOC $toc)
    {
        $titles = TOC::getTitles();
        $styleFont = TOC::getStyleFont();

        $styleTOC = TOC::getStyleTOC();
        $fIndent = $styleTOC->getIndent();
        $tabLeader = $styleTOC->getTabLeader();
        $tabPos = $styleTOC->getTabPos();

        $isObject = ($styleFont instanceof Font) ? true : false;

        for ($i = 0; $i < count($titles); $i++) {
            $title = $titles[$i];
            $indent = ($title['depth'] - 1) * $fIndent;

            $xmlWriter->startElement('w:p');

            $xmlWriter->startElement('w:pPr');

            if ($isObject && !is_null($styleFont->getParagraphStyle())) {
                $this->writeParagraphStyle($xmlWriter, $styleFont->getParagraphStyle());
            }

            if ($indent > 0) {
                $xmlWriter->startElement('w:ind');
                $xmlWriter->writeAttribute('w:left', $indent);
                $xmlWriter->endElement();
            }

            if (!empty($styleFont) && !$isObject) {
                $xmlWriter->startElement('w:pPr');
                $xmlWriter->startElement('w:pStyle');
                $xmlWriter->writeAttribute('w:val', $styleFont);
                $xmlWriter->endElement();
                $xmlWriter->endElement();
            }

            $xmlWriter->startElement('w:tabs');
            $xmlWriter->startElement('w:tab');
            $xmlWriter->writeAttribute('w:val', 'right');
            if (!empty($tabLeader)) {
                $xmlWriter->writeAttribute('w:leader', $tabLeader);
            }
            $xmlWriter->writeAttribute('w:pos', $tabPos);
            $xmlWriter->endElement();
            $xmlWriter->endElement();

            $xmlWriter->endElement(); // w:pPr


            if ($i == 0) {
                $xmlWriter->startElement('w:r');
                $xmlWriter->startElement('w:fldChar');
                $xmlWriter->writeAttribute('w:fldCharType', 'begin');
                $xmlWriter->endElement();
                $xmlWriter->endElement();

                $xmlWriter->startElement('w:r');
                $xmlWriter->startElement('w:instrText');
                $xmlWriter->writeAttribute('xml:space', 'preserve');
                $xmlWriter->writeRaw('TOC \o "1-9" \h \z \u');
                $xmlWriter->endElement();
                $xmlWriter->endElement();

                $xmlWriter->startElement('w:r');
                $xmlWriter->startElement('w:fldChar');
                $xmlWriter->writeAttribute('w:fldCharType', 'separate');
                $xmlWriter->endElement();
                $xmlWriter->endElement();
            }

            $xmlWriter->startElement('w:hyperlink');
            $xmlWriter->writeAttribute('w:anchor', $title['anchor']);
            $xmlWriter->writeAttribute('w:history', '1');

            $xmlWriter->startElement('w:r');

            if ($isObject) {
                $this->writeFontStyle($xmlWriter, $styleFont);
            }

            $xmlWriter->startElement('w:t');
            $xmlWriter->writeRaw($title['text']);
            $xmlWriter->endElement();
            $xmlWriter->endElement();

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
            $xmlWriter->writeRaw('PAGEREF ' . $title['anchor'] . ' \h');
            $xmlWriter->endElement();
            $xmlWriter->endElement();

            $xmlWriter->startElement('w:r');
            $xmlWriter->startElement('w:fldChar');
            $xmlWriter->writeAttribute('w:fldCharType', 'end');
            $xmlWriter->endElement();
            $xmlWriter->endElement();

            $xmlWriter->endElement(); // w:hyperlink

            $xmlWriter->endElement(); // w:p
        }

        $xmlWriter->startElement('w:p');
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:fldChar');
        $xmlWriter->writeAttribute('w:fldCharType', 'end');
        $xmlWriter->endElement();
        $xmlWriter->endElement();
        $xmlWriter->endElement();
    }
}
