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
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Style\Cell as CellStyle;
use PhpOffice\PhpWord\Style\Lengths\Absolute;
use PhpOffice\PhpWord\Style\Lengths\Auto;
use PhpOffice\PhpWord\Style\Lengths\Length;
use PhpOffice\PhpWord\Style\Lengths\Percent;

/**
 * Cell style writer
 *
 * @since 0.10.0
 */
class Cell extends AbstractStyle
{
    use Border;

    /**
     * @var Length Cell width
     */
    private $width;

    /**
     * Write style.
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof CellStyle) {
            return;
        }
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startElement('w:tcPr');

        // Width
        $this->writeWidth($xmlWriter, $style);

        // Text direction
        $textDir = $style->getTextDirection();
        $xmlWriter->writeElementIf(!is_null($textDir), 'w:textDirection', 'w:val', $textDir);

        // Vertical alignment
        $vAlign = $style->getVAlign();
        $xmlWriter->writeElementIf(!is_null($vAlign), 'w:vAlign', 'w:val', $vAlign);

        // Border
        $this->writeBorders($xmlWriter, $style);

        // Shading
        $shading = $style->getShading();
        if (!is_null($shading)) {
            $styleWriter = new Shading($xmlWriter, $shading);
            $styleWriter->write();
        }

        // Colspan & rowspan
        $gridSpan = $style->getGridSpan();
        $vMerge = $style->getVMerge();
        $xmlWriter->writeElementIf(!is_null($gridSpan), 'w:gridSpan', 'w:val', $gridSpan);
        $xmlWriter->writeElementIf(!is_null($vMerge), 'w:vMerge', 'w:val', $vMerge);

        $xmlWriter->endElement(); // w:tcPr
    }

    protected function writeWidth(XmlWriter $xmlWriter, CellStyle $style)
    {
        $width = is_null($this->width) ? $style->getWidth() : $this->width;

        if ($width instanceof Absolute) {
            $width = $width->toFloat('twip');
            $unit = 'dxa';
        } elseif ($width instanceof Percent) {
            $width = $width->toFloat() . '%';
            $unit = 'pct';
        } elseif ($width instanceof Auto) {
            $width = null;
            $unit = 'auto';
        } else {
            throw new Exception('Unsupported width `' . get_class($width) . '` provided');
        }

        if ($width === null && $unit !== 'auto') {
            return;
        } elseif ($width !== null && $width == 0) {
            $width = null;
            $unit = 'nil';
        }

        $xmlWriter->startElement('w:tcW');
        $xmlWriter->writeAttributeIf(null !== $width, 'w:w', $width);
        $xmlWriter->writeAttribute('w:type', $unit);
        $xmlWriter->endElement(); // w:tcW
    }

    protected function writeBorders(XmlWriter $xmlWriter, CellStyle $style)
    {
        if (!$style->hasBorder()) {
            return;
        }

        $xmlWriter->startElement('w:tcBorders');

        foreach ($style->getBorders() as $side => $border) {
            $this->writeBorder($xmlWriter, $side, $border);
        }

        $xmlWriter->endElement();
    }

    /**
     * Override width set in style.
     */
    public function setWidth(Length $value): self
    {
        $this->width = $value->isSpecified() ? $value : null;

        return $this;
    }
}
