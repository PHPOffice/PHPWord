<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\ODText;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Element\Link;
use PhpOffice\PhpWord\Element\ListItem;
use PhpOffice\PhpWord\Element\Object;
use PhpOffice\PhpWord\Element\PageBreak;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextBreak;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\TOC;

/**
 * ODText content part writer
 */
class Content extends Base
{
    /**
     * Write content file to XML format
     *
     * @param  PhpWord $phpWord
     * @return string XML Output
     */
    public function writeContent(PhpWord $phpWord = null)
    {
        if (is_null($phpWord)) {
            throw new Exception("No PhpWord assigned.");
        }

        // Create XML writer
        $xmlWriter = $this->getXmlWriter();

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8');

        // office:document-content
        $xmlWriter->startElement('office:document-content');
        $this->writeCommonRootAttributes($xmlWriter);
        $xmlWriter->writeAttribute('xmlns:xforms', 'http://www.w3.org/2002/xforms');
        $xmlWriter->writeAttribute('xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
        $xmlWriter->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xmlWriter->writeAttribute('xmlns:field', 'urn:openoffice:names:experimental:ooo-ms-interop:xmlns:field:1.0');
        $xmlWriter->writeAttribute('xmlns:formx', 'urn:openoffice:names:experimental:ooxml-odf-interop:xmlns:form:1.0');

        // We firstly search all fonts used
        $sections = $phpWord->getSections();
        $countSections = count($sections);
        if ($countSections > 0) {
            $pSection = 0;
            $numPStyles = 0;
            $numFStyles = 0;

            foreach ($sections as $section) {
                $pSection++;
                $elements = $section->getElements();

                foreach ($elements as $element) {
                    if ($element instanceof Text) {
                        $fStyle = $element->getFontStyle();
                        $pStyle = $element->getParagraphStyle();

                        if ($fStyle instanceof Font) {
                            $numFStyles++;

                            $arrStyle = array(
                                'color' => $fStyle->getColor(),
                                'name'  => $fStyle->getName()
                            );
                            $phpWord->addFontStyle('T' . $numFStyles, $arrStyle);
                            $element->setFontStyle('T' . $numFStyles);
                        } elseif ($pStyle instanceof Paragraph) {
                            $numPStyles++;

                            $phpWord->addParagraphStyle('P' . $numPStyles, array());
                            $element->setParagraphStyle('P' . $numPStyles);
                        }
                    }
                }
            }
        }

        // office:font-face-decls
        $this->writeFontFaces($xmlWriter);

        // office:automatic-styles
        $xmlWriter->startElement('office:automatic-styles');
        $styles = Style::getStyles();
        $numPStyles = 0;
        if (count($styles) > 0) {
            foreach ($styles as $styleName => $style) {
                if (preg_match('#^T[0-9]+$#', $styleName) != 0
                    || preg_match('#^P[0-9]+$#', $styleName) != 0
                ) {
                    // Font
                    if ($style instanceof Font) {
                        $xmlWriter->startElement('style:style');
                        $xmlWriter->writeAttribute('style:name', $styleName);
                        $xmlWriter->writeAttribute('style:family', 'text');
                        // style:text-properties
                        $xmlWriter->startElement('style:text-properties');
                        $xmlWriter->writeAttribute('fo:color', '#' . $style->getColor());
                        $xmlWriter->writeAttribute('style:font-name', $style->getName());
                        $xmlWriter->writeAttribute('style:font-name-complex', $style->getName());
                        $xmlWriter->endElement();
                        $xmlWriter->endElement();
                    }
                    if ($style instanceof Paragraph) {
                        $numPStyles++;
                        // style:style
                        $xmlWriter->startElement('style:style');
                        $xmlWriter->writeAttribute('style:name', $styleName);
                        $xmlWriter->writeAttribute('style:family', 'paragraph');
                        $xmlWriter->writeAttribute('style:parent-style-name', 'Standard');
                        $xmlWriter->writeAttribute('style:master-page-name', 'Standard');
                        // style:paragraph-properties
                        $xmlWriter->startElement('style:paragraph-properties');
                        $xmlWriter->writeAttribute('style:page-number', 'auto');
                        $xmlWriter->endElement();
                        $xmlWriter->endElement();
                    }
                }
            }

            if ($numPStyles == 0) {
                // style:style
                $xmlWriter->startElement('style:style');
                $xmlWriter->writeAttribute('style:name', 'P1');
                $xmlWriter->writeAttribute('style:family', 'paragraph');
                $xmlWriter->writeAttribute('style:parent-style-name', 'Standard');
                $xmlWriter->writeAttribute('style:master-page-name', 'Standard');
                // style:paragraph-properties
                $xmlWriter->startElement('style:paragraph-properties');
                $xmlWriter->writeAttribute('style:page-number', 'auto');
                $xmlWriter->endElement();
                $xmlWriter->endElement();
            }
        }
        $xmlWriter->endElement();

        // office:body
        $xmlWriter->startElement('office:body');
        // office:text
        $xmlWriter->startElement('office:text');
        // text:sequence-decls
        $xmlWriter->startElement('text:sequence-decls');
        // text:sequence-decl
        $xmlWriter->startElement('text:sequence-decl');
        $xmlWriter->writeAttribute('text:display-outline-level', 0);
        $xmlWriter->writeAttribute('text:name', 'Illustration');
        $xmlWriter->endElement();
        // text:sequence-decl
        $xmlWriter->startElement('text:sequence-decl');
        $xmlWriter->writeAttribute('text:display-outline-level', 0);
        $xmlWriter->writeAttribute('text:name', 'Table');
        $xmlWriter->endElement();
        // text:sequence-decl
        $xmlWriter->startElement('text:sequence-decl');
        $xmlWriter->writeAttribute('text:display-outline-level', 0);
        $xmlWriter->writeAttribute('text:name', 'Text');
        $xmlWriter->endElement();
        // text:sequence-decl
        $xmlWriter->startElement('text:sequence-decl');
        $xmlWriter->writeAttribute('text:display-outline-level', 0);
        $xmlWriter->writeAttribute('text:name', 'Drawing');
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $sections = $phpWord->getSections();
        $countSections = count($sections);
        if ($countSections > 0) {
            foreach ($sections as $section) {
                $elements = $section->getElements();

                foreach ($elements as $element) {
                    if ($element instanceof Text) {
                        $this->writeText($xmlWriter, $element);
                    } elseif ($element instanceof TextRun) {
                        $this->writeTextRun($xmlWriter, $element);
                    } elseif ($element instanceof TextBreak) {
                        $this->writeTextBreak($xmlWriter);
                    } elseif ($element instanceof Link) {
                        $this->writeUnsupportedElement($xmlWriter, 'Link');
                    } elseif ($element instanceof Title) {
                        $this->writeUnsupportedElement($xmlWriter, 'Title');
                    } elseif ($element instanceof PageBreak) {
                        $this->writeUnsupportedElement($xmlWriter, 'Page Break');
                    } elseif ($element instanceof Table) {
                        $this->writeUnsupportedElement($xmlWriter, 'Table');
                    } elseif ($element instanceof ListItem) {
                        $this->writeUnsupportedElement($xmlWriter, 'List Item');
                    } elseif ($element instanceof Image) {
                        $this->writeUnsupportedElement($xmlWriter, 'Image');
                    } elseif ($element instanceof Object) {
                        $this->writeUnsupportedElement($xmlWriter, 'Object');
                    } elseif ($element instanceof TOC) {
                        $this->writeUnsupportedElement($xmlWriter, 'TOC');
                    } else {
                        $this->writeUnsupportedElement($xmlWriter, 'Element');
                    }
                }
            }
        }
        $xmlWriter->endElement();
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        // Return
        return $xmlWriter->getData();
    }

    /**
     * Write text
     *
     * @param XMLWriter $xmlWriter
     * @param Text $text
     * @param bool $withoutP
     */
    protected function writeText(XMLWriter $xmlWriter, Text $text, $withoutP = false)
    {
        $styleFont = $text->getFontStyle();
        $styleParagraph = $text->getParagraphStyle();

        // @todo Commented for TextRun. Should really checkout this value
        // $SfIsObject = ($styleFont instanceof Font) ? true : false;
        $SfIsObject = false;

        if ($SfIsObject) {
            // Don't never be the case, because I browse all sections for cleaning all styles not declared
            die('PhpWord : $SfIsObject wouldn\'t be an object');
        } else {
            if (!$withoutP) {
                $xmlWriter->startElement('text:p'); // text:p
            }
            if (empty($styleFont)) {
                if (empty($styleParagraph)) {
                    $xmlWriter->writeAttribute('text:style-name', 'P1');
                } elseif (is_string($styleParagraph)) {
                    $xmlWriter->writeAttribute('text:style-name', $styleParagraph);
                }
                $xmlWriter->writeRaw($text->getText());
            } else {
                if (empty($styleParagraph)) {
                    $xmlWriter->writeAttribute('text:style-name', 'Standard');
                } elseif (is_string($styleParagraph)) {
                    $xmlWriter->writeAttribute('text:style-name', $styleParagraph);
                }
                // text:span
                $xmlWriter->startElement('text:span');
                if (is_string($styleFont)) {
                    $xmlWriter->writeAttribute('text:style-name', $styleFont);
                }
                $xmlWriter->writeRaw($text->getText());
                $xmlWriter->endElement();
            }
            if (!$withoutP) {
                $xmlWriter->endElement(); // text:p
            }
        }
    }

    /**
     * Write TextRun section
     *
     * @param XMLWriter $xmlWriter
     * @param TextRun $textrun
     * @todo Enable all other section types
     */
    protected function writeTextRun(XMLWriter $xmlWriter, TextRun $textrun)
    {
        $elements = $textrun->getElements();
        $xmlWriter->startElement('text:p');
        if (count($elements) > 0) {
            foreach ($elements as $element) {
                if ($element instanceof Text) {
                    $this->writeText($xmlWriter, $element, true);
                }
            }
        }
        $xmlWriter->endElement();
    }

    /**
     * Write TextBreak
     *
     * @param XMLWriter $xmlWriter
     */
    protected function writeTextBreak(XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('text:p');
        $xmlWriter->writeAttribute('text:style-name', 'Standard');
        $xmlWriter->endElement();
    }

    /**
     * Write unsupported element
     *
     * @param XMLWriter $xmlWriter
     * @param string $element
     */
    private function writeUnsupportedElement($xmlWriter, $element)
    {
        $xmlWriter->startElement('text:p');
        $xmlWriter->writeRaw($element);
        $xmlWriter->endElement();
    }
}
