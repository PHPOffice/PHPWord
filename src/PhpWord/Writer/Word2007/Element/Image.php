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
    private const EMU_PER_UNIT = [FrameStyle::UNIT_PT => 12700, FrameStyle::UNIT_PX => 9525];

    /** wp:positionH/@relativeFrom of Style\Frame::getHPosRelTo() */
    private const H_RELATIVE_FROM = [
        FrameStyle::POS_RELTO_MARGIN => 'margin',
        FrameStyle::POS_RELTO_PAGE => 'page',
        FrameStyle::POS_RELTO_COLUMN => 'column',
        FrameStyle::POS_RELTO_CHAR => 'character',
        FrameStyle::POS_RELTO_LMARGIN => 'leftMargin',
        FrameStyle::POS_RELTO_RMARGIN => 'rightMargin',
        FrameStyle::POS_RELTO_IMARGIN => 'insideMargin',
        FrameStyle::POS_RELTO_OMARGIN => 'outsideMargin',
    ];

    /** wp:positionV/@relativeFrom of Style\Frame::getVPosRelTo() */
    private const V_RELATIVE_FROM = [
        FrameStyle::POS_RELTO_MARGIN => 'margin',
        FrameStyle::POS_RELTO_PAGE => 'page',
        FrameStyle::POS_RELTO_TEXT => 'paragraph',
        FrameStyle::POS_RELTO_LINE => 'line',
        FrameStyle::POS_RELTO_TMARGIN => 'topMargin',
        FrameStyle::POS_RELTO_BMARGIN => 'bottomMargin',
        FrameStyle::POS_RELTO_IMARGIN => 'insideMargin',
        FrameStyle::POS_RELTO_OMARGIN => 'outsideMargin',
    ];

    private const WRAP = [
        FrameStyle::WRAP_SQUARE => 'wp:wrapSquare',
        FrameStyle::WRAP_TIGHT => 'wp:wrapTight',
        FrameStyle::WRAP_THROUGH => 'wp:wrapThrough',
        FrameStyle::WRAP_TOPBOTTOM => 'wp:wrapTopAndBottom',
    ];

    /** Full width and height of the picture in the fixed coordinate space of wp:wrapPolygon, which Word scales to the picture */
    private const WRAP_POLYGON_SIZE = 21600;

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
        $anchor = !in_array($style->getWrap(), [null, FrameStyle::WRAP_INLINE], true);

        if (!$this->withoutP) {
            $xmlWriter->startElement('w:p');
            $styleWriter->writeAlignment();
        }
        $this->writeCommentRangeStart();

        $xmlWriter->startElement('w:r');

        // Write position
        $position = $style->getPosition();
        if ($position && !$anchor) {
            $fontStyle = new FontStyle('text');
            $fontStyle->setPosition($position);
            $fontStyleWriter = new FontStyleWriter($xmlWriter, $fontStyle);
            $fontStyleWriter->write();
        }

        $xmlWriter->startElement('w:drawing');
        $this->writeDrawing($xmlWriter, $element, $rId, $anchor);
        $xmlWriter->endElement(); // w:drawing
        $xmlWriter->endElement(); // w:r

        $this->endElementP();
    }

    /**
     * Write the picture as DrawingML: in line with the text, or anchored when it wraps otherwise.
     */
    private function writeDrawing(XMLWriter $xmlWriter, ImageElement $element, int $rId, bool $anchor): void
    {
        $style = $element->getStyle();
        $emu = self::EMU_PER_UNIT[$style->getUnit()] ?? self::EMU_PER_UNIT[FrameStyle::UNIT_PT];
        $docPrId = $this->getNextDocPrId();
        $name = $element->getName() ?? "Picture {$docPrId}";
        $extent = [
            'cx' => (int) round((float) $style->getWidth() * $emu),
            'cy' => (int) round((float) $style->getHeight() * $emu),
        ];

        $xmlWriter->startElement($anchor ? 'wp:anchor' : 'wp:inline');
        $xmlWriter->writeAttribute('distT', (int) round((float) $style->getWrapDistanceTop() * $emu));
        $xmlWriter->writeAttribute('distB', (int) round((float) $style->getWrapDistanceBottom() * $emu));
        $xmlWriter->writeAttribute('distL', (int) round((float) $style->getWrapDistanceLeft() * $emu));
        $xmlWriter->writeAttribute('distR', (int) round((float) $style->getWrapDistanceRight() * $emu));
        if ($anchor) {
            $xmlWriter->writeAttribute('simplePos', '0');
            $xmlWriter->writeAttribute('relativeHeight', $docPrId);
            $xmlWriter->writeAttribute('behindDoc', $style->getWrap() === FrameStyle::WRAP_BEHIND ? '1' : '0');
            $xmlWriter->writeAttribute('locked', '0');
            $xmlWriter->writeAttribute('layoutInCell', '1');
            $xmlWriter->writeAttribute('allowOverlap', '1');
            $xmlWriter->writeElementBlock('wp:simplePos', ['x' => 0, 'y' => 0]);
            $this->writePosition($xmlWriter, 'wp:positionH', self::H_RELATIVE_FROM[$style->getHPosRelTo()] ?? 'column', $style->getHPos(), (float) $style->getLeft() * $emu);
            $this->writePosition($xmlWriter, 'wp:positionV', self::V_RELATIVE_FROM[$style->getVPosRelTo()] ?? 'paragraph', $style->getVPos(), (float) $style->getTop() * $emu);
        }
        $xmlWriter->writeElementBlock('wp:extent', $extent);
        if ($anchor) {
            $this->writeWrap($xmlWriter, self::WRAP[$style->getWrap()] ?? 'wp:wrapNone');
        }

        $xmlWriter->startElement('wp:docPr');
        $xmlWriter->writeAttribute('id', $docPrId);
        $xmlWriter->writeAttribute('name', $name);
        if ($element->getAltText() !== null) {
            $xmlWriter->writeAttribute('descr', $element->getAltText());
        }
        $xmlWriter->endElement(); // wp:docPr

        $xmlWriter->startElement('wp:cNvGraphicFramePr');
        $xmlWriter->startElement('a:graphicFrameLocks');
        $xmlWriter->writeAttribute('xmlns:a', 'http://schemas.openxmlformats.org/drawingml/2006/main');
        $xmlWriter->writeAttribute('noChangeAspect', '1');
        $xmlWriter->endElement(); // a:graphicFrameLocks
        $xmlWriter->endElement(); // wp:cNvGraphicFramePr

        $xmlWriter->startElement('a:graphic');
        $xmlWriter->writeAttribute('xmlns:a', 'http://schemas.openxmlformats.org/drawingml/2006/main');
        $xmlWriter->startElement('a:graphicData');
        $xmlWriter->writeAttribute('uri', 'http://schemas.openxmlformats.org/drawingml/2006/picture');
        $xmlWriter->startElement('pic:pic');
        $xmlWriter->writeAttribute('xmlns:pic', 'http://schemas.openxmlformats.org/drawingml/2006/picture');

        $xmlWriter->startElement('pic:nvPicPr');
        $xmlWriter->writeElementBlock('pic:cNvPr', ['id' => 0, 'name' => $name]);
        $xmlWriter->writeElement('pic:cNvPicPr');
        $xmlWriter->endElement(); // pic:nvPicPr

        $xmlWriter->startElement('pic:blipFill');
        $xmlWriter->writeElementBlock('a:blip', ['r:embed' => 'rId' . $rId]);
        $xmlWriter->startElement('a:stretch');
        $xmlWriter->writeElement('a:fillRect');
        $xmlWriter->endElement(); // a:stretch
        $xmlWriter->endElement(); // pic:blipFill

        $xmlWriter->startElement('pic:spPr');
        $xmlWriter->startElement('a:xfrm');
        $xmlWriter->writeElementBlock('a:off', ['x' => 0, 'y' => 0]);
        $xmlWriter->writeElementBlock('a:ext', $extent);
        $xmlWriter->endElement(); // a:xfrm
        $xmlWriter->startElement('a:prstGeom');
        $xmlWriter->writeAttribute('prst', 'rect');
        $xmlWriter->writeElement('a:avLst');
        $xmlWriter->endElement(); // a:prstGeom
        $xmlWriter->endElement(); // pic:spPr

        $xmlWriter->endElement(); // pic:pic
        $xmlWriter->endElement(); // a:graphicData
        $xmlWriter->endElement(); // a:graphic
        $xmlWriter->endElement(); // wp:anchor or wp:inline
    }

    /**
     * Write wp:positionH or wp:positionV: an alignment, or an offset in EMU.
     *
     * @param float|int $offset
     */
    private function writePosition(XMLWriter $xmlWriter, string $name, string $relativeFrom, ?string $align, $offset): void
    {
        $xmlWriter->startElement($name);
        $xmlWriter->writeAttribute('relativeFrom', $relativeFrom);
        if ($align === null || $align === FrameStyle::POS_ABSOLUTE) {
            $xmlWriter->writeElement('wp:posOffset', (string) (int) round($offset));
        } else {
            $xmlWriter->writeElement('wp:align', $align);
        }
        $xmlWriter->endElement();
    }

    /**
     * Write the wrapping of an anchored picture; tight and through wrap around its rectangle.
     */
    private function writeWrap(XMLWriter $xmlWriter, string $wrap): void
    {
        $xmlWriter->startElement($wrap);
        if ($wrap !== 'wp:wrapNone' && $wrap !== 'wp:wrapTopAndBottom') {
            $xmlWriter->writeAttribute('wrapText', 'bothSides');
        }
        if ($wrap === 'wp:wrapTight' || $wrap === 'wp:wrapThrough') {
            $xmlWriter->startElement('wp:wrapPolygon');
            $xmlWriter->writeAttribute('edited', '0');
            $xmlWriter->writeElementBlock('wp:start', ['x' => 0, 'y' => 0]);
            $size = self::WRAP_POLYGON_SIZE;
            foreach ([[0, $size], [$size, $size], [$size, 0], [0, 0]] as [$x, $y]) {
                $xmlWriter->writeElementBlock('wp:lineTo', ['x' => $x, 'y' => $y]);
            }
            $xmlWriter->endElement(); // wp:wrapPolygon
        }
        $xmlWriter->endElement();
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
}
