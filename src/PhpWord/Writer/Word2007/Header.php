<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\Element\PreserveText;
use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextBreak;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Container\Header as HeaderElement;
use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Word2007 header part writer
 */
class Header extends Base
{
    /**
     * Write word/headerx.xml
     *
     * @param HeaderElement $header
     */
    public function writeHeader(HeaderElement $header)
    {
        // Create XML writer
        $xmlWriter = $this->getXmlWriter();

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');

        $xmlWriter->startElement('w:hdr');
        $xmlWriter->writeAttribute('xmlns:ve', 'http://schemas.openxmlformats.org/markup-compatibility/2006');
        $xmlWriter->writeAttribute('xmlns:o', 'urn:schemas-microsoft-com:office:office');
        $xmlWriter->writeAttribute('xmlns:r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $xmlWriter->writeAttribute('xmlns:m', 'http://schemas.openxmlformats.org/officeDocument/2006/math');
        $xmlWriter->writeAttribute('xmlns:v', 'urn:schemas-microsoft-com:vml');
        $xmlWriter->writeAttribute('xmlns:wp', 'http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing');
        $xmlWriter->writeAttribute('xmlns:w10', 'urn:schemas-microsoft-com:office:word');
        $xmlWriter->writeAttribute('xmlns:w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $xmlWriter->writeAttribute('xmlns:wne', 'http://schemas.microsoft.com/office/word/2006/wordml');


        $_elements = $header->getElements();

        foreach ($_elements as $element) {
            if ($element instanceof Text) {
                $this->writeText($xmlWriter, $element);
            } elseif ($element instanceof TextRun) {
                $this->writeTextRun($xmlWriter, $element);
            } elseif ($element instanceof TextBreak) {
                $this->writeTextBreak($xmlWriter, $element);
            } elseif ($element instanceof Table) {
                $this->writeTable($xmlWriter, $element);
            } elseif ($element instanceof Image) {
                if (!$element->getIsWatermark()) {
                    $this->writeImage($xmlWriter, $element);
                } else {
                    $this->writeWatermark($xmlWriter, $element);
                }
            } elseif ($element instanceof PreserveText) {
                $this->writePreserveText($xmlWriter, $element);
            }
        }

        $xmlWriter->endElement();

        // Return
        return $xmlWriter->getData();
    }
}
