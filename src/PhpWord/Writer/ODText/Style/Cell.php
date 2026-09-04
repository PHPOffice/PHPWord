<?php

namespace PhpOffice\PhpWord\Writer\ODText\Style;

use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style\Cell as CellStyle;

/**
 * Table cell style writer.
 */
class Cell extends AbstractStyle
{
    public function write(): void
    {
        $style = $this->getStyle();
        if (!$style instanceof CellStyle) {
            return;
        }

        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startElement('style:style');
        $xmlWriter->writeAttribute('style:name', $style->getStyleName());
        $xmlWriter->writeAttribute('style:family', 'table-cell');
        $xmlWriter->startElement('style:table-cell-properties');

        if ($style->getBgColor() !== null) {
            $xmlWriter->writeAttribute('fo:background-color', '#' . ltrim($style->getBgColor(), '#'));
        }

        $this->writeBorder($xmlWriter, 'top', $style->getBorderTopSize(), $style->getBorderTopColor(), $style->getBorderTopStyle());
        $this->writeBorder($xmlWriter, 'bottom', $style->getBorderBottomSize(), $style->getBorderBottomColor(), $style->getBorderBottomStyle());
        $this->writeBorder($xmlWriter, 'left', $style->getBorderLeftSize(), $style->getBorderLeftColor(), $style->getBorderLeftStyle());
        $this->writeBorder($xmlWriter, 'right', $style->getBorderRightSize(), $style->getBorderRightColor(), $style->getBorderRightStyle());

        $padding = [
            'top' => $style->getPaddingTop(),
            'bottom' => $style->getPaddingBottom(),
            'left' => $style->getPaddingLeft(),
            'right' => $style->getPaddingRight(),
        ];
        foreach ($padding as $side => $value) {
            if ($value !== null) {
                $xmlWriter->writeAttribute('fo:padding-' . $side, number_format($value / 1440, 3, '.', '') . 'in');
            }
        }

        $verticalAlign = [
            'top' => 'top',
            'center' => 'middle',
            'both' => 'automatic',
            'bottom' => 'bottom',
        ];
        if ($style->getVAlign() !== null && isset($verticalAlign[$style->getVAlign()])) {
            $xmlWriter->writeAttribute('style:vertical-align', $verticalAlign[$style->getVAlign()]);
        }

        $writingMode = [
            CellStyle::TEXT_DIR_LRTB => 'lr-tb',
            CellStyle::TEXT_DIR_TBRL => 'tb-rl',
            CellStyle::TEXT_DIR_BTLR => 'bt-lr',
        ];
        if ($style->getTextDirection() !== null && isset($writingMode[$style->getTextDirection()])) {
            $xmlWriter->writeAttribute('style:writing-mode', $writingMode[$style->getTextDirection()]);
        }

        if ($style->getNoWrap() === false) {
            $xmlWriter->writeAttribute('fo:wrap-option', 'wrap');
        }

        $xmlWriter->endElement(); // style:table-cell-properties
        $xmlWriter->endElement(); // style:style
    }

    /**
     * @param null|float|int $size
     */
    private function writeBorder(XMLWriter $xmlWriter, string $side, $size, ?string $color, ?string $style): void
    {
        if ($size === null) {
            return;
        }

        $xmlWriter->writeAttribute(
            'fo:border-' . $side,
            number_format($size / 20, 3, '.', '') . 'pt ' . $this->convertBorderStyle($style) . ' #' . ltrim($color ?: CellStyle::DEFAULT_BORDER_COLOR, '#')
        );
    }

    private function convertBorderStyle(?string $style): string
    {
        if ($style === null || $style === 'single' || $style === 'thick' || $style === 'inset' || $style === 'outset' || $style === 'wave') {
            return 'solid';
        }
        if ($style === 'double' || $style === 'triple') {
            return 'double';
        }
        if ($style === 'dotted' || strpos($style, 'dot') !== false) {
            return 'dotted';
        }
        if ($style === 'none' || $style === 'nil') {
            return 'none';
        }

        return 'dashed';
    }
}
