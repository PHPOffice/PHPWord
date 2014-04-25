<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Writer\Word2007\Style\Style as StyleWriter;

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
        $pStyle = $this->element->getParagraphStyle();
        $this->xmlWriter->startElement('w:p');
        $this->parentWriter->writeInlineParagraphStyle($this->xmlWriter, $pStyle);
        $this->parentWriter->writeContainerElements($this->xmlWriter, $this->element);
        $this->xmlWriter->endElement(); // w:p
    }
}
