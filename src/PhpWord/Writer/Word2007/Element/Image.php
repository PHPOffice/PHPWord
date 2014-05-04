<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Style\Image as ImageStyle;

/**
 * Image element writer
 *
 * @since 0.10.0
 */
class Image extends Element
{
    /**
     * Write element
     */
    public function write()
    {
        if ($this->element->getIsWatermark()) {
            $this->writeWatermark();
        } else {
            $this->writeImage();
        }
    }

    /**
     * Write image element
     */
    private function writeImage()
    {
        $rId = $this->element->getRelationId() + ($this->element->isInSection() ? 6 : 0);

        $style = $this->element->getStyle();
        $width = $style->getWidth();
        $height = $style->getHeight();
        $align = $style->getAlign();
        $marginTop = $style->getMarginTop();
        $marginLeft = $style->getMarginLeft();
        $wrappingStyle = $style->getWrappingStyle();
        $positioning = $style->getPositioning();
        $w10wrapType = null;
        $imgStyle = '';
        if (null !== $width) {
            $imgStyle .= 'width:' . $width . 'px;';
        }
        if (null !== $height) {
            $imgStyle .= 'height:' . $height . 'px;';
        }
        if (null !== $marginTop) {
            $imgStyle .= 'margin-top:' . $marginTop . 'px;';
        }
        if (null !== $marginLeft) {
            $imgStyle .= 'margin-left:' . $marginLeft . 'px;';
        }
        $imgStyle.='position:absolute;mso-width-percent:0;mso-height-percent:0;';
        $imgStyle.='mso-width-relative:margin;mso-height-relative:margin;';
        switch ($positioning) {
            case ImageStyle::POSITION_RELATIVE:
                $imgStyle.='mso-position-horizontal:'.$style->getPosHorizontal().';';
                $imgStyle.='mso-position-horizontal-relative:'.$style->getPosHorizontalRel().';';
                $imgStyle.='mso-position-vertical:'.$style->getPosVertical().';';
                $imgStyle.='mso-position-vertical-relative:'.$style->getPosVerticalRel().';';
                $imgStyle.='margin-left:0;margin-top:0;';
                break;
            case ImageStyle::POSITION_ABSOLUTE:
                $imgStyle.='mso-position-horizontal-relative:page;';
                $imgStyle.='mso-position-vertical-relative:page;';
                break;
        }

        switch ($wrappingStyle) {
            case ImageStyle::WRAPPING_STYLE_BEHIND:
                $imgStyle .= 'z-index:-251658752;';
                break;
            case ImageStyle::WRAPPING_STYLE_INFRONT:
                $imgStyle .= 'z-index:251659264;mso-position-horizontal:absolute;mso-position-vertical:absolute;';
                break;
            case ImageStyle::WRAPPING_STYLE_SQUARE:
                $imgStyle .= 'z-index:251659264;mso-position-horizontal:absolute;mso-position-vertical:absolute;';
                $w10wrapType = 'square';
                break;
            case ImageStyle::WRAPPING_STYLE_TIGHT:
                $imgStyle .= 'z-index:251659264;mso-position-horizontal:absolute;mso-position-vertical:absolute;';
                $w10wrapType = 'tight';
                break;
        }
        
        if (!$this->withoutP) {
            $this->xmlWriter->startElement('w:p');
            if (!is_null($align)) {
                $this->xmlWriter->startElement('w:pPr');
                $this->xmlWriter->startElement('w:jc');
                $this->xmlWriter->writeAttribute('w:val', $align);
                $this->xmlWriter->endElement(); // w:jc
                $this->xmlWriter->endElement(); // w:pPr
            }
        }
        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:pict');
        $this->xmlWriter->startElement('v:shape');
        $this->xmlWriter->writeAttribute('type', '#_x0000_t75');
        $this->xmlWriter->writeAttribute('style', $imgStyle);
        $this->xmlWriter->startElement('v:imagedata');
        $this->xmlWriter->writeAttribute('r:id', 'rId' . $rId);
        $this->xmlWriter->writeAttribute('o:title', '');
        $this->xmlWriter->endElement(); // v:imagedata
        if (!is_null($w10wrapType)) {
            $this->xmlWriter->startElement('w10:wrap');
            $this->xmlWriter->writeAttribute('type', $w10wrapType);
            $this->xmlWriter->endElement(); // w10:wrap
        }
        $this->xmlWriter->endElement(); // v:shape
        $this->xmlWriter->endElement(); // w:pict
        $this->xmlWriter->endElement(); // w:r

        if (!$this->withoutP) {
            $this->xmlWriter->endElement(); // w:p
        }
    }
    /**
     * Write watermark element
     */
    private function writeWatermark()
    {
        $rId = $this->element->getRelationId();

        $style = $this->element->getStyle();
        $width = $style->getWidth();
        $height = $style->getHeight();
        $marginLeft = $style->getMarginLeft();
        $marginTop = $style->getMarginTop();
        $strStyle = 'position:absolute;';
        $strStyle .= ' width:' . $width . 'px;';
        $strStyle .= ' height:' . $height . 'px;';
        if (!is_null($marginTop)) {
            $strStyle .= ' margin-top:' . $marginTop . 'px;';
        }
        if (!is_null($marginLeft)) {
            $strStyle .= ' margin-left:' . $marginLeft . 'px;';
        }

        $this->xmlWriter->startElement('w:p');
        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:pict');
        $this->xmlWriter->startElement('v:shape');
        $this->xmlWriter->writeAttribute('type', '#_x0000_t75');
        $this->xmlWriter->writeAttribute('style', $strStyle);
        $this->xmlWriter->startElement('v:imagedata');
        $this->xmlWriter->writeAttribute('r:id', 'rId' . $rId);
        $this->xmlWriter->writeAttribute('o:title', '');
        $this->xmlWriter->endElement(); // v:imagedata
        $this->xmlWriter->endElement(); // v:shape
        $this->xmlWriter->endElement(); // w:pict
        $this->xmlWriter->endElement(); // w:r
        $this->xmlWriter->endElement(); // w:p
    }
}
