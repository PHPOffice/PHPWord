<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Writer\Word2007\Style\Paragraph as ParagraphStyleWriter;

/**
 * TextRun element writer
 *
 * @since 0.10.0
 */
class TextRun extends Element
{
    /**
     * Write textrun element
     */
    public function write()
    {
        $paragraphStyle = $this->element->getParagraphStyle();
        $styleWriter = new ParagraphStyleWriter($this->xmlWriter, $paragraphStyle);
        $styleWriter->setIsInline(true);

        $this->xmlWriter->startElement('w:p');
        $styleWriter->write();
        $this->parentWriter->writeContainerElements($this->xmlWriter, $this->element);
        $this->xmlWriter->endElement(); // w:p
    }
}
