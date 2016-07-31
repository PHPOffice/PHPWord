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
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\ODText\Part;

use PhpOffice\Common\XMLWriter;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style;

/**
 * ODText styles part writer: styles.xml
 */
class Styles extends AbstractPart
{
    /**
     * Write part
     *
     * @return string
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8');
        $xmlWriter->startElement('office:document-styles');
        $this->writeCommonRootAttributes($xmlWriter);

        // Font declarations
        $this->writeFontFaces($xmlWriter);

        // Office styles
        $xmlWriter->startElement('office:styles');
        $this->writeDefault($xmlWriter);
        $this->writeNamed($xmlWriter);
        $xmlWriter->endElement();

        // Automatic styles
        $xmlWriter->startElement('office:automatic-styles');
        $this->writePageLayout($xmlWriter);
        $this->writeMaster($xmlWriter);
        $xmlWriter->endElement();

        $xmlWriter->endElement(); // office:document-styles

        return $xmlWriter->getData();
    }

    /**
     * Write default styles.
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @return void
     */
    private function writeDefault(XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('style:default-style');
        $xmlWriter->writeAttribute('style:family', 'paragraph');

        // Paragraph
        $xmlWriter->startElement('style:paragraph-properties');
        $xmlWriter->writeAttribute('fo:hyphenation-ladder-count', 'no-limit');
        $xmlWriter->writeAttribute('style:text-autospace', 'ideograph-alpha');
        $xmlWriter->writeAttribute('style:punctuation-wrap', 'hanging');
        $xmlWriter->writeAttribute('style:line-break', 'strict');
        $xmlWriter->writeAttribute('style:tab-stop-distance', '1.249cm');
        $xmlWriter->writeAttribute('style:writing-mode', 'page');
        $xmlWriter->endElement(); // style:paragraph-properties

        // Font
        $xmlWriter->startElement('style:text-properties');
        $xmlWriter->writeAttribute('style:use-window-font-color', 'true');
        $xmlWriter->writeAttribute('style:font-name', Settings::getDefaultFontName());
        $xmlWriter->writeAttribute('fo:font-size', Settings::getDefaultFontSize() . 'pt');
        $xmlWriter->writeAttribute('fo:language', 'fr');
        $xmlWriter->writeAttribute('fo:country', 'FR');
        $xmlWriter->writeAttribute('style:letter-kerning', 'true');
        $xmlWriter->writeAttribute('style:font-name-asian', Settings::getDefaultFontName() . '2');
        $xmlWriter->writeAttribute('style:font-size-asian', Settings::getDefaultFontSize() . 'pt');
        $xmlWriter->writeAttribute('style:language-asian', 'zh');
        $xmlWriter->writeAttribute('style:country-asian', 'CN');
        $xmlWriter->writeAttribute('style:font-name-complex', Settings::getDefaultFontName() . '2');
        $xmlWriter->writeAttribute('style:font-size-complex', Settings::getDefaultFontSize() . 'pt');
        $xmlWriter->writeAttribute('style:language-complex', 'hi');
        $xmlWriter->writeAttribute('style:country-complex', 'IN');
        $xmlWriter->writeAttribute('fo:hyphenate', 'false');
        $xmlWriter->writeAttribute('fo:hyphenation-remain-char-count', '2');
        $xmlWriter->writeAttribute('fo:hyphenation-push-char-count', '2');
        $xmlWriter->endElement(); // style:text-properties

        $xmlWriter->endElement(); // style:default-style
    }

    /**
     * Write named styles.
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @return void
     */
    private function writeNamed(XMLWriter $xmlWriter)
    {
        $styles = Style::getStyles();
        if (count($styles) > 0) {
            foreach ($styles as $style) {
                if ($style->isAuto() === false) {
                    $styleClass = str_replace('\\Style\\', '\\Writer\\ODText\\Style\\', get_class($style));
                    if (class_exists($styleClass)) {
                        /** @var $styleWriter \PhpOffice\PhpWord\Writer\ODText\Style\AbstractStyle Type hint */
                        $styleWriter = new $styleClass($xmlWriter, $style);
                        $styleWriter->write();
                    }
                }
            }
        }
    }
    /**
     * Write page layout styles.
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @return void
     */
    private function writePageLayout(XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('style:page-layout');
        $xmlWriter->writeAttribute('style:name', 'Mpm1');

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

        $xmlWriter->startElement('style:footnote-sep');
        $xmlWriter->writeAttribute('style:width', '0.018cm');
        $xmlWriter->writeAttribute('style:line-style', 'solid');
        $xmlWriter->writeAttribute('style:adjustment', 'left');
        $xmlWriter->writeAttribute('style:rel-width', '25%');
        $xmlWriter->writeAttribute('style:color', '#000000');
        $xmlWriter->endElement(); //style:footnote-sep

        $xmlWriter->endElement(); // style:page-layout-properties


        $xmlWriter->startElement('style:header-style');
        $xmlWriter->endElement(); // style:header-style

        $xmlWriter->startElement('style:footer-style');
        $xmlWriter->endElement(); // style:footer-style

        $xmlWriter->endElement(); // style:page-layout
    }

    /**
     * Write master style.
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @return void
     */
    private function writeMaster(XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('office:master-styles');

        $xmlWriter->startElement('style:master-page');
        $xmlWriter->writeAttribute('style:name', 'Standard');
        $xmlWriter->writeAttribute('style:page-layout-name', 'Mpm1');
        $xmlWriter->endElement(); // style:master-page

        $xmlWriter->endElement(); // office:master-styles
    }
}
