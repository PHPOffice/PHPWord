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

use PhpOffice\PhpWord\Style\Section as SectionStyle;

/**
 * Section style writer
 *
 * @since 0.10.0
 */
class Section extends AbstractStyle
{
    /**
     * Write style
     */
    public function write()
    {
        if (!($this->style instanceof \PhpOffice\PhpWord\Style\Section)) {
            return;
        }

        // Section break
        if (!is_null($this->style->getBreakType())) {
            $this->xmlWriter->startElement('w:type');
            $this->xmlWriter->writeAttribute('w:val', $this->style->getBreakType());
            $this->xmlWriter->endElement();
        }

        // Page size & orientation
        $this->xmlWriter->startElement('w:pgSz');
        $this->xmlWriter->writeAttribute('w:orient', $this->style->getOrientation());
        $this->xmlWriter->writeAttribute('w:w', $this->style->getPageSizeW());
        $this->xmlWriter->writeAttribute('w:h', $this->style->getPageSizeH());
        $this->xmlWriter->endElement(); // w:pgSz

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
        $this->xmlWriter->startElement('w:pgMar');
        foreach ($margins as $attribute => $value) {
            list($method, $default) = $value;
            $this->xmlWriter->writeAttribute($attribute, $this->convertTwip($this->style->$method(), $default));
        }
        $this->xmlWriter->endElement();

        // Borders
        $borders = $this->style->getBorderSize();
        $hasBorders = false;
        for ($i = 0; $i < 4; $i++) {
            if (!is_null($borders[$i])) {
                $hasBorders = true;
                break;
            }
        }
        if ($hasBorders) {
            $styleWriter = new MarginBorder($this->xmlWriter);
            $styleWriter->setSizes($borders);
            $styleWriter->setColors($this->style->getBorderColor());
            $styleWriter->setAttributes(array('space' => '24'));

            $this->xmlWriter->startElement('w:pgBorders');
            $this->xmlWriter->writeAttribute('w:offsetFrom', 'page');
            $styleWriter->write();
            $this->xmlWriter->endElement();
        }

        // Page numbering
        if (!is_null($this->style->getPageNumberingStart())) {
            $this->xmlWriter->startElement('w:pgNumType');
            $this->xmlWriter->writeAttribute('w:start', $this->style->getPageNumberingStart());
            $this->xmlWriter->endElement();
        }

        // Columns
        $this->xmlWriter->startElement('w:cols');
        $this->xmlWriter->writeAttribute('w:num', $this->style->getColsNum());
        $this->xmlWriter->writeAttribute('w:space', $this->convertTwip(
            $this->style->getColsSpace(),
            SectionStyle::DEFAULT_COLUMN_SPACING
        ));
        $this->xmlWriter->endElement();

        // Line numbering
        $styleWriter = new LineNumbering($this->xmlWriter, $this->style->getLineNumbering());
        $styleWriter->write();
    }
}
