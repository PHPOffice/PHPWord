<?php

namespace PhpOffice\PhpWord\Writer\ODText\Style;

use PhpOffice\PhpWord\Shared\Converter;

/**
 * Table row style writer.
 */
class Row extends AbstractStyle
{
    public function write(): void
    {
        $style = $this->getStyle();
        if ($style instanceof \PhpOffice\PhpWord\Element\Row) {
            $height = $style->getHeight();
            $style = $style->getStyle();
        } else {
            $height = null;
        }
        if (!$style instanceof \PhpOffice\PhpWord\Style\Row) {
            return;
        }

        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('style:style');
        $xmlWriter->writeAttribute('style:name', $style->getStyleName());
        $xmlWriter->writeAttribute('style:family', 'table-row');
        $xmlWriter->startElement('style:table-row-properties');

        if ($height !== null) {
            $attribute = $style->isExactHeight() ? 'style:row-height' : 'style:min-row-height';
            $xmlWriter->writeAttribute($attribute, $this->convertHeight($height));
            if ($style->isExactHeight()) {
                $xmlWriter->writeAttribute('style:use-optimal-row-height', 'false');
            }
        }

        $xmlWriter->endElement(); // style:table-row-properties
        $xmlWriter->endElement(); // style:style
    }

    private function convertHeight(int $height): string
    {
        $inches = (string) ($height / Converter::INCH_TO_TWIP) . 'in';
        $centimeters = (string) ($height * Converter::INCH_TO_CM / Converter::INCH_TO_TWIP) . 'cm';

        return strlen($inches) < strlen($centimeters) ? $inches : $centimeters;
    }
}
