<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License as published by the Free Software Foundation.
 */

namespace PhpOffice\PhpWord\Writer\ODText\Style;

use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style\Fill;
use PhpOffice\PhpWord\Style\Frame;
use PhpOffice\PhpWord\Style\Outline;

/**
 * Shared ODF graphic property helpers.
 */
class Graphic
{
    /**
     * Write the ODF geometry for a frame-like element.
     */
    public static function writeFrameProperties(XMLWriter $xmlWriter, Frame $style): void
    {
        $unit = $style->getUnit();
        $convert = $unit === Frame::UNIT_PX ? 'pixelToCm' : 'pointToCm';
        $xmlWriter->writeAttributeIf($style->getWidth() !== null, 'svg:width', Converter::$convert($style->getWidth()) . 'cm');
        $xmlWriter->writeAttributeIf($style->getHeight() !== null, 'svg:height', Converter::$convert($style->getHeight()) . 'cm');
        $xmlWriter->writeAttributeIf($style->getLeft() !== null && $style->getLeft() != 0, 'svg:x', Converter::$convert($style->getLeft()) . 'cm');
        $xmlWriter->writeAttributeIf($style->getTop() !== null && $style->getTop() != 0, 'svg:y', Converter::$convert($style->getTop()) . 'cm');
    }

    /**
     * Write standard ODF fill and stroke properties.
     */
    public static function writeFillAndStroke(XMLWriter $xmlWriter, ?Fill $fill = null, ?Outline $outline = null): void
    {
        if ($fill !== null && $fill->getColor() !== null) {
            $xmlWriter->writeAttribute('draw:fill', 'solid');
            $xmlWriter->writeAttribute('draw:fill-color', '#' . ltrim($fill->getColor(), '#'));
        } else {
            $xmlWriter->writeAttribute('draw:fill', 'none');
        }

        if ($outline !== null && $outline->getColor() !== null) {
            $xmlWriter->writeAttribute('draw:stroke', 'solid');
            $xmlWriter->writeAttribute('svg:stroke-color', '#' . ltrim($outline->getColor(), '#'));
            $xmlWriter->writeAttributeIf($outline->getWeight() !== null, 'svg:stroke-width', $outline->getWeight() . $outline->getUnit());
        } else {
            $xmlWriter->writeAttribute('draw:stroke', 'none');
        }
    }
}
