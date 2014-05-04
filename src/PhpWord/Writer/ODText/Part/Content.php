<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\ODText\Part;

use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Writer\ODText\Element\Element as ElementWriter;

/**
 * ODText content part writer
 */
class Content extends AbstractPart
{
    /**
     * Write content file to XML format
     *
     * @param  \PhpOffice\PhpWord\PhpWord $phpWord
     * @return string XML Output
     */
    public function writeContent(PhpWord $phpWord = null)
    {
        if (is_null($phpWord)) {
            throw new Exception("No PhpWord assigned.");
        }
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startDocument('1.0', 'UTF-8');
        $xmlWriter->startElement('office:document-content');
        $this->writeCommonRootAttributes($xmlWriter);
        $xmlWriter->writeAttribute('xmlns:xforms', 'http://www.w3.org/2002/xforms');
        $xmlWriter->writeAttribute('xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
        $xmlWriter->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xmlWriter->writeAttribute('xmlns:field', 'urn:openoffice:names:experimental:ooo-ms-interop:xmlns:field:1.0');
        $xmlWriter->writeAttribute('xmlns:formx', 'urn:openoffice:names:experimental:ooxml-odf-interop:xmlns:form:1.0');

        $this->getAutomaticStyles($phpWord);
        $this->writeFontFaces($xmlWriter); // office:font-face-decls
        $this->writeAutomaticStyles($xmlWriter, $phpWord); // office:automatic-styles

        $xmlWriter->startElement('office:body');
        $xmlWriter->startElement('office:text');

        // text:sequence-decls
        $sequences = array('Illustration', 'Table', 'Text', 'Drawing');
        $xmlWriter->startElement('text:sequence-decls');
        foreach ($sequences as $sequence) {
            $xmlWriter->startElement('text:sequence-decl');
            $xmlWriter->writeAttribute('text:display-outline-level', 0);
            $xmlWriter->writeAttribute('text:name', $sequence);
            $xmlWriter->endElement();
        }
        $xmlWriter->endElement(); // text:sequence-decl

        $sections = $phpWord->getSections();
        $sectionCount = count($sections);
        if ($sectionCount > 0) {
            foreach ($sections as $section) {
                $elements = $section->getElements();
                // $xmlWriter->startElement('text:section');
                foreach ($elements as $element) {
                    $elementWriter = new ElementWriter($xmlWriter, $this, $element, false);
                    $elementWriter->write();
                }
                // $xmlWriter->endElement(); // text:section
            }
        }
        $xmlWriter->endElement(); // office:text
        $xmlWriter->endElement(); // office:body
        $xmlWriter->endElement(); // office:document-content

        return $xmlWriter->getData();
    }

    /**
     * Write automatic styles
     */
    private function writeAutomaticStyles(XMLWriter $xmlWriter, PhpWord $phpWord)
    {
        $xmlWriter->startElement('office:automatic-styles');

        // Font and paragraph
        $styles = Style::getStyles();
        $paragraphStyleCount = 0;
        if (count($styles) > 0) {
            foreach ($styles as $styleName => $style) {
                if (preg_match('#^T[0-9]+$#', $styleName) != 0
                    || preg_match('#^P[0-9]+$#', $styleName) != 0
                ) {
                    $styleClass = str_replace('Style', 'Writer\\ODText\\Style', get_class($style));
                    if (class_exists($styleClass)) {
                        $styleWriter = new $styleClass($xmlWriter, $style);
                        $styleWriter->setIsAuto(true);
                        $styleWriter->write();
                    }
                    if ($style instanceof Paragraph) {
                        $paragraphStyleCount++;
                    }
                }
            }
            if ($paragraphStyleCount == 0) {
                $style = new Paragraph();
                $style->setStyleName('P1');
                $styleWriter = new \PhpOffice\PhpWord\Writer\ODText\Style\Paragraph($xmlWriter, $style);
                $styleWriter->setIsAuto(true);
                $styleWriter->write();
            }
        }

        // Images
        $images = Media::getElements('section');
        foreach ($images as $image) {
            if ($image['type'] == 'image') {
                $xmlWriter->startElement('style:style');
                $xmlWriter->writeAttribute('style:name', 'fr' . $image['rID']);
                $xmlWriter->writeAttribute('style:family', 'graphic');
                $xmlWriter->writeAttribute('style:parent-style-name', 'Graphics');
                $xmlWriter->startElement('style:graphic-properties');
                $xmlWriter->writeAttribute('style:vertical-pos', 'top');
                $xmlWriter->writeAttribute('style:vertical-rel', 'baseline');
                $xmlWriter->endElement();
                $xmlWriter->endElement();
            }
        }

        // Tables
        $sections = $phpWord->getSections();
        $sectionCount = count($sections);
        if ($sectionCount > 0) {
            $sectionId = 0;
            foreach ($sections as $section) {
                $sectionId++;
                $elements = $section->getElements();
                foreach ($elements as $element) {
                    if ($elements instanceof Table) {
                        $xmlWriter->startElement('style:style');
                        $xmlWriter->writeAttribute('style:name', $element->getElementId());
                        $xmlWriter->writeAttribute('style:family', 'table');
                        $xmlWriter->startElement('style:table-properties');
                        //$xmlWriter->writeAttribute('style:width', 'table');
                        $xmlWriter->writeAttribute('style:rel-width', 100);
                        $xmlWriter->writeAttribute('table:align', 'center');
                        $xmlWriter->endElement();
                        $xmlWriter->endElement();
                    }
                }
            }
        }

        $xmlWriter->endElement(); // office:automatic-styles
    }

    /**
     * Set automatic styles
     */
    private function getAutomaticStyles(PhpWord $phpWord)
    {
        $sections = $phpWord->getSections();
        $sectionCount = count($sections);
        if ($sectionCount > 0) {
            $paragraphStyleCount = 0;
            $fontStyleCount = 0;
            foreach ($sections as $section) {
                $elements = $section->getElements();
                foreach ($elements as $element) {
                    if ($element instanceof Text) {
                        $fontStyle = $element->getFontStyle();
                        $paragraphStyle = $element->getParagraphStyle();

                        // Font
                        if ($fontStyle instanceof Font) {
                            $fontStyleCount++;
                            $arrStyle = array(
                                'color' => $fontStyle->getColor(),
                                'name'  => $fontStyle->getName()
                            );
                            $phpWord->addFontStyle('T' . $fontStyleCount, $arrStyle);
                            $element->setFontStyle('T' . $fontStyleCount);

                        // Paragraph
                        } elseif ($paragraphStyle instanceof Paragraph) {
                            $paragraphStyleCount++;

                            $phpWord->addParagraphStyle('P' . $paragraphStyleCount, array());
                            $element->setParagraphStyle('P' . $paragraphStyleCount);
                        }
                    }
                }
            }
        }
    }
}
