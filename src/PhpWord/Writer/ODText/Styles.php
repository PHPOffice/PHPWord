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
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Style\Table;

/**
 * ODText styloes part writer
 */
class Styles extends WriterPart
{
    /**
     * Write Styles file to XML format
     *
     * @param  \PhpOffice\PhpWord\PhpWord $phpWord
     * @return string XML Output
     */
    public function writeStyles(PhpWord $phpWord = null)
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

        // Styles:Styles
        $xmlWriter->startElement('office:document-styles');
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
        $xmlWriter->writeAttribute('xmlns:rpt', 'http://openoffice.org/2005/report');
        $xmlWriter->writeAttribute('xmlns:of', 'urn:oasis:names:tc:opendocument:xmlns:of:1.2');
        $xmlWriter->writeAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
        $xmlWriter->writeAttribute('xmlns:grddl', 'http://www.w3.org/2003/g/data-view#');
        $xmlWriter->writeAttribute('xmlns:tableooo', 'http://openoffice.org/2009/table');
        $xmlWriter->writeAttribute('xmlns:css3t', 'http://www.w3.org/TR/css3-text/');
        $xmlWriter->writeAttribute('office:version', '1.2');


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
        }
        if (!in_array(PhpWord::DEFAULT_FONT_NAME, $arrFonts)) {
            $xmlWriter->startElement('style:font-face');
            $xmlWriter->writeAttribute('style:name', PhpWord::DEFAULT_FONT_NAME);
            $xmlWriter->writeAttribute('svg:font-family', PhpWord::DEFAULT_FONT_NAME);
            $xmlWriter->endElement();
        }
        $xmlWriter->endElement();

        // office:styles
        $xmlWriter->startElement('office:styles');

        // style:default-style
        $xmlWriter->startElement('style:default-style');
        $xmlWriter->writeAttribute('style:family', 'paragraph');

        // style:paragraph-properties
        $xmlWriter->startElement('style:paragraph-properties');
        $xmlWriter->writeAttribute('fo:hyphenation-ladder-count', 'no-limit');
        $xmlWriter->writeAttribute('style:text-autospace', 'ideograph-alpha');
        $xmlWriter->writeAttribute('style:punctuation-wrap', 'hanging');
        $xmlWriter->writeAttribute('style:line-break', 'strict');
        $xmlWriter->writeAttribute('style:tab-stop-distance', '1.249cm');
        $xmlWriter->writeAttribute('style:writing-mode', 'page');
        $xmlWriter->endElement();

        // style:text-properties
        $xmlWriter->startElement('style:text-properties');
        $xmlWriter->writeAttribute('style:use-window-font-color', 'true');
        $xmlWriter->writeAttribute('style:font-name', PhpWord::DEFAULT_FONT_NAME);
        $xmlWriter->writeAttribute('fo:font-size', PhpWord::DEFAULT_FONT_SIZE . 'pt');
        $xmlWriter->writeAttribute('fo:language', 'fr');
        $xmlWriter->writeAttribute('fo:country', 'FR');
        $xmlWriter->writeAttribute('style:letter-kerning', 'true');
        $xmlWriter->writeAttribute('style:font-name-asian', PhpWord::DEFAULT_FONT_NAME . '2');
        $xmlWriter->writeAttribute('style:font-size-asian', PhpWord::DEFAULT_FONT_SIZE . 'pt');
        $xmlWriter->writeAttribute('style:language-asian', 'zh');
        $xmlWriter->writeAttribute('style:country-asian', 'CN');
        $xmlWriter->writeAttribute('style:font-name-complex', PhpWord::DEFAULT_FONT_NAME . '2');
        $xmlWriter->writeAttribute('style:font-size-complex', PhpWord::DEFAULT_FONT_SIZE . 'pt');
        $xmlWriter->writeAttribute('style:language-complex', 'hi');
        $xmlWriter->writeAttribute('style:country-complex', 'IN');
        $xmlWriter->writeAttribute('fo:hyphenate', 'false');
        $xmlWriter->writeAttribute('fo:hyphenation-remain-char-count', '2');
        $xmlWriter->writeAttribute('fo:hyphenation-push-char-count', '2');
        $xmlWriter->endElement();

        $xmlWriter->endElement();

        // Write Style Definitions
        $styles = Style::getStyles();
        if (count($styles) > 0) {
            foreach ($styles as $styleName => $style) {
                if (preg_match('#^T[0-9]+$#', $styleName) == 0
                    && preg_match('#^P[0-9]+$#', $styleName) == 0
                ) {
                    // PhpOffice\PhpWord\Style\Font
                    if ($style instanceof Font) {
                        // style:style
                        $xmlWriter->startElement('style:style');
                        $xmlWriter->writeAttribute('style:name', $styleName);
                        $xmlWriter->writeAttribute('style:family', 'text');

                        // style:text-properties
                        $xmlWriter->startElement('style:text-properties');
                        $xmlWriter->writeAttribute('fo:font-size', ($style->getSize()) . 'pt');
                        $xmlWriter->writeAttribute('style:font-size-asian', ($style->getSize()) . 'pt');
                        $xmlWriter->writeAttribute('style:font-size-complex', ($style->getSize()) . 'pt');
                        if ($style->getItalic()) {
                            $xmlWriter->writeAttribute('fo:font-style', 'italic');
                            $xmlWriter->writeAttribute('style:font-style-asian', 'italic');
                            $xmlWriter->writeAttribute('style:font-style-complex', 'italic');
                        }
                        if ($style->getBold()) {
                            $xmlWriter->writeAttribute('fo:font-weight', 'bold');
                            $xmlWriter->writeAttribute('style:font-weight-asian', 'bold');
                        }
                        $xmlWriter->endElement();
                        $xmlWriter->endElement();
                    } elseif ($style instanceof Paragraph) {
                        // PhpOffice\PhpWord\Style\Paragraph
                        // style:style
                        $xmlWriter->startElement('style:style');
                        $xmlWriter->writeAttribute('style:name', $styleName);
                        $xmlWriter->writeAttribute('style:family', 'paragraph');

                        //style:paragraph-properties
                        $xmlWriter->startElement('style:paragraph-properties');
                        $xmlWriter->writeAttribute('fo:margin-top', ((is_null($style->getSpaceBefore())) ? '0' : round(17.6 / $style->getSpaceBefore(), 2)) . 'cm');
                        $xmlWriter->writeAttribute('fo:margin-bottom', ((is_null($style->getSpaceAfter())) ? '0' : round(17.6 / $style->getSpaceAfter(), 2)) . 'cm');
                        $xmlWriter->writeAttribute('fo:text-align', $style->getAlign());
                        $xmlWriter->endElement();

                        $xmlWriter->endElement();
                    } elseif ($style instanceof Table) {
                        // PhpOffice\PhpWord\Style\Table
                    }
                }
            }
        }
        $xmlWriter->endElement();

        // office:automatic-styles
        $xmlWriter->startElement('office:automatic-styles');
        // style:page-layout
        $xmlWriter->startElement('style:page-layout');
        $xmlWriter->writeAttribute('style:name', 'Mpm1');
        // style:page-layout-properties
        $xmlWriter->startElement('style:page-layout-properties');
        $xmlWriter->writeAttribute('fo:page-width', "21.001cm");
        $xmlWriter->writeAttribute('fo:page-height', '29.7cm');
        $xmlWriter->writeAttribute('style:num-format', '1');
        $xmlWriter->writeAttribute('style:print-orientation', 'portrait');
        $xmlWriter->writeAttribute('fo:margin-top', '2.501cm');
        $xmlWriter->writeAttribute('fo:margin-bottom', '2cm');
        $xmlWriter->writeAttribute('fo:margin-left', '2.501cm');
        $xmlWriter->writeAttribute('fo:margin-right', '2.501cm');
        $xmlWriter->writeAttribute('style:writing-mode', 'lr-tb');
        $xmlWriter->writeAttribute('style:layout-grid-color', '#c0c0c0');
        $xmlWriter->writeAttribute('style:layout-grid-lines', '25199');
        $xmlWriter->writeAttribute('style:layout-grid-base-height', '0.423cm');
        $xmlWriter->writeAttribute('style:layout-grid-ruby-height', '0cm');
        $xmlWriter->writeAttribute('style:layout-grid-mode', 'none');
        $xmlWriter->writeAttribute('style:layout-grid-ruby-below', 'false');
        $xmlWriter->writeAttribute('style:layout-grid-print', 'false');
        $xmlWriter->writeAttribute('style:layout-grid-display', 'false');
        $xmlWriter->writeAttribute('style:layout-grid-base-width', '0.37cm');
        $xmlWriter->writeAttribute('style:layout-grid-snap-to', 'true');
        $xmlWriter->writeAttribute('style:footnote-max-height', '0cm');
        //style:footnote-sep
        $xmlWriter->startElement('style:footnote-sep');
        $xmlWriter->writeAttribute('style:width', '0.018cm');
        $xmlWriter->writeAttribute('style:line-style', 'solid');
        $xmlWriter->writeAttribute('style:adjustment', 'left');
        $xmlWriter->writeAttribute('style:rel-width', '25%');
        $xmlWriter->writeAttribute('style:color', '#000000');
        $xmlWriter->endElement();
        $xmlWriter->endElement();
        // style:header-style
        $xmlWriter->startElement('style:header-style');
        $xmlWriter->endElement();
        // style:footer-style
        $xmlWriter->startElement('style:footer-style');
        $xmlWriter->endElement();
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        // office:master-styles
        $xmlWriter->startElement('office:master-styles');
        // style:master-page
        $xmlWriter->startElement('style:master-page');
        $xmlWriter->writeAttribute('style:name', 'Standard');
        $xmlWriter->writeAttribute('style:page-layout-name', 'Mpm1');
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->endElement();

        // Return
        return $xmlWriter->getData();
    }
}
