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

use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Writer\ODText\Element\Container;

/**
 * ODText content part writer: content.xml
 */
class Content extends AbstractPart
{
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

        $this->writeFontFaces($xmlWriter); // office:font-face-decls

        // Automatic styles
        $xmlWriter->startElement('office:automatic-styles');
        $this->writeTextAutoStyles($xmlWriter);
        $this->writeImageAutoStyles($xmlWriter);
        $this->writeTableAutoStyles($xmlWriter, $phpWord);
        $xmlWriter->endElement(); // office:automatic-styles

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
            // $xmlWriter->startElement('text:section');
            $containerWriter = new Container($xmlWriter, $section);
            $containerWriter->write();
            // $xmlWriter->endElement(); // text:section
        }

        $xmlWriter->endElement(); // office:text
        $xmlWriter->endElement(); // office:body

        $xmlWriter->endElement(); // office:document-content

        return $xmlWriter->getData();
    }

    /**
     * Write automatic styles
     */
    private function writeTextAutoStyles(XMLWriter $xmlWriter)
    {
        $styles = Style::getStyles();
        $paragraphStyleCount = 0;
        if (count($styles) > 0) {
            foreach ($styles as $style) {
                if ($style->isAuto() === true) {
                    $styleClass = str_replace('\\Style\\', '\\Writer\\ODText\\Style\\', get_class($style));
                    if (class_exists($styleClass)) {
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
                $styleWriter = new \PhpOffice\PhpWord\Writer\ODText\Style\Paragraph($xmlWriter, $style);
                $styleWriter->write();
            }
        }
    }

    /**
     * Write image automatic styles
     */
    private function writeImageAutoStyles(XMLWriter $xmlWriter)
    {
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
                $xmlWriter->endElement(); // style:graphic-properties
                $xmlWriter->endElement(); // style:style
            }
        }
    }

    /**
     * Write table automatic styles
     */
    private function writeTableAutoStyles(XMLWriter $xmlWriter, PhpWord $phpWord)
    {
        $sections = $phpWord->getSections();
        foreach ($sections as $section) {
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

    /**
     * Get automatic styles
     */
    private function getAutoStyles(PhpWord $phpWord)
    {
        $sections = $phpWord->getSections();
        $paragraphStyleCount = 0;
        $fontStyleCount = 0;
        foreach ($sections as $section) {
            $this->getContainerStyle($section, $paragraphStyleCount, $fontStyleCount);
        }
    }

    /**
     * Get all styles of each elements in container recursively
     *
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $container
     * @param int $paragraphStyleCount
     * @param int $fontStyleCount
     */
    private function getContainerStyle($container, &$paragraphStyleCount, &$fontStyleCount)
    {
        $elements = $container->getElements();
        foreach ($elements as $element) {
            if ($element instanceof TextRun) {
                $this->getContainerStyle($element, $paragraphStyleCount, $fontStyleCount);
            } elseif ($element instanceof Text) {
                $this->getElementStyle($element, $paragraphStyleCount, $fontStyleCount);
            }
        }
    }

    /**
     * Get style of individual element
     *
     * @param \PhpOffice\PhpWord\Element\Text $element
     * @param int $paragraphStyleCount
     * @param int $fontStyleCount
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
