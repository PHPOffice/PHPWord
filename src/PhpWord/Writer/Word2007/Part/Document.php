<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Word2007 document part writer
 */
class Document extends AbstractPart
{
    /**
     * Write word/document.xml
     *
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     * @return string
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function writeDocument(PhpWord $phpWord = null)
    {
        if (is_null($phpWord)) {
            throw new Exception("No PhpWord assigned.");
        }
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
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

        $xmlWriter->endElement(); // w:body

        $xmlWriter->endElement(); // w:document

        // Return
        return $xmlWriter->getData();
    }

    /**
     * Write begin section
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Element\Section $section
     */
    private function writeSection(XMLWriter $xmlWriter, Section $section)
    {
        $xmlWriter->startElement('w:p');
        $xmlWriter->startElement('w:pPr');
        $this->writeEndSection($xmlWriter, $section);
        $xmlWriter->endElement();
        $xmlWriter->endElement();
    }

    /**
     * Write end section
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Element\Section $section
     */
    private function writeEndSection(XMLWriter $xmlWriter, Section $section)
    {
        $settings = $section->getSettings();
        $headers = $section->getHeaders();
        $footers = $section->getFooters();
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
        // Footer reference
        foreach ($footers as &$footer) {
            $rId = $footer->getRelationId();
            $xmlWriter->startElement('w:footerReference');
            $xmlWriter->writeAttribute('w:type', $footer->getType());
            $xmlWriter->writeAttribute('r:id', 'rId' . $rId);
            $xmlWriter->endElement();
        }
        // Different first page
        if ($section->hasDifferentFirstPage()) {
            $xmlWriter->startElement('w:titlePg');
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
            $mbWriter = new \PhpOffice\PhpWord\Writer\Word2007\Style\MarginBorder($xmlWriter);
            $mbWriter->setSizes($borders);
            $mbWriter->setColors($borderColor);
            $mbWriter->setAttributes(array('space' => '24'));

            $xmlWriter->startElement('w:pgBorders');
            $xmlWriter->writeAttribute('w:offsetFrom', 'page');
            $mbWriter->write();
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
}
