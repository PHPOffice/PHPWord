<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\ODText\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Exception\Exception;

/**
 * ODText styloes part writer
 */
class Styles extends AbstractPart
{
    /**
     * Write Styles file to XML format
     *
     * @param  \PhpOffice\PhpWord\PhpWord $phpWord
     * @return string XML Output
     */
    public function writeStyles(PhpWord $phpWord = null)
    {
        if (is_null($phpWord)) {
            throw new Exception("No PhpWord assigned.");
        }

        // Create XML writer
        $xmlWriter = $this->getXmlWriter();

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8');

        // Styles:Styles
        $xmlWriter->startElement('office:document-styles');
        $this->writeCommonRootAttributes($xmlWriter);

        // office:font-face-decls
        $this->writeFontFaces($xmlWriter);

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
                    $styleClass = str_replace('Style', 'Writer\\ODText\\Style', get_class($style));
                    if (class_exists($styleClass)) {
                        $styleWriter = new $styleClass($xmlWriter, $style);
                        $styleWriter->write();
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
