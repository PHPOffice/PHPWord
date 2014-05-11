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

use PhpOffice\PhpWord\Style\Image as ImageStyle;

/**
 * Image style writer
 *
 * @since 0.10.0
 */
class Image extends AbstractStyle
{
    /**
     * w10 namespace wrapping type
     *
     * @var string
     */
    protected $w10wrap;

    /**
     * Write style
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Image) {
            return;
        }
        $this->writeStyle();
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

        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('w10:wrap');
        $xmlWriter->writeAttribute('type', $this->w10wrap);
        $xmlWriter->endElement(); // w10:wrap
    }

    /**
     * Write style attribute
     */
    protected function writeStyle()
    {
        $xmlWriter = $this->getXmlWriter();
        $style = $this->getStyle();

        // Default style array
        $styleArray = array(
            'mso-width-percent' => '0',
            'mso-height-percent' => '0',
            'mso-width-relative' => 'margin',
            'mso-height-relative' => 'margin',
        );
        $styleArray = array_merge($styleArray, $this->getElementStyle());

        // Absolute/relative positioning
        $positioning = $style->getPositioning();
        $styleArray['position'] = $positioning;
        if ($positioning == ImageStyle::POSITION_ABSOLUTE) {
            $styleArray['mso-position-horizontal-relative'] = 'page';
            $styleArray['mso-position-vertical-relative'] = 'page';
        } elseif ($positioning == ImageStyle::POSITION_RELATIVE) {
            $styleArray['mso-position-horizontal'] = $style->getPosHorizontal();
            $styleArray['mso-position-vertical'] = $style->getPosVertical();
            $styleArray['mso-position-horizontal-relative'] = $style->getPosHorizontalRel();
            $styleArray['mso-position-vertical-relative'] = $style->getPosVerticalRel();
            $styleArray['margin-left'] = 0;
            $styleArray['margin-top'] = 0;
        }

        // Wrapping style
        $wrapping = $style->getWrappingStyle();
        if ($wrapping == ImageStyle::WRAPPING_STYLE_INLINE) {
            // Nothing to do when inline
        } elseif ($wrapping == ImageStyle::WRAPPING_STYLE_BEHIND) {
            $styleArray['z-index'] = -251658752;
        } else {
            $styleArray['z-index'] = 251659264;
            $styleArray['mso-position-horizontal'] = 'absolute';
            $styleArray['mso-position-vertical'] = 'absolute';
        }

        // w10 wrapping
        if ($wrapping == ImageStyle::WRAPPING_STYLE_SQUARE) {
            $this->w10wrap = 'square';
        } elseif ($wrapping == ImageStyle::WRAPPING_STYLE_TIGHT) {
            $this->w10wrap = 'tight';
        }

        $imageStyle = $this->assembleStyle($styleArray);

        $xmlWriter->writeAttribute('style', $imageStyle);
    }

    /**
     * Get element style
     *
     * @return array
     */
    private function getElementStyle()
    {
        $style = $this->getStyle();
        $styles = array();
        $styleValues = array(
            'width' => $style->getWidth(),
            'height' => $style->getHeight(),
            'margin-top' => $style->getMarginTop(),
            'margin-left' => $style->getMarginLeft()
        );
        foreach ($styleValues as $key => $value) {
            if (!is_null($value) && $value != '') {
                $styles[$key] = $value . 'px';
            }
        }

        return $styles;
    }

    /**
     * Assemble style array into style string
     *
     * @param array $styles
     * @return string
     */
    protected function assembleStyle($styles = array())
    {
        $style = '';
        foreach ($styles as $key => $value) {
            if (!is_null($value) && $value != '') {
                $style .= "{$key}:{$value}; ";
            }
        }

        return trim($style);
    }
}
