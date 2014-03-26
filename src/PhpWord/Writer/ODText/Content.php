<?php
/**
 * PHPWord
 *
 * Copyright (c) 2014 PHPWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.9.0
 */

namespace PhpOffice\PhpWord\Writer\ODText;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Section;
use PhpOffice\PhpWord\Section\Image;
use PhpOffice\PhpWord\Section\Link;
use PhpOffice\PhpWord\Section\ListItem;
use PhpOffice\PhpWord\Section\Object;
use PhpOffice\PhpWord\Section\PageBreak;
use PhpOffice\PhpWord\Section\Table;
use PhpOffice\PhpWord\Section\Text;
use PhpOffice\PhpWord\Section\TextBreak;
use PhpOffice\PhpWord\Section\TextRun;
use PhpOffice\PhpWord\Section\Title;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\TOC;

/**
 * ODText content part writer
 */
class Content extends WriterPart
{
    /**
     * Write content file to XML format
     *
     * @param  \PhpOffice\PhpWord\PhpWord $phpWord
     * @return string XML Output
     */
    public function writeContent(PhpWord $phpWord = null)
    {
        // Create XML writer
        $xmlWriter = null;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $xmlWriter = new XMLWriter(XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $xmlWriter = new XMLWriter(XMLWriter::STORAGE_MEMORY);
        }

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8');

        // office:document-content
        $xmlWriter->startElement('office:document-content');
        $xmlWriter->writeAttribute('xmlns:office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
        $xmlWriter->writeAttribute('xmlns:style', 'urn:oasis:names:tc:opendocument:xmlns:style:1.0');
        $xmlWriter->writeAttribute('xmlns:text', 'urn:oasis:names:tc:opendocument:xmlns:text:1.0');
        $xmlWriter->writeAttribute('xmlns:table', 'urn:oasis:names:tc:opendocument:xmlns:table:1.0');
        $xmlWriter->writeAttribute('xmlns:draw', 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0');
        $xmlWriter->writeAttribute('xmlns:fo', 'urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0');
        $xmlWriter->writeAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
        $xmlWriter->writeAttribute('xmlns:dc', 'http://purl.org/dc/elements/1.1/');
        $xmlWriter->writeAttribute('xmlns:meta', 'urn:oasis:names:tc:opendocument:xmlns:meta:1.0');
        $xmlWriter->writeAttribute('xmlns:number', 'urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0');
        $xmlWriter->writeAttribute('xmlns:svg', 'urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0');
        $xmlWriter->writeAttribute('xmlns:chart', 'urn:oasis:names:tc:opendocument:xmlns:chart:1.0');
        $xmlWriter->writeAttribute('xmlns:dr3d', 'urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0');
        $xmlWriter->writeAttribute('xmlns:math', 'http://www.w3.org/1998/Math/MathML');
        $xmlWriter->writeAttribute('xmlns:form', 'urn:oasis:names:tc:opendocument:xmlns:form:1.0');
        $xmlWriter->writeAttribute('xmlns:script', 'urn:oasis:names:tc:opendocument:xmlns:script:1.0');
        $xmlWriter->writeAttribute('xmlns:ooo', 'http://openoffice.org/2004/office');
        $xmlWriter->writeAttribute('xmlns:ooow', 'http://openoffice.org/2004/writer');
        $xmlWriter->writeAttribute('xmlns:oooc', 'http://openoffice.org/2004/calc');
        $xmlWriter->writeAttribute('xmlns:dom', 'http://www.w3.org/2001/xml-events');
        $xmlWriter->writeAttribute('xmlns:xforms', 'http://www.w3.org/2002/xforms');
        $xmlWriter->writeAttribute('xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
        $xmlWriter->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xmlWriter->writeAttribute('xmlns:rpt', 'http://openoffice.org/2005/report');
        $xmlWriter->writeAttribute('xmlns:of', 'urn:oasis:names:tc:opendocument:xmlns:of:1.2');
        $xmlWriter->writeAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
        $xmlWriter->writeAttribute('xmlns:grddl', 'http://www.w3.org/2003/g/data-view#');
        $xmlWriter->writeAttribute('xmlns:tableooo', 'http://openoffice.org/2009/table');
        $xmlWriter->writeAttribute('xmlns:field', 'urn:openoffice:names:experimental:ooo-ms-interop:xmlns:field:1.0');
        $xmlWriter->writeAttribute('xmlns:formx', 'urn:openoffice:names:experimental:ooxml-odf-interop:xmlns:form:1.0');
        $xmlWriter->writeAttribute('xmlns:css3t', 'http://www.w3.org/TR/css3-text/');
        $xmlWriter->writeAttribute('office:version', '1.2');

        // We firstly search all fonts used
        $_sections = $phpWord->getSections();
        $countSections = count($_sections);
        if ($countSections > 0) {
            $pSection = 0;
            $numPStyles = 0;
            $numFStyles = 0;

            foreach ($_sections as $section) {
                $pSection++;
                $_elements = $section->getElements();

                foreach ($_elements as $element) {
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
        $xmlWriter->startElement('office:font-face-decls');
        $arrFonts = array();

        $styles = Style::getStyles();
        $numFonts = 0;
        if (count($styles) > 0) {
            foreach ($styles as $styleName => $style) {
                // PhpOffice\PhpWord\Style\Font
                if ($style instanceof Font) {
                    $numFonts++;
                    $name = $style->getName();
                    if (!in_array($name, $arrFonts)) {
                        $arrFonts[] = $name;

                        // style:font-face
                        $xmlWriter->startElement('style:font-face');
                        $xmlWriter->writeAttribute('style:name', $name);
                        $xmlWriter->writeAttribute('svg:font-family', $name);
                        $xmlWriter->endElement();
                    }
                }
            }
            if (!in_array(PhpWord::DEFAULT_FONT_NAME, $arrFonts)) {
                $xmlWriter->startElement('style:font-face');
                $xmlWriter->writeAttribute('style:name', PhpWord::DEFAULT_FONT_NAME);
                $xmlWriter->writeAttribute('svg:font-family', PhpWord::DEFAULT_FONT_NAME);
                $xmlWriter->endElement();
            }
        }
        $xmlWriter->endElement();

        $xmlWriter->startElement('office:automatic-styles');
        $styles = Style::getStyles();
        $numPStyles = 0;
        if (count($styles) > 0) {
            foreach ($styles as $styleName => $style) {
                if (preg_match('#^T[0-9]+$#', $styleName) != 0
                    || preg_match('#^P[0-9]+$#', $styleName) != 0
                ) {
                    // PhpOffice\PhpWord\Style\Font
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

        $_sections = $phpWord->getSections();
        $countSections = count($_sections);
        $pSection = 0;

        if ($countSections > 0) {
            foreach ($_sections as $section) {
                $pSection++;

                $_elements = $section->getElements();

                foreach ($_elements as $element) {
                    if ($element instanceof Text) {
                        $this->_writeText($xmlWriter, $element);
                    } elseif ($element instanceof TextRun) {
                        $this->_writeTextRun($xmlWriter, $element);
                    } elseif ($element instanceof TextBreak) {
                        $this->_writeTextBreak($xmlWriter);
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

                if ($pSection == $countSections) {
                    $this->_writeEndSection($xmlWriter, $section);
                } else {
                    $this->_writeSection($xmlWriter, $section);
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
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Section\Text $text
     * @param bool $withoutP
     */
    protected function _writeText(XMLWriter $xmlWriter, Text $text, $withoutP = false)
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
                } else {
                    $xmlWriter->writeAttribute('text:style-name', $text->getParagraphStyle());
                }
                $xmlWriter->writeRaw($text->getText());
            } else {
                if (empty($styleParagraph)) {
                    $xmlWriter->writeAttribute('text:style-name', 'Standard');
                } else {
                    $xmlWriter->writeAttribute('text:style-name', $text->getParagraphStyle());
                }
                // text:span
                $xmlWriter->startElement('text:span');
                $xmlWriter->writeAttribute('text:style-name', $styleFont);
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
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Section\TextRun $textrun
     * @todo Enable all other section types
     */
    protected function _writeTextRun(XMLWriter $xmlWriter, TextRun $textrun)
    {
        $elements = $textrun->getElements();
        $xmlWriter->startElement('text:p');
        if (count($elements) > 0) {
            foreach ($elements as $element) {
                if ($element instanceof Text) {
                    $this->_writeText($xmlWriter, $element, true);
                }
            }
        }
        $xmlWriter->endElement();
    }

    /**
     * Write TextBreak
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     */
    protected function _writeTextBreak(XMLWriter $xmlWriter = null)
    {
        $xmlWriter->startElement('text:p');
        $xmlWriter->writeAttribute('text:style-name', 'Standard');
        $xmlWriter->endElement();
    }

    // @codeCoverageIgnoreStart
    /**
     * Write end section
     *
     * @param PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param PhpOffice\PhpWord\Section $section
     */
    private function _writeEndSection(XMLWriter $xmlWriter = null, Section $section = null)
    {
    }

    /**
     * Write section
     *
     * @param PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param PhpOffice\PhpWord\Section $section
     */
    private function _writeSection(XMLWriter $xmlWriter = null, Section $section = null)
    {
    }
    // @codeCoverageIgnoreEnd

    /**
     * Write unsupported element
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param string $element
     */
    private function writeUnsupportedElement($xmlWriter, $element)
    {
        $xmlWriter->startElement('text:p');
        $xmlWriter->writeRaw($element);
        $xmlWriter->endElement();
    }
}
