<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

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

        $bgColor = $this->style->getBgColor();
        $valign = $this->style->getVAlign();
        $textDir = $this->style->getTextDirection();
        $brdSz = $this->style->getBorderSize();
        $brdCol = $this->style->getBorderColor();
        $hasBorders = false;
        for ($i = 0; $i < 4; $i++) {
            if (!is_null($brdSz[$i])) {
                $hasBorders = true;
                break;
            }
        }

        $styles = (!is_null($bgColor) || !is_null($valign) || !is_null($textDir) || $hasBorders) ? true : false;

        if ($styles) {
            if (!is_null($textDir)) {
                $this->xmlWriter->startElement('w:textDirection');
                $this->xmlWriter->writeAttribute('w:val', $textDir);
                $this->xmlWriter->endElement();
            }

            if (!is_null($bgColor)) {
                $this->xmlWriter->startElement('w:shd');
                $this->xmlWriter->writeAttribute('w:val', 'clear');
                $this->xmlWriter->writeAttribute('w:color', 'auto');
                $this->xmlWriter->writeAttribute('w:fill', $bgColor);
                $this->xmlWriter->endElement();
            }

            if (!is_null($valign)) {
                $this->xmlWriter->startElement('w:vAlign');
                $this->xmlWriter->writeAttribute('w:val', $valign);
                $this->xmlWriter->endElement();
            }

            if ($hasBorders) {
                $defaultColor = $this->style->getDefaultBorderColor();
                $mbWriter = new MarginBorder($this->xmlWriter);
                $mbWriter->setSizes($brdSz);
                $mbWriter->setColors($brdCol);
                $mbWriter->setAttributes(array('defaultColor' => $defaultColor));

                $this->xmlWriter->startElement('w:tcBorders');
                $mbWriter->write();
                $this->xmlWriter->endElement();
            }
        }
        $gridSpan = $this->style->getGridSpan();
        if (!is_null($gridSpan)) {
            $this->xmlWriter->startElement('w:gridSpan');
            $this->xmlWriter->writeAttribute('w:val', $gridSpan);
            $this->xmlWriter->endElement();
        }

        $vMerge = $this->style->getVMerge();
        if (!is_null($vMerge)) {
            $this->xmlWriter->startElement('w:vMerge');
            $this->xmlWriter->writeAttribute('w:val', $vMerge);
            $this->xmlWriter->endElement();
        }
    }
}
