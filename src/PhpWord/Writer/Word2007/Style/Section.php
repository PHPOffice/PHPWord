<?php
declare(strict_types=1);
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

use PhpOffice\Common\XMLWriter;
use PhpOffice\PhpWord\Style\Section as SectionStyle;

/**
 * Section style writer
 *
 * @since 0.10.0
 */
class Section extends AbstractStyle
{
    use Border;

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
        $xmlWriter->writeAttribute('w:w', $style->getPageSizeW()->toInt('twip'));
        $xmlWriter->writeAttribute('w:h', $style->getPageSizeH()->toInt('twip'));
        $xmlWriter->endElement(); // w:pgSz

        // Vertical alignment
        $vAlign = $style->getVAlign();
        $xmlWriter->writeElementIf(!is_null($vAlign), 'w:vAlign', 'w:val', $vAlign);

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
            $xmlWriter->writeAttribute($attribute, $style->$method()->toInt('twip') ?? $default);
        }
        $xmlWriter->endElement();

        // Borders
        $this->writeBorders($xmlWriter, $style);

        // Columns
        $colsSpace = $style->getColsSpace();
        $xmlWriter->startElement('w:cols');
        $xmlWriter->writeAttribute('w:num', $style->getColsNum());
        $xmlWriter->writeAttribute('w:space', $colsSpace->toInt('twip') ?? SectionStyle::DEFAULT_COLUMN_SPACING);
        $xmlWriter->endElement();

        // Page numbering start
        $pageNum = $style->getPageNumberingStart();
        $xmlWriter->writeElementIf(!is_null($pageNum), 'w:pgNumType', 'w:start', $pageNum);

        // Line numbering
        $styleWriter = new LineNumbering($xmlWriter, $style->getLineNumbering());
        $styleWriter->write();
    }

    /**
     * Writes section borders
     * @see http://www.officeopenxml.com/WPsectionBorders.php
     */
    private function writeBorders(XMLWriter $xmlWriter, SectionStyle $style)
    {
        if (!$style->hasBorder()) {
            return;
        }

        $xmlWriter->startElement('w:pgBorders');
        // Border should be drawn on 'allPages', 'firstPage', or 'notFirstPage'
        $xmlWriter->writeAttribute('w:display', 'allPages');
        // Distance should be from 'page' edge or 'text' margin
        $xmlWriter->writeAttribute('w:offsetFrom', 'page');
        // Drawn in 'back' or 'front' of text
        $xmlWriter->writeAttribute('w:zOrder', 'back');

        foreach ($style->getBorders() as $side => $border) {
            $this->writeBorder($xmlWriter, $side, $border);
        }

        $xmlWriter->endElement();
    }
}
