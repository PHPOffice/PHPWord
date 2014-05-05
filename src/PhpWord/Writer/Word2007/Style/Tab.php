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
 * Line numbering style writer
 *
 * @since 0.10.0
 */
class Tab extends AbstractStyle
{
    /**
     * Write style
     */
    public function write()
    {
        if (!($this->style instanceof \PhpOffice\PhpWord\Style\Tab)) {
            return;
        }

        $this->xmlWriter->startElement("w:tab");
        $this->xmlWriter->writeAttribute("w:val", $this->style->getType());
        $this->xmlWriter->writeAttribute("w:leader", $this->style->getLeader());
        $this->xmlWriter->writeAttribute('w:pos', $this->convertTwip($this->style->getPosition()));
        $this->xmlWriter->endElement();
    }
}
