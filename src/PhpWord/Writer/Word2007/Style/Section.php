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

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use PhpOffice\PhpWord\Style\Section as SectionStyle;

/**
 * Section style writer.
 *
 * @since 0.10.0
 */
class Section extends AbstractStyle
{
    /**
     * Write style.
     *
     * The children of w:sectPr are a sequence in the schema and are only
     * accepted in the order the schema names them, so the properties of the
     * style are written in the order of `EG_SectPrContents`. The part that
     * writes w:sectPr puts the footnote properties of the section before them
     * and w:titlePg after them.
     */
    public function write(): void
    {
        $style = $this->getStyle();
        if (!$style instanceof SectionStyle) {
            return;
        }
        $xmlWriter = $this->getXmlWriter();

        $breakType = $style->getBreakType();
        $xmlWriter->writeElementIf(null !== $breakType, 'w:type', 'w:val', $breakType);

        $xmlWriter->startElement('w:pgSz');
        $xmlWriter->writeAttribute('w:orient', $style->getOrientation());
        $xmlWriter->writeAttribute('w:w', $style->getPageSizeW());
        $xmlWriter->writeAttribute('w:h', $style->getPageSizeH());
        $xmlWriter->endElement(); // w:pgSz

        $margins = [
            'w:top' => ['getMarginTop', SectionStyle::DEFAULT_MARGIN],
            'w:right' => ['getMarginRight', SectionStyle::DEFAULT_MARGIN],
            'w:bottom' => ['getMarginBottom', SectionStyle::DEFAULT_MARGIN],
            'w:left' => ['getMarginLeft', SectionStyle::DEFAULT_MARGIN],
            'w:header' => ['getHeaderHeight', SectionStyle::DEFAULT_HEADER_HEIGHT],
            'w:footer' => ['getFooterHeight', SectionStyle::DEFAULT_FOOTER_HEIGHT],
            'w:gutter' => ['getGutter', SectionStyle::DEFAULT_GUTTER],
        ];
        $xmlWriter->startElement('w:pgMar');
        foreach ($margins as $attribute => $value) {
            [$method, $default] = $value;
            $xmlWriter->writeAttribute($attribute, $this->convertTwip($style->$method(), $default));
        }
        $xmlWriter->endElement();

        if ($style->hasBorder()) {
            $xmlWriter->startElement('w:pgBorders');
            $xmlWriter->writeAttribute('w:offsetFrom', 'page');

            $styleWriter = new MarginBorder($xmlWriter);
            $styleWriter->setSizes($style->getBorderSize());
            $styleWriter->setColors($style->getBorderColor());
            $styleWriter->setAttributes(['space' => '24']);
            $styleWriter->write();

            $xmlWriter->endElement();
        }

        $styleWriter = new LineNumbering($xmlWriter, $style->getLineNumbering());
        $styleWriter->write();

        $pageNum = $style->getPageNumberingStart();
        $xmlWriter->writeElementIf(null !== $pageNum, 'w:pgNumType', 'w:start', $pageNum);

        $colsSpace = $style->getColsSpace();
        $xmlWriter->startElement('w:cols');
        $xmlWriter->writeAttribute('w:num', $style->getColsNum());
        $xmlWriter->writeAttribute('w:space', $this->convertTwip($colsSpace, SectionStyle::DEFAULT_COLUMN_SPACING));
        $xmlWriter->endElement();

        $vAlign = $style->getVAlign();
        $xmlWriter->writeElementIf(null !== $vAlign, 'w:vAlign', 'w:val', $vAlign);
    }
}
