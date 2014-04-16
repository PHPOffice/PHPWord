<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\ODText;

use PhpOffice\PhpWord\Exception\Exception;
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
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Style;

/**
 * ODText content part writer
 */
class Content extends Base
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


        $this->writeFontFaces($xmlWriter); // office:font-face-decls

        $this->writeAutomaticStyles($xmlWriter); // office:automatic-styles

        // Tables
        $sections = $phpWord->getSections();
        $countSections = count($sections);
        if ($countSections > 0) {
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
                    } elseif ($element instanceof Link) {
                        $this->writeLink($xmlWriter, $element);
                    } elseif ($element instanceof Title) {
                        $this->writeTitle($xmlWriter, $element);
                    } elseif ($element instanceof ListItem) {
                        $this->writeListItem($xmlWriter, $element);
                    } elseif ($element instanceof TextBreak) {
                        $this->writeTextBreak($xmlWriter);
                    } elseif ($element instanceof PageBreak) {
                        $this->writePageBreak($xmlWriter);
                    } elseif ($element instanceof Table) {
                        $this->writeTable($xmlWriter, $element);
                    } elseif ($element instanceof Image) {
                        $this->writeImage($xmlWriter, $element);
                    } elseif ($element instanceof Object) {
                        $this->writeObject($xmlWriter, $element);
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
     * Write link element
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     */
    protected function writeLink(XMLWriter $xmlWriter, Link $link)
    {
        $this->writeUnsupportedElement($xmlWriter, 'Link');
    }

    /**
     * Write title element
     */
    protected function writeTitle(XMLWriter $xmlWriter, Title $title)
    {
        $this->writeUnsupportedElement($xmlWriter, 'Title');
    }

    /**
     * Write preserve text
     */
    protected function writePreserveText(XMLWriter $xmlWriter, PreserveText $preservetext)
    {
        $this->writeUnsupportedElement($xmlWriter, 'PreserveText');
    }

    /**
     * Write list item
     */
    protected function writeListItem(XMLWriter $xmlWriter, ListItem $listItem)
    {
        $this->writeUnsupportedElement($xmlWriter, 'ListItem');
    }

    /**
     * Write text break
     */
    protected function writeTextBreak(XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('text:p');
        $xmlWriter->writeAttribute('text:style-name', 'Standard');
        $xmlWriter->endElement();
    }

    /**
     * Write page break
     */
    protected function writePageBreak(XMLWriter $xmlWriter)
    {
        $this->writeUnsupportedElement($xmlWriter, 'PageBreak');
    }

    /**
     * Write table
     */
    protected function writeTable(XMLWriter $xmlWriter, Table $table)
    {
        $rows = $table->getRows();
        $rowCount = count($rows);
        $colCount = $table->countColumns();
        if ($rowCount > 0) {
            $xmlWriter->startElement('table:table');
            $xmlWriter->writeAttribute('table:name', $table->getElementId());
            $xmlWriter->writeAttribute('table:style', $table->getElementId());

            $xmlWriter->startElement('table:table-column');
            $xmlWriter->writeAttribute('table:number-columns-repeated', $colCount);
            $xmlWriter->endElement(); // table:table-column

            foreach ($rows as $row) {
                $xmlWriter->startElement('table:table-row');
                foreach ($row->getCells() as $cell) {
                    $xmlWriter->startElement('table:table-cell');
                    $xmlWriter->writeAttribute('office:value-type', 'string');
                    $elements = $cell->getElements();
                    if (count($elements) > 0) {
                        foreach ($elements as $element) {
                            if ($element instanceof Text) {
                                $this->writeText($xmlWriter, $element);
                            } elseif ($element instanceof TextRun) {
                                $this->writeTextRun($xmlWriter, $element);
                            } elseif ($element instanceof ListItem) {
                                $this->writeListItem($xmlWriter, $element);
                            } elseif ($element instanceof TextBreak) {
                                $this->writeTextBreak($xmlWriter);
                            } elseif ($element instanceof Image) {
                                $this->writeImage($xmlWriter, $element);
                            } elseif ($element instanceof Object) {
                                $this->writeObject($xmlWriter, $element);
                            }
                        }
                    } else {
                        $this->writeTextBreak($xmlWriter);
                    }
                    $xmlWriter->endElement(); // table:table-cell
                }
                $xmlWriter->endElement(); // table:table-row
            }
            $xmlWriter->endElement(); // table:table
        }
    }

    /**
     * Write image
     */
    protected function writeImage(XMLWriter $xmlWriter, Image $element)
    {
        $this->writeUnsupportedElement($xmlWriter, 'Image');
    }

    /**
     * Write object
     */
    protected function writeObject(XMLWriter $xmlWriter, Object $element)
    {
        $this->writeUnsupportedElement($xmlWriter, 'Object');
    }

    /**
     * Write unsupported element
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param string $element
     */
    private function writeUnsupportedElement(XMLWriter $xmlWriter, $element)
    {
        $xmlWriter->startElement('text:p');
        $xmlWriter->writeRaw($element);
        $xmlWriter->endElement();
    }

    /**
     * Write automatic styles
     */
    private function writeAutomaticStyles(XMLWriter $xmlWriter)
    {
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
    }
}
