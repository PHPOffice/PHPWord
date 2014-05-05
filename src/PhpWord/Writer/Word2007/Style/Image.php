<?php
/**
 * PHPWord
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
    private $w10wrap;

    /**
     * Write style
     */
    public function write()
    {
        if (!($this->style instanceof \PhpOffice\PhpWord\Style\Image)) {
            return;
        }

        $wrapping = $this->style->getWrappingStyle();
        $positioning = $this->style->getPositioning();

        // Default style array
        $styleArray = array(
            'mso-width-percent' => '0',
            'mso-height-percent' => '0',
            'mso-width-relative' => 'margin',
            'mso-height-relative' => 'margin',
        );
        $styleArray = array_merge($styleArray, $this->getElementStyle());

        // Absolute/relative positioning
        $styleArray['position'] = $positioning;
        if ($positioning == ImageStyle::POSITION_ABSOLUTE) {
            $styleArray['mso-position-horizontal-relative'] = 'page';
            $styleArray['mso-position-vertical-relative'] = 'page';
        } elseif ($positioning == ImageStyle::POSITION_RELATIVE) {
            $styleArray['mso-position-horizontal'] = $this->style->getPosHorizontal();
            $styleArray['mso-position-vertical'] = $this->style->getPosVertical();
            $styleArray['mso-position-horizontal-relative'] = $this->style->getPosHorizontalRel();
            $styleArray['mso-position-vertical-relative'] = $this->style->getPosVerticalRel();
            $styleArray['margin-left'] = 0;
            $styleArray['margin-top'] = 0;
        }

        // Wrapping style
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

        $this->xmlWriter->writeAttribute('style', $imageStyle);
    }

    /**
     * Write w10 wrapping
     *
     * @return array
     */
    public function writeW10Wrap()
    {
        if (!is_null($this->w10wrap)) {
            $this->xmlWriter->startElement('w10:wrap');
            $this->xmlWriter->writeAttribute('type', $this->w10wrap);
            $this->xmlWriter->endElement(); // w10:wrap
        }
    }

    /**
     * Get element style
     *
     * @return array
     */
    private function getElementStyle()
    {
        $styles = array();
        $styleValues = array(
            'width' => $this->style->getWidth(),
            'height' => $this->style->getHeight(),
            'margin-top' => $this->style->getMarginTop(),
            'margin-left' => $this->style->getMarginLeft()
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
    private function assembleStyle($styles = array())
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
