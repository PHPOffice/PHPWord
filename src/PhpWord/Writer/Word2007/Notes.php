<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\Element\Footnote;
use PhpOffice\PhpWord\Element\Endnote;
use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Word2007 footnotes part writer
 */
class Notes extends Base
{
    /**
     * Write word/(footnotes|endnotes).xml
     *
     * @param array $elements
     * @param string $notesTypes
     */
    public function writeNotes($elements, $notesTypes = 'footnotes')
    {
        $isFootnote = $notesTypes == 'footnotes';
        $rootNode = $isFootnote ? 'w:footnotes' : 'w:endnotes';
        $elementNode = $isFootnote ? 'w:footnote' : 'w:endnote';
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement($rootNode);
        $xmlWriter->writeAttribute('xmlns:ve', 'http://schemas.openxmlformats.org/markup-compatibility/2006');
        $xmlWriter->writeAttribute('xmlns:o', 'urn:schemas-microsoft-com:office:office');
        $xmlWriter->writeAttribute('xmlns:r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $xmlWriter->writeAttribute('xmlns:m', 'http://schemas.openxmlformats.org/officeDocument/2006/math');
        $xmlWriter->writeAttribute('xmlns:v', 'urn:schemas-microsoft-com:vml');
        $xmlWriter->writeAttribute('xmlns:wp', 'http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing');
        $xmlWriter->writeAttribute('xmlns:w10', 'urn:schemas-microsoft-com:office:word');
        $xmlWriter->writeAttribute('xmlns:w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $xmlWriter->writeAttribute('xmlns:wne', 'http://schemas.microsoft.com/office/word/2006/wordml');

        // Separator and continuation separator
        $xmlWriter->startElement($elementNode);
        $xmlWriter->writeAttribute('w:id', -1);
        $xmlWriter->writeAttribute('w:type', 'separator');
        $xmlWriter->startElement('w:p');
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:separator');
        $xmlWriter->endElement(); // w:separator
        $xmlWriter->endElement(); // w:r
        $xmlWriter->endElement(); // w:p
        $xmlWriter->endElement(); // $elementNode
        $xmlWriter->startElement($elementNode);
        $xmlWriter->writeAttribute('w:id', 0);
        $xmlWriter->writeAttribute('w:type', 'continuationSeparator');
        $xmlWriter->startElement('w:p');
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:continuationSeparator');
        $xmlWriter->endElement(); // w:continuationSeparator
        $xmlWriter->endElement(); // w:r
        $xmlWriter->endElement(); // w:p
        $xmlWriter->endElement(); // $elementNode

        // Content
        foreach ($elements as $element) {
            if ($element instanceof Footnote || $element instanceof Endnote) {
                $this->writeNote($xmlWriter, $element, $notesTypes);
            }
        }

        $xmlWriter->endElement(); // $rootNode

        return $xmlWriter->getData();
    }

    /**
     * Write note item
     *
     * @param XMLWriter $xmlWriter
     * @param Footnote|Endnote $element
     * @param string $notesTypes
     */
    protected function writeNote(XMLWriter $xmlWriter, $element, $notesTypes = 'footnotes')
    {
        $isFootnote = ($notesTypes == 'footnotes');
        $elementNode = $isFootnote ? 'w:footnote' : 'w:endnote';
        $refNode = $isFootnote ? 'w:footnoteRef' : 'w:endnoteRef';
        $styleName = $isFootnote ? 'FootnoteReference' : 'EndnoteReference';

        $xmlWriter->startElement($elementNode);
        $xmlWriter->writeAttribute('w:id', $element->getRelationId());
        $xmlWriter->startElement('w:p');

        // Paragraph style
        $styleParagraph = $element->getParagraphStyle();
        $this->writeInlineParagraphStyle($xmlWriter, $styleParagraph);

        // Reference symbol
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:rPr');
        $xmlWriter->startElement('w:rStyle');
        $xmlWriter->writeAttribute('w:val', $styleName);
        $xmlWriter->endElement(); // w:rStyle
        $xmlWriter->endElement(); // w:rPr
        $xmlWriter->writeElement($refNode);
        $xmlWriter->endElement(); // w:r

        // Empty space after refence symbol
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:t');
        $xmlWriter->writeAttribute('xml:space', 'preserve');
        $xmlWriter->writeRaw(' ');
        $xmlWriter->endElement(); // w:t
        $xmlWriter->endElement(); // w:r

        $this->writeContainerElements($xmlWriter, $element);

        $xmlWriter->endElement(); // w:p
        $xmlWriter->endElement(); // $elementNode
    }
}
