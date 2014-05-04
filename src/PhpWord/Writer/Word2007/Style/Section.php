<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use PhpOffice\PhpWord\Style\Section as SectionStyle;
use PhpOffice\PhpWord\Writer\Word2007\Style\LineNumbering;
use PhpOffice\PhpWord\Writer\Word2007\Style\MarginBorder;

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
        $this->xmlWriter->startElement('w:pgMar');
        $this->xmlWriter->writeAttribute('w:top', $this->convertTwip($this->style->getMarginTop(), SectionStyle::DEFAULT_MARGIN));
        $this->xmlWriter->writeAttribute('w:right', $this->convertTwip($this->style->getMarginRight(), SectionStyle::DEFAULT_MARGIN));
        $this->xmlWriter->writeAttribute('w:bottom', $this->convertTwip($this->style->getMarginBottom(), SectionStyle::DEFAULT_MARGIN));
        $this->xmlWriter->writeAttribute('w:left', $this->convertTwip($this->style->getMarginLeft(), SectionStyle::DEFAULT_MARGIN));
        $this->xmlWriter->writeAttribute('w:header', $this->convertTwip($this->style->getHeaderHeight(), SectionStyle::DEFAULT_HEADER_HEIGHT));
        $this->xmlWriter->writeAttribute('w:footer', $this->convertTwip($this->style->getFooterHeight(), SectionStyle::DEFAULT_FOOTER_HEIGHT));
        $this->xmlWriter->writeAttribute('w:gutter', $this->convertTwip($this->style->getGutter(), SectionStyle::DEFAULT_GUTTER));
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
        $this->xmlWriter->writeAttribute('w:space', $this->convertTwip($this->style->getColsSpace(), SectionStyle::DEFAULT_COLUMN_SPACING));
        $this->xmlWriter->endElement();

        // Line numbering
        $styleWriter = new LineNumbering($this->xmlWriter, $this->style->getLineNumbering());
        $styleWriter->write();
    }
}
