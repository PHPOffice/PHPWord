<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
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
