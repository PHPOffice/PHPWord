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

use PhpOffice\PhpWord\Style\TextBox as TextBoxStyle;

/**
 * TextBox style writer
 *
 * @since 0.11.0
 */
class TextBox extends Image
{
    /**
     * Write style
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof TextBoxStyle) {
            return;
        }
        $this->writeStyle($style);
        $this->writeBorder($style);
    }

    /**
     * Write w10 wrapping
     *
     * @return array
     */
    public function writeW10Wrap()
    {
        if (is_null($this->w10wrap)) {
            return;
        }
        $style = $this->getStyle();
        if (!$style instanceof TextBoxStyle) {
            return;
        }

        $relativePositions = array(
            TextBoxStyle::POSITION_RELATIVE_TO_MARGIN  => 'margin',
            TextBoxStyle::POSITION_RELATIVE_TO_PAGE    => 'page',
            TextBoxStyle::POSITION_RELATIVE_TO_TMARGIN => 'margin',
            TextBoxStyle::POSITION_RELATIVE_TO_BMARGIN => 'page',
            TextBoxStyle::POSITION_RELATIVE_TO_LMARGIN => 'margin',
            TextBoxStyle::POSITION_RELATIVE_TO_RMARGIN => 'page',
        );
        $pos = $style->getPositioning();
        $vPos = $style->getPosVerticalRel();
        $hPos = $style->getPosHorizontalRel();

        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('w10:wrap');
        $xmlWriter->writeAttribute('type', $this->w10wrap);

        if ($pos == TextBoxStyle::POSITION_ABSOLUTE) {
            $xmlWriter->writeAttribute('anchorx', "page");
            $xmlWriter->writeAttribute('anchory', "page");
        } elseif ($pos == TextBoxStyle::POSITION_RELATIVE) {
            if (array_key_exists($vPos, $relativePositions)) {
                $xmlWriter->writeAttribute('anchory', $relativePositions[$vPos]);
            }
            if (array_key_exists($hPos, $relativePositions)) {
                $xmlWriter->writeAttribute('anchorx', $relativePositions[$hPos]);
            }
        }

        $xmlWriter->endElement(); // w10:wrap
    }

    /**
     * Writer inner margin
     */
    public function writeInnerMargin()
    {
        $style = $this->getStyle();
        if (!$style instanceof TextBoxStyle || !$style->hasInnerMargins()) {
            return;
        }

        $xmlWriter = $this->getXmlWriter();
        $margins = implode(', ', $style->getInnerMargin());
        $xmlWriter->writeAttribute('inset', $margins);
    }

    /**
     * Writer border
     */
    private function writeBorder(TextBoxStyle $style)
    {
        $xmlWriter = $this->getXmlWriter();

        // Border size
        $borderSize = $style->getBorderSize();
        if ($borderSize !== null) {
            $xmlWriter->writeAttribute('strokeweight', $borderSize . 'pt');
        }

        // Border color
        $borderColor = $style->getBorderColor();
        if (empty($borderColor)) {
            $xmlWriter->writeAttribute('stroked', 'f');
        } else {
            $xmlWriter->writeAttribute('strokecolor', $borderColor);
        }
        //@todo <v:stroke dashstyle="dashDot" linestyle="thickBetweenThin"/>
    }
}
