<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\Section\Footnote;
use PhpOffice\PhpWord\Section\Text;
use PhpOffice\PhpWord\Section\Link;
use PhpOffice\PhpWord\Section\TextBreak;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Word2007 footnotes part writer
 */
class Footnotes extends Base
{
    /**
     * Write word/footnotes.xml
     *
     * @param array $allFootnotesCollection
     */
    public function writeFootnotes($allFootnotesCollection)
    {
        // Create XML writer
        $xmlWriter = $this->getXmlWriter();

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement('w:footnotes');
        $xmlWriter->writeAttribute(
            'xmlns:r',
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships'
        );
        $xmlWriter->writeAttribute(
            'xmlns:w',
            'http://schemas.openxmlformats.org/wordprocessingml/2006/main'
        );
        // Separator and continuation separator
        $xmlWriter->startElement('w:footnote');
        $xmlWriter->writeAttribute('w:id', 0);
        $xmlWriter->writeAttribute('w:type', 'separator');
        $xmlWriter->startElement('w:p');
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:separator');
        $xmlWriter->endElement(); // w:separator
        $xmlWriter->endElement(); // w:r
        $xmlWriter->endElement(); // w:p
        $xmlWriter->endElement(); // w:footnote
        // Content
        $xmlWriter->startElement('w:footnote');
        $xmlWriter->writeAttribute('w:id', 1);
        $xmlWriter->writeAttribute('w:type', 'continuationSeparator');
        $xmlWriter->startElement('w:p');
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:continuationSeparator');
        $xmlWriter->endElement(); // w:continuationSeparator
        $xmlWriter->endElement(); // w:r
        $xmlWriter->endElement(); // w:p
        $xmlWriter->endElement(); // w:footnote
        foreach ($allFootnotesCollection as $footnote) {
            if ($footnote instanceof Footnote) {
                $this->writeFootnote($xmlWriter, $footnote);
            }
        }
        $xmlWriter->endElement();

        return $xmlWriter->getData();
    }

    /**
     * Write footnote content, overrides method in parent class
     *
     * @param PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param PhpOffice\PhpWord\Section\Footnote $footnote
     */
    private function writeFootnote(XMLWriter $xmlWriter, Footnote $footnote)
    {
        $xmlWriter->startElement('w:footnote');
        $xmlWriter->writeAttribute('w:id', $footnote->getReferenceId());
        $xmlWriter->startElement('w:p');
        // Paragraph style
        $paragraphStyle = $footnote->getParagraphStyle();
        $spIsObject = ($paragraphStyle instanceof Paragraph) ? true : false;
        if ($spIsObject) {
            $this->_writeParagraphStyle($xmlWriter, $paragraphStyle);
        } elseif (!$spIsObject && !is_null($paragraphStyle)) {
            $xmlWriter->startElement('w:pPr');
            $xmlWriter->startElement('w:pStyle');
            $xmlWriter->writeAttribute('w:val', $paragraphStyle);
            $xmlWriter->endElement();
            $xmlWriter->endElement();
        }
        // Reference symbol
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:rPr');
        $xmlWriter->startElement('w:rStyle');
        $xmlWriter->writeAttribute('w:val', 'FootnoteReference');
        $xmlWriter->endElement(); // w:rStyle
        $xmlWriter->endElement(); // w:rPr
        $xmlWriter->writeElement('w:footnoteRef');
        $xmlWriter->endElement(); // w:r
        // Empty space after refence symbol
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:t');
        $xmlWriter->writeAttribute('xml:space', 'preserve');
        $xmlWriter->writeRaw(' ');
        $xmlWriter->endElement(); // w:t
        $xmlWriter->endElement(); // w:r
        // Actual footnote contents
        $elements = $footnote->getElements();
        if (count($elements) > 0) {
            foreach ($elements as $element) {
                if ($element instanceof Text) {
                    $this->_writeText($xmlWriter, $element, true);
                } elseif ($element instanceof Link) {
                    $this->_writeLink($xmlWriter, $element, true);
                } elseif ($element instanceof TextBreak) {
                    $xmlWriter->writeElement('w:br');
                }
            }
        }
        $xmlWriter->endElement(); // w:p
        $xmlWriter->endElement(); // w:footnote
    }
}
