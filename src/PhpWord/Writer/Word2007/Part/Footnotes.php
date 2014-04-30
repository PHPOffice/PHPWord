<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Part;

use PhpOffice\PhpWord\Element\Footnote;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Paragraph as ParagraphStyleWriter;

/**
 * Word2007 footnotes part writer
 */
class Footnotes extends AbstractPart
{
    /**
     * Name of XML root element
     *
     * @var string
     */
    protected $rootNode = 'w:footnotes';

    /**
     * Name of XML node element
     *
     * @var string
     */
    protected $elementNode = 'w:footnote';

    /**
     * Name of XML reference element
     *
     * @var string
     */
    protected $refNode = 'w:footnoteRef';

    /**
     * Reference style name
     *
     * @var string
     */
    protected $refStyle = 'FootnoteReference';

    /**
     * Write word/(footnotes|endnotes).xml
     *
     * @param array $elements
     */
    public function write($elements)
    {
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement($this->rootNode);
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
        $xmlWriter->startElement($this->elementNode);
        $xmlWriter->writeAttribute('w:id', -1);
        $xmlWriter->writeAttribute('w:type', 'separator');
        $xmlWriter->startElement('w:p');
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:separator');
        $xmlWriter->endElement(); // w:separator
        $xmlWriter->endElement(); // w:r
        $xmlWriter->endElement(); // w:p
        $xmlWriter->endElement(); // $this->elementNode
        $xmlWriter->startElement($this->elementNode);
        $xmlWriter->writeAttribute('w:id', 0);
        $xmlWriter->writeAttribute('w:type', 'continuationSeparator');
        $xmlWriter->startElement('w:p');
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:continuationSeparator');
        $xmlWriter->endElement(); // w:continuationSeparator
        $xmlWriter->endElement(); // w:r
        $xmlWriter->endElement(); // w:p
        $xmlWriter->endElement(); // $this->elementNode

        // Content
        foreach ($elements as $element) {
            if ($element instanceof Footnote) {
                $this->writeNote($xmlWriter, $element);
            }
        }

        $xmlWriter->endElement(); // $this->rootNode

        return $xmlWriter->getData();
    }

    /**
     * Write note item
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Element\Footnote|\PhpOffice\PhpWord\Element\Endnote $element
     */
    protected function writeNote(XMLWriter $xmlWriter, $element)
    {
        $xmlWriter->startElement($this->elementNode);
        $xmlWriter->writeAttribute('w:id', $element->getRelationId());
        $xmlWriter->startElement('w:p');

        // Paragraph style
        $styleWriter = new ParagraphStyleWriter($xmlWriter, $element->getParagraphStyle());
        $styleWriter->setIsInline(true);
        $styleWriter->write();

        // Reference symbol
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:rPr');
        $xmlWriter->startElement('w:rStyle');
        $xmlWriter->writeAttribute('w:val', $this->refStyle);
        $xmlWriter->endElement(); // w:rStyle
        $xmlWriter->endElement(); // w:rPr
        $xmlWriter->writeElement($this->refNode);
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
        $xmlWriter->endElement(); // $this->elementNode
    }
}
