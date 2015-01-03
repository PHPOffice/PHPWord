<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\ODText\Part;

use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Style\Table as TableStyle;
use PhpOffice\PhpWord\Writer\ODText\Element\Container;
use PhpOffice\PhpWord\Writer\ODText\Style\Paragraph as ParagraphStyleWriter;

/**
 * ODText content part writer: content.xml
 */
class Content extends AbstractPart
{
    /**
     * Auto style collection
     *
     * Collect inline style information from section, image, and table elements
     *
     * @todo Merge font and paragraph styles
     * @var array
     */
    private $autoStyles = array('Section' => array(), 'Image' => array(), 'Table' => array());

    /**
     * Write part
     *
     * @return string
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();
        $phpWord = $this->getParentWriter()->getPhpWord();

        $this->getAutoStyles($phpWord);

        $xmlWriter->startDocument('1.0', 'UTF-8');
        $xmlWriter->startElement('office:document-content');
        $this->writeCommonRootAttributes($xmlWriter);
        $xmlWriter->writeAttribute('xmlns:xforms', 'http://www.w3.org/2002/xforms');
        $xmlWriter->writeAttribute('xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
        $xmlWriter->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xmlWriter->writeAttribute('xmlns:field', 'urn:openoffice:names:experimental:ooo-ms-interop:xmlns:field:1.0');
        $xmlWriter->writeAttribute('xmlns:formx', 'urn:openoffice:names:experimental:ooxml-odf-interop:xmlns:form:1.0');

        // Font declarations and automatic styles
        $this->writeFontFaces($xmlWriter); // office:font-face-decls
        $this->writeAutoStyles($xmlWriter); // office:automatic-styles

        // Body
        $xmlWriter->startElement('office:body');
        $xmlWriter->startElement('office:text');

        // Sequence declarations
        $sequences = array('Illustration', 'Table', 'Text', 'Drawing');
        $xmlWriter->startElement('text:sequence-decls');
        foreach ($sequences as $sequence) {
            $xmlWriter->startElement('text:sequence-decl');
            $xmlWriter->writeAttribute('text:display-outline-level', 0);
            $xmlWriter->writeAttribute('text:name', $sequence);
            $xmlWriter->endElement();
        }
        $xmlWriter->endElement(); // text:sequence-decl

        // Sections
        $sections = $phpWord->getSections();
        foreach ($sections as $section) {
            $name = 'Section' . $section->getSectionId();
            $xmlWriter->startElement('text:section');
            $xmlWriter->writeAttribute('text:name', $name);
            $xmlWriter->writeAttribute('text:style-name', $name);
            $containerWriter = new Container($xmlWriter, $section);
            $containerWriter->write();
            $xmlWriter->endElement(); // text:section
        }

        $xmlWriter->endElement(); // office:text
        $xmlWriter->endElement(); // office:body

        $xmlWriter->endElement(); // office:document-content

        return $xmlWriter->getData();
    }

    /**
     * Write automatic styles other than fonts and paragraphs.
     *
     * @since 0.11.0
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @return void
     */
    private function writeAutoStyles(XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('office:automatic-styles');

        $this->writeTextStyles($xmlWriter);
        foreach ($this->autoStyles as $element => $styles) {
            $writerClass = 'PhpOffice\\PhpWord\\Writer\\ODText\\Style\\' . $element;
            foreach ($styles as $style) {

                /** @var \PhpOffice\PhpWord\Writer\ODText\Style\AbstractStyle $styleWriter Type hint */
                $styleWriter = new $writerClass($xmlWriter, $style);
                $styleWriter->write();
            }
        }

        $xmlWriter->endElement(); // office:automatic-styles
    }

    /**
     * Write automatic styles.
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @return void
     */
    private function writeTextStyles(XMLWriter $xmlWriter)
    {
        $styles = Style::getStyles();
        $paragraphStyleCount = 0;
        if (count($styles) > 0) {
            foreach ($styles as $style) {
                if ($style->isAuto() === true) {
                    $styleClass = str_replace('\\Style\\', '\\Writer\\ODText\\Style\\', get_class($style));
                    if (class_exists($styleClass)) {
                        /** @var \PhpOffice\PhpWord\Writer\ODText\Style\AbstractStyle $styleWriter Type hint */
                        $styleWriter = new $styleClass($xmlWriter, $style);
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
                $style->setAuto();
                $styleWriter = new ParagraphStyleWriter($xmlWriter, $style);
                $styleWriter->write();
            }
        }
    }

    /**
     * Get automatic styles.
     *
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     * @return void
     */
    private function getAutoStyles(PhpWord $phpWord)
    {
        $sections = $phpWord->getSections();
        $paragraphStyleCount = 0;
        $fontStyleCount = 0;
        foreach ($sections as $section) {
            $style = $section->getStyle();
            $style->setStyleName("Section{$section->getSectionId()}");
            $this->autoStyles['Section'][] = $style;
            $this->getContainerStyle($section, $paragraphStyleCount, $fontStyleCount);
        }
    }

    /**
     * Get all styles of each elements in container recursively
     *
     * Table style can be null or string of the style name
     *
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $container
     * @param int &$paragraphStyleCount
     * @param int &$fontStyleCount
     * @return void
     * @todo Simplify the logic
     */
    private function getContainerStyle($container, &$paragraphStyleCount, &$fontStyleCount)
    {
        $elements = $container->getElements();
        foreach ($elements as $element) {
            if ($element instanceof TextRun) {
                $this->getContainerStyle($element, $paragraphStyleCount, $fontStyleCount);
            } elseif ($element instanceof Text) {
                $this->getElementStyle($element, $paragraphStyleCount, $fontStyleCount);
            } elseif ($element instanceof Image) {
                $style = $element->getStyle();
                $style->setStyleName('fr' . $element->getMediaIndex());
                $this->autoStyles['Image'][] = $style;
            } elseif ($element instanceof Table) {
                $style = $element->getStyle();
                if ($style === null) {
                    $style = new TableStyle();
                } elseif (is_string($style)) {
                    $style = Style::getStyle($style);
                }
                $style->setStyleName($element->getElementId());
                $this->autoStyles['Table'][] = $style;
            }
        }
    }

    /**
     * Get style of individual element
     *
     * @param \PhpOffice\PhpWord\Element\Text &$element
     * @param int &$paragraphStyleCount
     * @param int &$fontStyleCount
     * @return void
     */
    private function getElementStyle(&$element, &$paragraphStyleCount, &$fontStyleCount)
    {
        $fontStyle = $element->getFontStyle();
        $paragraphStyle = $element->getParagraphStyle();
        $phpWord = $this->getParentWriter()->getPhpWord();

        // Font
        if ($fontStyle instanceof Font) {
            $fontStyleCount++;
            $style = $phpWord->addFontStyle("T{$fontStyleCount}", $fontStyle);
            $style->setAuto();
            $element->setFontStyle("T{$fontStyleCount}");

        // Paragraph
        } elseif ($paragraphStyle instanceof Paragraph) {
            $paragraphStyleCount++;
            $style = $phpWord->addParagraphStyle("P{$paragraphStyleCount}", array());
            $style->setAuto();
            $element->setParagraphStyle("P{$paragraphStyleCount}");
        }
    }
}
