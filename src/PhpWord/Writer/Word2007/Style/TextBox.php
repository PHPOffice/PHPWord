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
        if (is_null($style = $this->getStyle())) {
            return;
        }
        $xmlWriter = $this->getXmlWriter();
        $wrapping = $style->getWrappingStyle();
        $positioning = $style->getPositioning();

        // Default style array
        $styleArray = array(
            'mso-width-percent' => '0',
            'mso-height-percent' => '0',
            'mso-width-relative' => 'margin',
            'mso-height-relative' => 'margin',
        );
        $styleArray = array_merge($styleArray, $this->getElementStyle($style));

        // Absolute/relative positioning
        $styleArray['position'] = $positioning;
        if ($positioning == TextBoxStyle::POSITION_ABSOLUTE) {
            $styleArray['mso-position-horizontal-relative'] = 'page';
            $styleArray['mso-position-vertical-relative'] = 'page';
        } elseif ($positioning == TextBoxStyle::POSITION_RELATIVE) {
            $styleArray['mso-position-horizontal'] = $style->getPosHorizontal();
            $styleArray['mso-position-vertical'] = $style->getPosVertical();
            $styleArray['mso-position-horizontal-relative'] = $style->getPosHorizontalRel();
            $styleArray['mso-position-vertical-relative'] = $style->getPosVerticalRel();
            $styleArray['margin-left'] = 0;
            $styleArray['margin-top'] = 0;
        }

        // Wrapping style
        if ($wrapping == TextBoxStyle::WRAPPING_STYLE_INLINE) {
            // Nothing to do when inline
        } elseif ($wrapping == TextBoxStyle::WRAPPING_STYLE_BEHIND) {
            $styleArray['z-index'] = -251658752;
        } else {
            $styleArray['z-index'] = 251659264;
            $styleArray['mso-position-horizontal'] = 'absolute';
            $styleArray['mso-position-vertical'] = 'absolute';
        }

        // w10 wrapping
        if ($wrapping == TextBoxStyle::WRAPPING_STYLE_SQUARE) {
            $this->w10wrap = 'square';
        } elseif ($wrapping == TextBoxStyle::WRAPPING_STYLE_TIGHT) {
            $this->w10wrap = 'tight';
        }

        $textboxStyle = $this->assembleStyle($styleArray);

        $xmlWriter->writeAttribute('style', $textboxStyle);

        $borderSize = $style->getBorderSize();
        if ($borderSize !== null) {
            $xmlWriter->writeAttribute('strokeweight', $style->getBorderSize().'pt');
        }

        $borderColor = $style->getBorderColor();
        if (empty($borderColor)) {
            $xmlWriter->writeAttribute('stroked', 'f');
        } else {
            $xmlWriter->writeAttribute('strokecolor', $borderColor);
        }
        //@todo <v:stroke dashstyle="dashDot" linestyle="thickBetweenThin"/>

    }

    /**
     * Write w10 wrapping
     *
     * @return array
     */
    public function writeW10Wrap()
    {
        $xmlWriter = $this->getXmlWriter();

        if (!is_null($this->w10wrap)) {
            $xmlWriter->startElement('w10:wrap');
            $xmlWriter->writeAttribute('type', $this->w10wrap);

            switch ($style->getPositioning()) {
                case TextBoxStyle::POSITION_ABSOLUTE:
                    $xmlWriter->writeAttribute('anchorx', "page");
                    $xmlWriter->writeAttribute('anchory', "page");
                    break;
                case TextBoxStyle::POSITION_RELATIVE:
                    switch ($style->getPosVerticalRel()) {
                        case TextBoxStyle::POSITION_RELATIVE_TO_MARGIN:
                            $xmlWriter->writeAttribute('anchory', "margin");
                            break;
                        case TextBoxStyle::POSITION_RELATIVE_TO_PAGE:
                            $xmlWriter->writeAttribute('anchory', "page");
                            break;
                        case TextBoxStyle::POSITION_RELATIVE_TO_TMARGIN:
                            $xmlWriter->writeAttribute('anchory', "margin");
                            break;
                        case TextBoxStyle::POSITION_RELATIVE_TO_BMARGIN:
                            $xmlWriter->writeAttribute('anchory', "page");
                            break;
                    }
                    switch ($style->getPosHorizontalRel()) {
                        case TextBoxStyle::POSITION_RELATIVE_TO_MARGIN:
                            $xmlWriter->writeAttribute('anchorx', "margin");
                            break;
                        case TextBoxStyle::POSITION_RELATIVE_TO_PAGE:
                            $xmlWriter->writeAttribute('anchorx', "page");
                            break;
                        case TextBoxStyle::POSITION_RELATIVE_TO_LMARGIN:
                            $xmlWriter->writeAttribute('anchorx', "margin");
                            break;
                        case TextBoxStyle::POSITION_RELATIVE_TO_RMARGIN:
                            $xmlWriter->writeAttribute('anchorx', "page");
                            break;
                    }
            }

            $xmlWriter->endElement(); // w10:wrap
        }
    }
}
