<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

/**
 * Shading style writer
 *
 * @since 0.10.0
 */
class Shading extends AbstractStyle
{
    /**
     * Write style
     */
    public function write()
    {
        if (!($this->style instanceof \PhpOffice\PhpWord\Style\Shading)) {
            return;
        }

        $this->xmlWriter->startElement('w:shd');
        $this->xmlWriter->writeAttribute('w:val', $this->style->getPattern());
        $this->xmlWriter->writeAttribute('w:color', $this->style->getColor());
        $this->xmlWriter->writeAttribute('w:fill', $this->style->getFill());
        $this->xmlWriter->endElement();
    }
}
