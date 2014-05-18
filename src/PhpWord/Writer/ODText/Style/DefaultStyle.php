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

namespace PhpOffice\PhpWord\Writer\ODText\Style;

use PhpOffice\PhpWord\Settings;

/**
 * Default-style writer; cannot use `default` only because it's a reserved word
 *
 * @since 0.11.0
 */
class DefaultStyle extends AbstractStyle
{
    /**
     * Write style
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();

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
}
