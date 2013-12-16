<?php
/**
 * PHPWord
 *
 * Copyright (c) 2013 PHPWord
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
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 2013 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    0.7.0
 */

/**
 * Class PHPWord_Writer_ODText_Styles
 */
class PHPWord_Writer_ODText_Styles extends PHPWord_Writer_ODText_WriterPart
{
    /**
     * Write Styles file to XML format
     *
     * @param    PHPWord $pPHPWord
     * @return    string                        XML Output
     * @throws    Exception
     */
    public function writeStyles(PHPWord $pPHPWord = null)
    {
        // Create XML writer
        $objWriter = null;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $objWriter = new PHPWord_Shared_XMLWriter(PHPWord_Shared_XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $objWriter = new PHPWord_Shared_XMLWriter(PHPWord_Shared_XMLWriter::STORAGE_MEMORY);
        }

        // XML header
        $objWriter->startDocument('1.0', 'UTF-8');

        // Styles:Styles
        $objWriter->startElement('office:document-styles');
        $objWriter->writeAttribute('xmlns:office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
        $objWriter->writeAttribute('xmlns:style', 'urn:oasis:names:tc:opendocument:xmlns:style:1.0');
        $objWriter->writeAttribute('xmlns:text', 'urn:oasis:names:tc:opendocument:xmlns:text:1.0');
        $objWriter->writeAttribute('xmlns:table', 'urn:oasis:names:tc:opendocument:xmlns:table:1.0');
        $objWriter->writeAttribute('xmlns:draw', 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0');
        $objWriter->writeAttribute('xmlns:fo', 'urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0');
        $objWriter->writeAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
        $objWriter->writeAttribute('xmlns:dc', 'http://purl.org/dc/elements/1.1/');
        $objWriter->writeAttribute('xmlns:meta', 'urn:oasis:names:tc:opendocument:xmlns:meta:1.0');
        $objWriter->writeAttribute('xmlns:number', 'urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0');
        $objWriter->writeAttribute('xmlns:svg', 'urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0');
        $objWriter->writeAttribute('xmlns:chart', 'urn:oasis:names:tc:opendocument:xmlns:chart:1.0');
        $objWriter->writeAttribute('xmlns:dr3d', 'urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0');
        $objWriter->writeAttribute('xmlns:math', 'http://www.w3.org/1998/Math/MathML');
        $objWriter->writeAttribute('xmlns:form', 'urn:oasis:names:tc:opendocument:xmlns:form:1.0');
        $objWriter->writeAttribute('xmlns:script', 'urn:oasis:names:tc:opendocument:xmlns:script:1.0');
        $objWriter->writeAttribute('xmlns:ooo', 'http://openoffice.org/2004/office');
        $objWriter->writeAttribute('xmlns:ooow', 'http://openoffice.org/2004/writer');
        $objWriter->writeAttribute('xmlns:oooc', 'http://openoffice.org/2004/calc');
        $objWriter->writeAttribute('xmlns:dom', 'http://www.w3.org/2001/xml-events');
        $objWriter->writeAttribute('xmlns:rpt', 'http://openoffice.org/2005/report');
        $objWriter->writeAttribute('xmlns:of', 'urn:oasis:names:tc:opendocument:xmlns:of:1.2');
        $objWriter->writeAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
        $objWriter->writeAttribute('xmlns:grddl', 'http://www.w3.org/2003/g/data-view#');
        $objWriter->writeAttribute('xmlns:tableooo', 'http://openoffice.org/2009/table');
        $objWriter->writeAttribute('xmlns:css3t', 'http://www.w3.org/TR/css3-text/');
        $objWriter->writeAttribute('office:version', '1.2');


        // office:font-face-decls
        $objWriter->startElement('office:font-face-decls');
        $arrFonts = array();
        $styles = PHPWord_Style::getStyles();
        $numFonts = 0;
        if (count($styles) > 0) {
            foreach ($styles as $styleName => $style) {
                // PHPWord_Style_Font
                if ($style instanceof PHPWord_Style_Font) {
                    $numFonts++;
                    $name = $style->getName();
                    if (!in_array($name, $arrFonts)) {
                        $arrFonts[] = $name;

                        // style:font-face
                        $objWriter->startElement('style:font-face');
                        $objWriter->writeAttribute('style:name', $name);
                        $objWriter->writeAttribute('svg:font-family', $name);
                        $objWriter->endElement();
                    }
                }
            }
        }
        if (!in_array('Arial', $arrFonts)) {
            $objWriter->startElement('style:font-face');
            $objWriter->writeAttribute('style:name', 'Arial');
            $objWriter->writeAttribute('svg:font-family', 'Arial');
            $objWriter->endElement();
        }
        $objWriter->endElement();

        // office:styles
        $objWriter->startElement('office:styles');

        // style:default-style
        $objWriter->startElement('style:default-style');
        $objWriter->writeAttribute('style:family', 'paragraph');

        // style:paragraph-properties
        $objWriter->startElement('style:paragraph-properties');
        $objWriter->writeAttribute('fo:hyphenation-ladder-count', 'no-limit');
        $objWriter->writeAttribute('style:text-autospace', 'ideograph-alpha');
        $objWriter->writeAttribute('style:punctuation-wrap', 'hanging');
        $objWriter->writeAttribute('style:line-break', 'strict');
        $objWriter->writeAttribute('style:tab-stop-distance', '1.249cm');
        $objWriter->writeAttribute('style:writing-mode', 'page');
        $objWriter->endElement();

        // style:text-properties
        $objWriter->startElement('style:text-properties');
        $objWriter->writeAttribute('style:use-window-font-color', 'true');
        $objWriter->writeAttribute('style:font-name', 'Arial');
        $objWriter->writeAttribute('fo:font-size', '10pt');
        $objWriter->writeAttribute('fo:language', 'fr');
        $objWriter->writeAttribute('fo:country', 'FR');
        $objWriter->writeAttribute('style:letter-kerning', 'true');
        $objWriter->writeAttribute('style:font-name-asian', 'Arial2');
        $objWriter->writeAttribute('style:font-size-asian', '10pt');
        $objWriter->writeAttribute('style:language-asian', 'zh');
        $objWriter->writeAttribute('style:country-asian', 'CN');
        $objWriter->writeAttribute('style:font-name-complex', 'Arial2');
        $objWriter->writeAttribute('style:font-size-complex', '10pt');
        $objWriter->writeAttribute('style:language-complex', 'hi');
        $objWriter->writeAttribute('style:country-complex', 'IN');
        $objWriter->writeAttribute('fo:hyphenate', 'false');
        $objWriter->writeAttribute('fo:hyphenation-remain-char-count', '2');
        $objWriter->writeAttribute('fo:hyphenation-push-char-count', '2');
        $objWriter->endElement();

        $objWriter->endElement();

        // Write Style Definitions
        $styles = PHPWord_Style::getStyles();
        if (count($styles) > 0) {
            foreach ($styles as $styleName => $style) {
                if (preg_match('#^T[0-9]+$#', $styleName) == 0
                    && preg_match('#^P[0-9]+$#', $styleName) == 0
                ) {
                    // PHPWord_Style_Font
                    if ($style instanceof PHPWord_Style_Font) {
                        // style:style
                        $objWriter->startElement('style:style');
                        $objWriter->writeAttribute('style:name', $styleName);
                        $objWriter->writeAttribute('style:family', 'text');

                        // style:text-properties
                        $objWriter->startElement('style:text-properties');
                        $objWriter->writeAttribute('fo:font-size', ($style->getSize() / 2) . 'pt');
                        $objWriter->writeAttribute('style:font-size-asian', ($style->getSize() / 2) . 'pt');
                        $objWriter->writeAttribute('style:font-size-complex', ($style->getSize() / 2) . 'pt');
                        if ($style->getItalic()) {
                            $objWriter->writeAttribute('fo:font-style', 'italic');
                            $objWriter->writeAttribute('style:font-style-asian', 'italic');
                            $objWriter->writeAttribute('style:font-style-complex', 'italic');
                        }
                        if ($style->getBold()) {
                            $objWriter->writeAttribute('fo:font-weight', 'bold');
                            $objWriter->writeAttribute('style:font-weight-asian', 'bold');
                        }
                        $objWriter->endElement();
                        $objWriter->endElement();
                    } // PHPWord_Style_Paragraph
                    elseif ($style instanceof PHPWord_Style_Paragraph) {
                        // style:style
                        $objWriter->startElement('style:style');
                        $objWriter->writeAttribute('style:name', $styleName);
                        $objWriter->writeAttribute('style:family', 'paragraph');

                        //style:paragraph-properties
                        $objWriter->startElement('style:paragraph-properties');
                        $objWriter->writeAttribute('fo:margin-top', ((is_null($style->getSpaceBefore())) ? '0' : round(17.6 / $style->getSpaceBefore(), 2)) . 'cm');
                        $objWriter->writeAttribute('fo:margin-bottom', ((is_null($style->getSpaceAfter())) ? '0' : round(17.6 / $style->getSpaceAfter(), 2)) . 'cm');
                        $objWriter->writeAttribute('fo:text-align', $style->getAlign());
                        $objWriter->endElement();

                        $objWriter->endElement();

                    } // PHPWord_Style_TableFull
                    elseif ($style instanceof PHPWord_Style_TableFull) {
                    }
                }
            }
        }
        $objWriter->endElement();

        // office:automatic-styles
        $objWriter->startElement('office:automatic-styles');
        // style:page-layout
        $objWriter->startElement('style:page-layout');
        $objWriter->writeAttribute('style:name', 'Mpm1');
        // style:page-layout-properties
        $objWriter->startElement('style:page-layout-properties');
        $objWriter->writeAttribute('fo:page-width', "21.001cm");
        $objWriter->writeAttribute('fo:page-height', '29.7cm');
        $objWriter->writeAttribute('style:num-format', '1');
        $objWriter->writeAttribute('style:print-orientation', 'portrait');
        $objWriter->writeAttribute('fo:margin-top', '2.501cm');
        $objWriter->writeAttribute('fo:margin-bottom', '2cm');
        $objWriter->writeAttribute('fo:margin-left', '2.501cm');
        $objWriter->writeAttribute('fo:margin-right', '2.501cm');
        $objWriter->writeAttribute('style:writing-mode', 'lr-tb');
        $objWriter->writeAttribute('style:layout-grid-color', '#c0c0c0');
        $objWriter->writeAttribute('style:layout-grid-lines', '25199');
        $objWriter->writeAttribute('style:layout-grid-base-height', '0.423cm');
        $objWriter->writeAttribute('style:layout-grid-ruby-height', '0cm');
        $objWriter->writeAttribute('style:layout-grid-mode', 'none');
        $objWriter->writeAttribute('style:layout-grid-ruby-below', 'false');
        $objWriter->writeAttribute('style:layout-grid-print', 'false');
        $objWriter->writeAttribute('style:layout-grid-display', 'false');
        $objWriter->writeAttribute('style:layout-grid-base-width', '0.37cm');
        $objWriter->writeAttribute('style:layout-grid-snap-to', 'true');
        $objWriter->writeAttribute('style:footnote-max-height', '0cm');
        //style:footnote-sep
        $objWriter->startElement('style:footnote-sep');
        $objWriter->writeAttribute('style:width', '0.018cm');
        $objWriter->writeAttribute('style:line-style', 'solid');
        $objWriter->writeAttribute('style:adjustment', 'left');
        $objWriter->writeAttribute('style:rel-width', '25%');
        $objWriter->writeAttribute('style:color', '#000000');
        $objWriter->endElement();
        $objWriter->endElement();
        // style:header-style
        $objWriter->startElement('style:header-style');
        $objWriter->endElement();
        // style:footer-style
        $objWriter->startElement('style:footer-style');
        $objWriter->endElement();
        $objWriter->endElement();
        $objWriter->endElement();

        // office:master-styles
        $objWriter->startElement('office:master-styles');
        // style:master-page
        $objWriter->startElement('style:master-page');
        $objWriter->writeAttribute('style:name', 'Standard');
        $objWriter->writeAttribute('style:page-layout-name', 'Mpm1');
        $objWriter->endElement();
        $objWriter->endElement();

        $objWriter->endElement();

        // Return
        return $objWriter->getData();
    }
}
