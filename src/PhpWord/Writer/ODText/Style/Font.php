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
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\ODText\Style;

use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style\Language;

/**
 * Font style writer.
 *
 * @since 0.10.0
 */
class Font extends AbstractStyle
{
    /**
     * Write style.
     */
    public function write(): void
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Font) {
            return;
        }
        $xmlWriter = $this->getXmlWriter();

        $stylep = $style->getParagraph();
        if ($stylep instanceof \PhpOffice\PhpWord\Style\Paragraph) {
            $temp1 = clone $stylep;
            $temp1->setStyleName($style->getStyleName());
            $temp2 = new Paragraph($xmlWriter, $temp1);
            $temp2->write();
        }

        $xmlWriter->startElement('style:style');
        $xmlWriter->writeAttribute('style:name', $style->getStyleName());
        $xmlWriter->writeAttribute('style:family', 'text');
        $xmlWriter->startElement('style:text-properties');

        // Name
        $font = $style->getName();
        $xmlWriter->writeAttributeIf($font != '', 'style:font-name', $font);
        $xmlWriter->writeAttributeIf($font != '', 'style:font-name-complex', $font);
        $size = $style->getSize();

        // Size
        $xmlWriter->writeAttributeIf(is_numeric($size), 'fo:font-size', $size . 'pt');
        $xmlWriter->writeAttributeIf(is_numeric($size), 'style:font-size-asian', $size . 'pt');
        $xmlWriter->writeAttributeIf(is_numeric($size), 'style:font-size-complex', $size . 'pt');

        // Color
        $color = $style->getColor();
        $xmlWriter->writeAttributeIf($color != '', 'fo:color', '#' . Converter::stringToRgb($color));

        // Bold & italic
        $xmlWriter->writeAttributeIf($style->isBold(), 'fo:font-weight', 'bold');
        $xmlWriter->writeAttributeIf($style->isBold(), 'style:font-weight-asian', 'bold');
        $xmlWriter->writeAttributeIf($style->isItalic(), 'fo:font-style', 'italic');
        $xmlWriter->writeAttributeIf($style->isItalic(), 'style:font-style-asian', 'italic');
        $xmlWriter->writeAttributeIf($style->isItalic(), 'style:font-style-complex', 'italic');

        // Underline
        // @todo Various mode of underline
        $underline = $style->getUnderline();
        $xmlWriter->writeAttributeIf($underline != 'none', 'style:text-underline-style', 'solid');

		$underlineColor = $style->getUnderlineColor();
		$xmlWriter->writeAttributeIf($underlineColor != '', 'style:text-underline-color', '#' . Converter::stringToRgb($underlineColor));

        // Strikethrough, double strikethrough
        $xmlWriter->writeAttributeIf($style->isStrikethrough(), 'style:text-line-through-type', 'single');
        $xmlWriter->writeAttributeIf($style->isDoubleStrikethrough(), 'style:text-line-through-type', 'double');

        // Small caps, all caps
        $xmlWriter->writeAttributeIf($style->isSmallCaps(), 'fo:font-variant', 'small-caps');
        $xmlWriter->writeAttributeIf($style->isAllCaps(), 'fo:text-transform', 'uppercase');

        //Hidden text
        $xmlWriter->writeAttributeIf($style->isHidden(), 'text:display', 'none');

        // Superscript/subscript
        $xmlWriter->writeAttributeIf($style->isSuperScript(), 'style:text-position', 'super');
        $xmlWriter->writeAttributeIf($style->isSubScript(), 'style:text-position', 'sub');

        $language = $style->getLang();
        if ($style->isNoProof()) {
            $xmlWriter->writeAttribute('fo:language', 'zxx');
            $xmlWriter->writeAttribute('style:language-asian', 'zxx');
            $xmlWriter->writeAttribute('style:language-complex', 'zxx');
            $xmlWriter->writeAttribute('fo:country', 'none');
            $xmlWriter->writeAttribute('style:country-asian', 'none');
            $xmlWriter->writeAttribute('style:country-complex', 'none');
        } elseif ($language instanceof Language) {
            self::writeLanguage($xmlWriter, $language->getLatin());
            self::writeLanguage($xmlWriter, $language->getEastAsia(), 'asian');
            self::writeLanguage($xmlWriter, $language->getBidirectional(), 'complex');
        }

        // @todo Foreground-Color

        // @todo Background color

        $xmlWriter->endElement(); // style:text-properties
        $xmlWriter->endElement(); // style:style
    }

    /**
     * Write a BCP 47 language tag, such as uk-UA or sr-Latn-RS, as the language, script and country of ODF.
     *
     * @param string $script '' for Western text, 'asian' or 'complex'
     */
    public static function writeLanguage(XMLWriter $xmlWriter, ?string $tag, string $script = ''): void
    {
        if ($tag === null || $tag === '') {
            return;
        }
        $subtags = explode('-', $tag);
        $language = array_shift($subtags);
        // Not a language, such as x-none, which Word writes for no language
        if (!preg_match('/^[A-Za-z]{2,3}$/', $language)) {
            return;
        }
        $scriptCode = isset($subtags[0]) && preg_match('/^[A-Za-z]{4}$/', $subtags[0]) ? array_shift($subtags) : null;
        $country = isset($subtags[0]) && preg_match('/^([A-Za-z]{2}|\\d{3})$/', $subtags[0]) ? array_shift($subtags) : null;
        $suffix = $script === '' ? '' : '-' . $script;
        $xmlWriter->writeAttribute($script === '' ? 'fo:language' : 'style:language' . $suffix, $language);
        $xmlWriter->writeAttributeIf($scriptCode !== null, $script === '' ? 'fo:script' : 'style:script' . $suffix, (string) $scriptCode);
        $xmlWriter->writeAttributeIf($country !== null, $script === '' ? 'fo:country' : 'style:country' . $suffix, (string) $country);
        // As LibreOffice writes it: the whole tag when it has a script, a variant or an extension
        $xmlWriter->writeAttributeIf($scriptCode !== null || count($subtags) > 0, 'style:rfc-language-tag' . $suffix, $tag);
    }
}
