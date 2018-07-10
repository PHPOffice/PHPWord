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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use PhpOffice\PhpWord\Style\Section as SectionStyle;

/**
 * Section style writer
 *
 * @since 0.10.0
 */
class Section extends AbstractStyle
{
    /**
     * Write style.
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof SectionStyle) {
            return;
        }
        $xmlWriter = $this->getXmlWriter();

        // Break type
        $breakType = $style->getBreakType();
        $xmlWriter->writeElementIf(!is_null($breakType), 'w:type', 'w:val', $breakType);

        // Page size & orientation
        $xmlWriter->startElement('w:pgSz');
        $xmlWriter->writeAttribute('w:orient', $style->getOrientation());
        $xmlWriter->writeAttribute('w:w', $style->getPageSizeW());
        $xmlWriter->writeAttribute('w:h', $style->getPageSizeH());
        $xmlWriter->endElement(); // w:pgSz

        // Margins
        $margins = array(
            'w:top'    => array('getMarginTop', SectionStyle::DEFAULT_MARGIN),
            'w:right'  => array('getMarginRight', SectionStyle::DEFAULT_MARGIN),
            'w:bottom' => array('getMarginBottom', SectionStyle::DEFAULT_MARGIN),
            'w:left'   => array('getMarginLeft', SectionStyle::DEFAULT_MARGIN),
            'w:header' => array('getHeaderHeight', SectionStyle::DEFAULT_HEADER_HEIGHT),
            'w:footer' => array('getFooterHeight', SectionStyle::DEFAULT_FOOTER_HEIGHT),
            'w:gutter' => array('getGutter', SectionStyle::DEFAULT_GUTTER),
        );
        $xmlWriter->startElement('w:pgMar');
        foreach ($margins as $attribute => $value) {
            list($method, $default) = $value;
            $xmlWriter->writeAttribute($attribute, $this->convertTwip($style->$method(), $default));
        }
        $xmlWriter->endElement();

        // Borders
        if ($style->hasBorder()) {
            $xmlWriter->startElement('w:pgBorders');
            $xmlWriter->writeAttribute('w:offsetFrom', 'page');

            $styleWriter = new MarginBorder($xmlWriter);
            $styleWriter->setSizes($style->getBorderSize());
            $styleWriter->setColors($style->getBorderColor());
            $styleWriter->setAttributes(array('space' => '24'));
            $styleWriter->write();

            $xmlWriter->endElement();
        }

        // Columns
        $colsSpace = $style->getColsSpace();
        $xmlWriter->startElement('w:cols');
        $xmlWriter->writeAttribute('w:num', $style->getColsNum());
        $xmlWriter->writeAttribute('w:space', $this->convertTwip($colsSpace, SectionStyle::DEFAULT_COLUMN_SPACING));
        $xmlWriter->endElement();

        // Page numbering start
        $pageNum = $style->getPageNumberingStart();
        $xmlWriter->writeElementIf(!is_null($pageNum), 'w:pgNumType', 'w:start', $pageNum);

        // Line numbering
        $styleWriter = new LineNumbering($xmlWriter, $style->getLineNumbering());
        $styleWriter->write();
    }
}
