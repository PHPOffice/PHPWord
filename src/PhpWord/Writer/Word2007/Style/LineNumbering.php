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
 * Line numbering style writer
 *
 * @since 0.10.0
 */
class LineNumbering extends AbstractStyle
{
    /**
     * Write style
     *
     * The w:start seems to be zero based so we have to decrement by one
     */
    public function write()
    {
        if (!($this->style instanceof \PhpOffice\PhpWord\Style\LineNumbering)) {
            return;
        }

        $this->xmlWriter->startElement('w:lnNumType');
        $this->xmlWriter->writeAttribute('w:start', $this->style->getStart() - 1);
        $this->xmlWriter->writeAttribute('w:countBy', $this->style->getIncrement());
        $this->xmlWriter->writeAttribute('w:distance', $this->style->getDistance());
        $this->xmlWriter->writeAttribute('w:restart', $this->style->getRestart());
        $this->xmlWriter->endElement();
    }
}
