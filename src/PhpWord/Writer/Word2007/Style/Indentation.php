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
 * Paragraph indentation style writer
 *
 * @since 0.10.0
 */
class Indentation extends AbstractStyle
{
    /**
     * Write style
     */
    public function write()
    {
        if (!($this->style instanceof \PhpOffice\PhpWord\Style\Indentation)) {
            return;
        }

        $this->xmlWriter->startElement('w:ind');
        $this->xmlWriter->writeAttribute('w:left', $this->convertTwip($this->style->getLeft()));
        $this->xmlWriter->writeAttribute('w:right', $this->convertTwip($this->style->getRight()));
        if (!is_null($this->style->getFirstLine())) {
            $this->xmlWriter->writeAttribute('w:firstLine', $this->convertTwip($this->style->getFirstLine()));
        }
        if (!is_null($this->style->getHanging())) {
            $this->xmlWriter->writeAttribute('w:hanging', $this->convertTwip($this->style->getHanging()));
        }
        $this->xmlWriter->endElement();
    }
}
