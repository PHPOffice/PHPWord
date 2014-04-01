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
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Link;
use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\Element\TextBreak;
use PhpOffice\PhpWord\Element\PageBreak;
use PhpOffice\PhpWord\Element\ListItem;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Element\Object;
use PhpOffice\PhpWord\Element\Footnote;
use PhpOffice\PhpWord\Element\CheckBox;
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

        $_sections = $phpWord->getSections();
        $countSections = count($_sections);
        $pSection = 0;

        if ($countSections > 0) {
            foreach ($_sections as $section) {
                $pSection++;

                $_elements = $section->getElements();
                foreach ($_elements as $element) {
                    if ($element instanceof Text) {
                        $this->writeText($xmlWriter, $element);
                    } elseif ($element instanceof TextRun) {
                        $this->writeTextRun($xmlWriter, $element);
                    } elseif ($element instanceof Link) {
                        $this->writeLink($xmlWriter, $element);
                    } elseif ($element instanceof Title) {
                        $this->writeTitle($xmlWriter, $element);
                    } elseif ($element instanceof TextBreak) {
                        $this->writeTextBreak($xmlWriter, $element);
                    } elseif ($element instanceof PageBreak) {
                        $this->writePageBreak($xmlWriter);
                    } elseif ($element instanceof ListItem) {
                        $this->writeListItem($xmlWriter, $element);
                    } elseif ($element instanceof Table) {
                        $this->writeTable($xmlWriter, $element);
                    } elseif ($element instanceof Image) {
                        $this->writeImage($xmlWriter, $element);
                    } elseif ($element instanceof Object) {
                        $this->writeObject($xmlWriter, $element);
                    } elseif ($element instanceof TOC) {
                        $this->writeTOC($xmlWriter);
                    } elseif ($element instanceof Footnote) {
                        $this->writeFootnote($xmlWriter, $element);
                    } elseif ($element instanceof CheckBox) {
                        $this->writeCheckBox($xmlWriter, $element);
                    }
                }

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
        $_headers = $section->getHeaders();
        $_footer = $section->getFooter();
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

        foreach ($_headers as &$_header) {
            $rId = $_header->getRelationId();
            $xmlWriter->startElement('w:headerReference');
            $xmlWriter->writeAttribute('w:type', $_header->getType());
            $xmlWriter->writeAttribute('r:id', 'rId' . $rId);
            $xmlWriter->endElement();
        }

        if ($section->hasDifferentFirstPage()) {
            $xmlWriter->startElement('w:titlePg');
            $xmlWriter->endElement();
        }

        if (!is_null($breakType)) {
            $xmlWriter->startElement('w:type');
            $xmlWriter->writeAttribute('w:val', $breakType);
            $xmlWriter->endElement();
        }

        if (!is_null($_footer)) {
            $rId = $_footer->getRelationId();
            $xmlWriter->startElement('w:footerReference');
            $xmlWriter->writeAttribute('w:type', 'default');
            $xmlWriter->writeAttribute('r:id', 'rId' . $rId);
            $xmlWriter->endElement();
        }

        $xmlWriter->startElement('w:pgSz');
        $xmlWriter->writeAttribute('w:w', $pgSzW);
        $xmlWriter->writeAttribute('w:h', $pgSzH);

        if (!is_null($orientation) && strtolower($orientation) != 'portrait') {
            $xmlWriter->writeAttribute('w:orient', $orientation);
        }

        $xmlWriter->endElement();

        $xmlWriter->startElement('w:pgMar');
        $xmlWriter->writeAttribute('w:top', $marginTop);
        $xmlWriter->writeAttribute('w:right', $marginRight);
        $xmlWriter->writeAttribute('w:bottom', $marginBottom);
        $xmlWriter->writeAttribute('w:left', $marginLeft);
        $xmlWriter->writeAttribute('w:header', $headerHeight);
        $xmlWriter->writeAttribute('w:footer', $footerHeight);
        $xmlWriter->writeAttribute('w:gutter', '0');
        $xmlWriter->endElement();


        if (!is_null($borders[0]) || !is_null($borders[1]) || !is_null($borders[2]) || !is_null($borders[3])) {
            $borderColor = $settings->getBorderColor();

            $xmlWriter->startElement('w:pgBorders');
            $xmlWriter->writeAttribute('w:offsetFrom', 'page');

            if (!is_null($borders[0])) {
                $xmlWriter->startElement('w:top');
                $xmlWriter->writeAttribute('w:val', 'single');
                $xmlWriter->writeAttribute('w:sz', $borders[0]);
                $xmlWriter->writeAttribute('w:space', '24');
                $xmlWriter->writeAttribute('w:color', $borderColor[0]);
                $xmlWriter->endElement();
            }

            if (!is_null($borders[1])) {
                $xmlWriter->startElement('w:left');
                $xmlWriter->writeAttribute('w:val', 'single');
                $xmlWriter->writeAttribute('w:sz', $borders[1]);
                $xmlWriter->writeAttribute('w:space', '24');
                $xmlWriter->writeAttribute('w:color', $borderColor[1]);
                $xmlWriter->endElement();
            }

            if (!is_null($borders[2])) {
                $xmlWriter->startElement('w:right');
                $xmlWriter->writeAttribute('w:val', 'single');
                $xmlWriter->writeAttribute('w:sz', $borders[2]);
                $xmlWriter->writeAttribute('w:space', '24');
                $xmlWriter->writeAttribute('w:color', $borderColor[2]);
                $xmlWriter->endElement();
            }

            if (!is_null($borders[3])) {
                $xmlWriter->startElement('w:bottom');
                $xmlWriter->writeAttribute('w:val', 'single');
                $xmlWriter->writeAttribute('w:sz', $borders[3]);
                $xmlWriter->writeAttribute('w:space', '24');
                $xmlWriter->writeAttribute('w:color', $borderColor[3]);
                $xmlWriter->endElement();
            }
            $xmlWriter->endElement();
        }

        // Page numbering
        if (null !== $settings->getPageNumberingStart()) {
            $xmlWriter->startElement('w:pgNumType');
            $xmlWriter->writeAttribute('w:start', $section->getSettings()->getPageNumberingStart());
            $xmlWriter->endElement();
        }

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
    private function writePageBreak(XMLWriter $xmlWriter)
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
    private function writeTOC(XMLWriter $xmlWriter)
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
