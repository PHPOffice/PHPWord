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
        $this->xmlWriter->writeAttribute("w:val", $this->style->getStopType());
        $this->xmlWriter->writeAttribute("w:leader", $this->style->getLeader());
        $this->xmlWriter->writeAttribute('w:pos', $this->convertTwip($this->style->getPosition()));
        $this->xmlWriter->endElement();
    }
}
