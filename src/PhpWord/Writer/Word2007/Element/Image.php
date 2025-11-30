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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Element\Image as ImageElement;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style\Font as FontStyle;
use PhpOffice\PhpWord\Style\Frame as FrameStyle;
use PhpOffice\PhpWord\Writer\Word2007\Style\Font as FontStyleWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Image as ImageStyleWriter;

/**
 * Image element writer.
 *
 * @since 0.10.0
 */
class Image extends AbstractElement
{
    /**
     * Write element.
     */
    public function write(): void
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof ImageElement) {
            return;
        }
        $ext = strtolower(pathinfo($element->getSource(), PATHINFO_EXTENSION));
        if ($ext === 'svg') {
            $this->writeSvgDrawing($xmlWriter, $element);

            return;
        }

        if ($element->isWatermark()) {
            $this->writeWatermark($xmlWriter, $element);
        } else {
            $this->writeImage($xmlWriter, $element);
        }
    }

    /**
     * Write image element.
     */
    private function writeImage(XMLWriter $xmlWriter, ImageElement $element): void
    {
        $rId = $element->getRelationId() + ($element->isInSection() ? 6 : 0);
        $style = $element->getStyle();
        $styleWriter = new ImageStyleWriter($xmlWriter, $style);

        if (!$this->withoutP) {
            $xmlWriter->startElement('w:p');
            $styleWriter->writeAlignment();
        }
        $this->writeCommentRangeStart();

        $xmlWriter->startElement('w:r');

        // Write position
        $position = $style->getPosition();
        if ($position && $style->getWrap() == FrameStyle::WRAP_INLINE) {
            $fontStyle = new FontStyle('text');
            $fontStyle->setPosition($position);
            $fontStyleWriter = new FontStyleWriter($xmlWriter, $fontStyle);
            $fontStyleWriter->write();
        }

        $xmlWriter->startElement('w:pict');
        $xmlWriter->startElement('v:shape');
        $xmlWriter->writeAttribute('type', '#_x0000_t75');
        $xmlWriter->writeAttribute('stroked', 'f');

        $styleWriter->write();

        $xmlWriter->startElement('v:imagedata');
        $xmlWriter->writeAttribute('r:id', 'rId' . $rId);
        $xmlWriter->writeAttribute('o:title', '');
        $xmlWriter->endElement(); // v:imagedata

        $xmlWriter->endElement(); // v:shape
        $xmlWriter->endElement(); // w:pict
        $xmlWriter->endElement(); // w:r

        $this->endElementP();
    }

    /**
     * Write watermark element.
     */
    private function writeWatermark(XMLWriter $xmlWriter, ImageElement $element): void
    {
        $rId = $element->getRelationId();
        $style = $element->getStyle();
        $style->setPositioning('absolute');
        $styleWriter = new ImageStyleWriter($xmlWriter, $style);

        if (!$this->withoutP) {
            $xmlWriter->startElement('w:p');
        }
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:pict');
        $xmlWriter->startElement('v:shape');
        $xmlWriter->writeAttribute('type', '#_x0000_t75');
        $xmlWriter->writeAttribute('stroked', 'f');

        $styleWriter->write();

        $xmlWriter->startElement('v:imagedata');
        $xmlWriter->writeAttribute('r:id', 'rId' . $rId);
        $xmlWriter->writeAttribute('o:title', '');
        $xmlWriter->endElement(); // v:imagedata
        $xmlWriter->endElement(); // v:shape
        $xmlWriter->endElement(); // w:pict
        $xmlWriter->endElement(); // w:r
        if (!$this->withoutP) {
            $xmlWriter->endElement(); // w:p
        }
    }

    private function writeSvgDrawing(XMLWriter $xmlWriter, ImageElement $element): void
    {
        $rId = $element->getRelationId() + ($element->isInSection() ? 6 : 0);

        $style = $element->getStyle();
        // dimensions px, fallback sur getSvgDimensions()
        $pxW = $style->getWidth() ?: 0;
        $pxH = $style->getHeight() ?: 0;
        if ($pxW <= 0 || $pxH <= 0) {
            [$pxW, $pxH] = $element->getSvgDimensions($element->getSource());
        }
        $cx = \PhpOffice\PhpWord\Shared\Drawing::pixelsToEmu($pxW);
        $cy = \PhpOffice\PhpWord\Shared\Drawing::pixelsToEmu($pxH);

        // <w:p> + align
        if (!$this->withoutP) {
            $xmlWriter->startElement('w:p');
            (new ImageStyleWriter($xmlWriter, $style))->writeAlignment();
        }
        // <w:r>
        $xmlWriter->startElement('w:r');
        // <w:drawing>
        $xmlWriter->startElement('w:drawing');

        // <wp:inline> avec déclarations xmlns comme python-docx-oss
        $xmlWriter->startElement('wp:inline');
        $xmlWriter->writeAttribute('xmlns:a', 'http://schemas.openxmlformats.org/drawingml/2006/main');
        $xmlWriter->writeAttribute('xmlns:pic', 'http://schemas.openxmlformats.org/drawingml/2006/picture');
        $xmlWriter->writeAttribute('xmlns:asvg', 'http://schemas.microsoft.com/office/drawing/2016/SVG/main');

        // <wp:extent>
        $xmlWriter->startElement('wp:extent');
        $xmlWriter->writeAttribute('cx', (string) $cx);
        $xmlWriter->writeAttribute('cy', (string) $cy);
        $xmlWriter->endElement();

        // <wp:docPr>
        $xmlWriter->startElement('wp:docPr');
        $xmlWriter->writeAttribute('id', '1');
        $xmlWriter->writeAttribute('name', 'Picture 1');
        $xmlWriter->endElement();

        // <wp:cNvGraphicFramePr>
        $xmlWriter->startElement('wp:cNvGraphicFramePr');
        $xmlWriter->startElement('a:graphicFrameLocks');
        $xmlWriter->writeAttribute('noChangeAspect', '1');
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        // <a:graphic>
        $xmlWriter->startElement('a:graphic');
        // <a:graphicData uri=".../picture">
        $xmlWriter->startElement('a:graphicData');
        $xmlWriter->writeAttribute(
            'uri',
            'http://schemas.openxmlformats.org/drawingml/2006/picture'
        );

        // <pic:pic>
        $xmlWriter->startElement('pic:pic');

        // <pic:nvPicPr>
        $xmlWriter->startElement('pic:nvPicPr');
        $xmlWriter->startElement('pic:cNvPr');
        $xmlWriter->writeAttribute('id', '0');
        $xmlWriter->writeAttribute('name', basename($element->getSource()));
        $xmlWriter->endElement();
        $xmlWriter->startElement('pic:cNvPicPr');
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        // <pic:blipFill>
        $xmlWriter->startElement('pic:blipFill');
        $xmlWriter->startElement('a:blip');
        // uniquement extLst avec svgBlip
        $xmlWriter->startElement('a:extLst');
        $xmlWriter->startElement('a:ext');
        $xmlWriter->writeAttribute(
            'uri',
            '{96DAC541-7B7A-43D3-8B79-37D633B846F1}'
        );
        $xmlWriter->startElement('asvg:svgBlip');
        $xmlWriter->writeAttribute(
            'r:embed',
            'rId' . $rId
        );
        $xmlWriter->endElement(); // asvg:svgBlip
        $xmlWriter->endElement(); // a:ext
        $xmlWriter->endElement(); // a:extLst
        $xmlWriter->endElement(); // a:blip

        // <a:stretch><a:fillRect/>
        $xmlWriter->startElement('a:stretch');
        $xmlWriter->startElement('a:fillRect');
        $xmlWriter->endElement();
        $xmlWriter->endElement();

        $xmlWriter->endElement(); // pic:blipFill

        // <pic:spPr>
        $xmlWriter->startElement('pic:spPr');
        $xmlWriter->startElement('a:xfrm');
        $xmlWriter->startElement('a:off');
        $xmlWriter->writeAttribute('x', '0');
        $xmlWriter->writeAttribute('y', '0');
        $xmlWriter->endElement();
        $xmlWriter->startElement('a:ext');
        $xmlWriter->writeAttribute('cx', (string) $cx);
        $xmlWriter->writeAttribute('cy', (string) $cy);
        $xmlWriter->endElement();
        $xmlWriter->endElement();
        $xmlWriter->startElement('a:prstGeom');
        $xmlWriter->writeAttribute('prst', 'rect');
        $xmlWriter->endElement();
        $xmlWriter->endElement(); // pic:spPr

        $xmlWriter->endElement(); // pic:pic

        $xmlWriter->endElement(); // a:graphicData
        $xmlWriter->endElement(); // a:graphic

        $xmlWriter->endElement(); // wp:inline

        $xmlWriter->endElement();   // w:drawing
        $xmlWriter->endElement();   // w:r

        // </w:p>
        if (!$this->withoutP) {
            $xmlWriter->endElement();
        }
    }
}
