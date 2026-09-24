<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License as published by the Free Software Foundation.
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

/**
 * Text box graphic style writer.
 */
class TextBox extends AbstractStyle
{
    public function write(): void
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\TextBox) {
            return;
        }

        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('style:style');
        $xmlWriter->writeAttribute('style:name', $style->getStyleName());
        $xmlWriter->writeAttribute('style:family', 'graphic');
        $xmlWriter->startElement('style:graphic-properties');
        Graphic::writeFillAndStroke($xmlWriter, $style->getBgColor() === null ? null : new \PhpOffice\PhpWord\Style\Fill(['color' => $style->getBgColor()]), $style->getBorderColor() === null ? null : new \PhpOffice\PhpWord\Style\Outline(['weight' => $style->getBorderSize(), 'color' => $style->getBorderColor()]));
        if ($style->hasInnerMargins()) {
            $margins = $style->getInnerMargin();
            $padding = [];
            foreach ([$margins[1], $margins[2], $margins[3], $margins[0]] as $margin) {
                $padding[] = Converter::pointToCm($margin / 20) . 'cm';
            }
            $xmlWriter->writeAttribute('fo:padding', implode(' ', $padding));
        }
        $xmlWriter->endElement();
        $xmlWriter->endElement();
    }
}
