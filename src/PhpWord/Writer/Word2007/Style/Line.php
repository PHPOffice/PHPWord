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
use PhpOffice\PhpWord\Style\Line as LineStyle;
use PhpOffice\PhpWord\Style\Image as ImageStyle;

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
     * Copied function from Image/writeStyle in order to override getElementStyle
     */
    protected function writeStyle(ImageStyle $style)
    {
        $xmlWriter = $this->getXmlWriter();
        
        // Default style array
        $styleArray = array(
            'mso-width-percent' => '0',
            'mso-height-percent' => '0',
            'mso-width-relative' => 'margin',
            'mso-height-relative' => 'margin',
        );
        $styleArray = array_merge($styleArray, $this->getElementStyle($style));
    
        // Absolute/relative positioning
        $positioning = $style->getPositioning();
        $styleArray['position'] = $positioning;
        if ($positioning !== null) {
            $styleArray['mso-position-horizontal'] = $style->getPosHorizontal();
            $styleArray['mso-position-vertical'] = $style->getPosVertical();
            $styleArray['mso-position-horizontal-relative'] = $style->getPosHorizontalRel();
            $styleArray['mso-position-vertical-relative'] = $style->getPosVerticalRel();
        }
    
        // Wrapping style
        $wrapping = $style->getWrappingStyle();
        if ($wrapping == LineStyle::WRAPPING_STYLE_INLINE) {
            // Nothing to do when inline
        } elseif ($wrapping == LineStyle::WRAPPING_STYLE_BEHIND) {
            $styleArray['z-index'] = -251658752;
        } else {
            $styleArray['z-index'] = 251659264;
            $styleArray['mso-position-horizontal'] = 'absolute';
            $styleArray['mso-position-vertical'] = 'absolute';
        }
    
        // w10 wrapping
        if ($wrapping == LineStyle::WRAPPING_STYLE_SQUARE) {
            $this->w10wrap = 'square';
        } elseif ($wrapping == LineStyle::WRAPPING_STYLE_TIGHT) {
            $this->w10wrap = 'tight';
        }
    
        $imageStyle = $this->assembleStyle($styleArray);
    
        $xmlWriter->writeAttribute('style', $imageStyle);
        $xmlWriter->writeAttribute('o:connectortype', $style->getConnectorType());
        
        // Weight
        $weight = $style->getWeight();
        if ($weight !== null) {
            $xmlWriter->writeAttribute('strokeweight', $weight . 'pt');
        }
        
        // Color
        $color = $style->getColor();
        if ($color !== null) {
            $xmlWriter->writeAttribute('strokecolor', $color);
        }
    }
    
    /**
     * Get element style
     *
     * @param \PhpOffice\PhpWord\Style\Image $style
     * @return array
     */
    private function getElementStyle(LineStyle $style)
    {
        $styles = array();
        $styleValues = array(
            'width' => $style->getWidth(),
            'height' => $style->getHeight(),
            'margin-top' => $style->getMarginTop(),
            'margin-left' => $style->getMarginLeft()
        );
        foreach ($styleValues as $key => $value) {
            if (!is_null($value)) {
                $styles[$key] = $value . 'px';
            }
        }
        if ($style->isFlip()) {
            $styles['flip']='y';
        }
    
        return $styles;
    }
    
    /**
     * Write Line stroke
     *
     */
    public function writeStroke()
    {
        $style = $this->getStyle();
        $xmlWriter = $this->getXmlWriter();
        
        $dash = $style->getDash();
        $beginArrow = $style->getBeginArrow();
        $endArrow = $style->getEndArrow();
        
        if (($dash !== null) || ($beginArrow !== null) || ($endArrow !== null)) {
            $xmlWriter->startElement('v:stroke');
            if ($beginArrow !== null) {
                $xmlWriter->writeAttribute('startarrow', $beginArrow);
            }
            if ($endArrow !== null) {
                $xmlWriter->writeAttribute('endarrow', $endArrow);
            }
            if ($dash !==null) {
                switch ($dash) {
                    case LineStyle::DASH_STYLE_DASH:
                        $xmlWriter->writeAttribute('dashstyle', 'dash');
                        break;
                    case LineStyle::DASH_STYLE_ROUND_DOT:
                        $xmlWriter->writeAttribute('dashstyle', '1 1');
                        $xmlWriter->writeAttribute('endcap', 'round');
                        break;
                    case LineStyle::DASH_STYLE_SQUARE_DOT:
                        $xmlWriter->writeAttribute('dashstyle', '1 1');
                        break;
                    case LineStyle::DASH_STYLE_DASH_DOT:
                        $xmlWriter->writeAttribute('dashstyle', 'dashDot');
                        break;
                    case LineStyle::DASH_STYLE_LONG_DASH:
                        $xmlWriter->writeAttribute('dashstyle', 'longDash');
                        break;
                    case LineStyle::DASH_STYLE_LONG_DASH_DOT:
                        $xmlWriter->writeAttribute('dashstyle', 'longDashDot');
                        break;
                    case LineStyle::DASH_STYLE_LONG_DASH_DOT_DOT:
                        $xmlWriter->writeAttribute('dashstyle', 'longDashDotDot');
                        break;
                }
            }
            $xmlWriter->endElement(); //v:stroke
        }
    }
}
