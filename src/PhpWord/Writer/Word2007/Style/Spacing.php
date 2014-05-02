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
 * Spacing between lines and above/below paragraph style writer
 *
 * @since 0.10.0
 */
class Spacing extends AbstractStyle
{
    /**
     * Write style
     */
    public function write()
    {
        if (!($this->style instanceof \PhpOffice\PhpWord\Style\Spacing)) {
            return;
        }

        $this->xmlWriter->startElement('w:spacing');
        if (!is_null($this->style->getBefore())) {
            $this->xmlWriter->writeAttribute('w:before', $this->convertTwip($this->style->getBefore()));
        }
        if (!is_null($this->style->getAfter())) {
            $this->xmlWriter->writeAttribute('w:after', $this->convertTwip($this->style->getAfter()));
        }
        if (!is_null($this->style->getLine())) {
            $this->xmlWriter->writeAttribute('w:line', $this->style->getLine());
            $this->xmlWriter->writeAttribute('w:lineRule', $this->style->getRule());
        }
        $this->xmlWriter->endElement();
    }
}
