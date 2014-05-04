<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\ODText\Element;

/**
 * TextBreak element writer
 *
 * @since 0.10.0
 */
class TextBreak extends Element
{
    /**
     * Write element
     */
    public function write()
    {
        $this->xmlWriter->startElement('text:p');
        $this->xmlWriter->writeAttribute('text:style-name', 'Standard');
        $this->xmlWriter->endElement();
    }
}
