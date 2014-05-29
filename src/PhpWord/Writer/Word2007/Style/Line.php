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

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use PhpOffice\PhpWord\Style\Line as LineStyle;

/**
 * Line style writer
 *
 */
class Line extends Image
{
    /**
     * Write style
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof LineStyle) {
            return;
        }
        $this->writeStyle($style);
    }

    /**
     * Write style attribute
     *
     * @param \PhpOffice\PhpWord\Style\Line $style
     */
    protected function writeStyle($style)
    {
        $xmlWriter = $this->getXmlWriter();

        $styles = $this->getElementStyle($style);
        if ($style->isFlip()) {
            $styles['flip'] = 'y';
        }
        $imageStyle = $this->assembleStyle($styles);
        $xmlWriter->writeAttribute('style', $imageStyle);

        // Connector type
        $xmlWriter->writeAttribute('o:connectortype', $style->getConnectorType());

        // Weight
        $weight = $style->getWeight();
        $xmlWriter->writeAttributeIf($weight !== null, 'strokeweight', $weight . 'pt');

        // Color
        $color = $style->getColor();
        $xmlWriter->writeAttributeIf($color !== null, 'strokecolor', $color);
    }

    /**
     * Write Line stroke
     */
    public function writeStroke()
    {
        $xmlWriter = $this->getXmlWriter();
        $style = $this->getStyle();
        if (!$style instanceof LineStyle) {
            return;
        }

        $dash = $style->getDash();
        $beginArrow = $style->getBeginArrow();
        $endArrow = $style->getEndArrow();
        $dashStyles = array(
            LineStyle::DASH_STYLE_DASH              => 'dash',
            LineStyle::DASH_STYLE_ROUND_DOT         => '1 1',
            LineStyle::DASH_STYLE_SQUARE_DOT        => '1 1',
            LineStyle::DASH_STYLE_DASH_DOT          => 'dashDot',
            LineStyle::DASH_STYLE_LONG_DASH         => 'longDash',
            LineStyle::DASH_STYLE_LONG_DASH_DOT     => 'longDashDot',
            LineStyle::DASH_STYLE_LONG_DASH_DOT_DOT => 'longDashDotDot',
        );

        if (($dash !== null) || ($beginArrow !== null) || ($endArrow !== null)) {
            $xmlWriter->startElement('v:stroke');

            $xmlWriter->writeAttributeIf($beginArrow !== null, 'startarrow', $beginArrow);
            $xmlWriter->writeAttributeIf($endArrow !== null, 'endarrow', $endArrow);

            if ($dash !== null) {
                if (array_key_exists($dash, $dashStyles)) {
                    $xmlWriter->writeAttribute('dashstyle', $dashStyles[$dash]);
                }
                if ($dash == LineStyle::DASH_STYLE_ROUND_DOT) {
                    $xmlWriter->writeAttribute('endcap', 'round');
                }
            }

            $xmlWriter->endElement(); //v:stroke
        }
    }
}
