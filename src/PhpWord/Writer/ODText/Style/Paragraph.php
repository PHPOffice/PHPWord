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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\ODText\Style;

/**
 * Font style writer
 *
 * @since 0.10.0
 */
class Paragraph extends AbstractStyle
{
    /**
     * Write style.
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Paragraph) {
            return;
        }
        $xmlWriter = $this->getXmlWriter();

        $marginTop = (is_null($style->getSpaceBefore()) || $style->getSpaceBefore() == 0) ? '0' : round(17.6 / $style->getSpaceBefore(), 2);
        $marginBottom = (is_null($style->getSpaceAfter()) || $style->getSpaceAfter() == 0) ? '0' : round(17.6 / $style->getSpaceAfter(), 2);

        $xmlWriter->startElement('style:style');
        $xmlWriter->writeAttribute('style:name', $style->getStyleName());
        $xmlWriter->writeAttribute('style:family', 'paragraph');
        if ($style->isAuto()) {
            $xmlWriter->writeAttribute('style:parent-style-name', 'Standard');
            $xmlWriter->writeAttribute('style:master-page-name', 'Standard');
        }

        $xmlWriter->startElement('style:paragraph-properties');
        if ($style->isAuto()) {
            $xmlWriter->writeAttribute('style:page-number', 'auto');
        } else {
            $xmlWriter->writeAttribute('fo:margin-top', $marginTop . 'cm');
            $xmlWriter->writeAttribute('fo:margin-bottom', $marginBottom . 'cm');
            $xmlWriter->writeAttribute('fo:text-align', $style->getAlignment());
        }
        $xmlWriter->endElement(); //style:paragraph-properties

        $xmlWriter->endElement(); //style:style
    }
}
