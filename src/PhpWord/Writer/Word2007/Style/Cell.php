<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use PhpOffice\PhpWord\Style\Cell as CellStyle;
use PhpOffice\PhpWord\Writer\Word2007\Style\Shading;

/**
 * Cell style writer
 *
 * @since 0.10.0
 */
class Cell extends AbstractStyle
{
    /**
     * Write style
     */
    public function write()
    {
        if (!($this->style instanceof \PhpOffice\PhpWord\Style\Cell)) {
            return;
        }

        $brdSz = $this->style->getBorderSize();
        $brdCol = $this->style->getBorderColor();
        $hasBorders = false;
        for ($i = 0; $i < 4; $i++) {
            if (!is_null($brdSz[$i])) {
                $hasBorders = true;
                break;
            }
        }

        // Border
        if ($hasBorders) {
            $mbWriter = new MarginBorder($this->xmlWriter);
            $mbWriter->setSizes($brdSz);
            $mbWriter->setColors($brdCol);
            $mbWriter->setAttributes(array('defaultColor' => CellStyle::DEFAULT_BORDER_COLOR));

            $this->xmlWriter->startElement('w:tcBorders');
            $mbWriter->write();
            $this->xmlWriter->endElement();
        }

        // Text direction
        if (!is_null($this->style->getTextDirection())) {
            $this->xmlWriter->startElement('w:textDirection');
            $this->xmlWriter->writeAttribute('w:val', $this->style->getTextDirection());
            $this->xmlWriter->endElement();
        }

        // Shading
        if (!is_null($this->style->getShading())) {
            $styleWriter = new Shading($this->xmlWriter, $this->style->getShading());
            $styleWriter->write();
        }

        // Alignment
        if (!is_null($this->style->getVAlign())) {
            $this->xmlWriter->startElement('w:vAlign');
            $this->xmlWriter->writeAttribute('w:val', $this->style->getVAlign());
            $this->xmlWriter->endElement();
        }

        // Colspan
        if (!is_null($this->style->getGridSpan())) {
            $this->xmlWriter->startElement('w:gridSpan');
            $this->xmlWriter->writeAttribute('w:val', $this->style->getGridSpan());
            $this->xmlWriter->endElement();
        }

        // Row span
        if (!is_null($this->style->getVMerge())) {
            $this->xmlWriter->startElement('w:vMerge');
            $this->xmlWriter->writeAttribute('w:val', $this->style->getVMerge());
            $this->xmlWriter->endElement();
        }
    }
}
