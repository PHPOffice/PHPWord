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

use PhpOffice\PhpWord\Style\Alignment as AlignmentStyle;
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
        if (!$style instanceof ImageStyle) {
            return;
        }
        $this->writeStyle($style);
    }

    /**
     * Write style attribute
     *
     * @param \PhpOffice\PhpWord\Style\Image $style
     */
    protected function writeStyle($style)
    {
        $xmlWriter = $this->getXmlWriter();

        $styles = $this->getElementStyle($style);
        $imageStyle = $this->assembleStyle($styles);

        $xmlWriter->writeAttribute('style', $imageStyle);
    }

    /**
     * Write alignment
     */
    public function writeAlignment()
    {
        $style = $this->getStyle();
        if (!$style instanceof ImageStyle) {
            return;
        }

        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('w:pPr');
        $styleWriter = new Alignment($xmlWriter, new AlignmentStyle(array('value' => $style->getAlign())));
        $styleWriter->write();
        $xmlWriter->endElement(); // w:pPr
    }

    /**
     * Write w10 wrapping
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
     * Get element style
     *
     * @param \PhpOffice\PhpWord\Style\Image $style
     * @return array
     */
    protected function getElementStyle(ImageStyle $style)
    {
        $styles = array(
            'mso-width-percent' => '0',
            'mso-height-percent' => '0',
            'mso-width-relative' => 'margin',
            'mso-height-relative' => 'margin',
        );

        // Dimension
        $dimensions = array(
            'width' => $style->getWidth(),
            'height' => $style->getHeight(),
            'margin-top' => $style->getMarginTop(),
            'margin-left' => $style->getMarginLeft()
        );
        foreach ($dimensions as $key => $value) {
            if ($value !== null) {
                $styles[$key] = $value . 'px';
            }
        }

        // Absolute/relative positioning
        $positioning = $style->getPositioning();
        $styles['position'] = $positioning;
        if ($positioning !== null) {
            $styles['mso-position-horizontal'] = $style->getPosHorizontal();
            $styles['mso-position-vertical'] = $style->getPosVertical();
            $styles['mso-position-horizontal-relative'] = $style->getPosHorizontalRel();
            $styles['mso-position-vertical-relative'] = $style->getPosVerticalRel();
        }

        // Wrapping style
        $wrapping = $style->getWrappingStyle();
        if ($wrapping == ImageStyle::WRAPPING_STYLE_INLINE) {
            // Nothing to do when inline
        } elseif ($wrapping == ImageStyle::WRAPPING_STYLE_BEHIND) {
            $styles['z-index'] = -251658752;
        } else {
            $styles['z-index'] = 251659264;
            $styles['mso-position-horizontal'] = 'absolute';
            $styles['mso-position-vertical'] = 'absolute';
        }

        // w10 wrapping
        if ($wrapping == ImageStyle::WRAPPING_STYLE_SQUARE) {
            $this->w10wrap = 'square';
        } elseif ($wrapping == ImageStyle::WRAPPING_STYLE_TIGHT) {
            $this->w10wrap = 'tight';
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
