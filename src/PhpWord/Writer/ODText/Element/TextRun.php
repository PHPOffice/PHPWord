<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\ODText\Element;

use PhpOffice\PhpWord\Element\Text as TextElement;

/**
 * TextRun element writer
 *
 * @since 0.10.0
 */
class TextRun extends Element
{
    /**
     * Write element
     */
    public function write()
    {
        $elements = $this->element->getElements();
        $this->xmlWriter->startElement('text:p');
        if (count($elements) > 0) {
            foreach ($elements as $element) {
                if ($element instanceof TextElement) {
                    $elementWriter = new Text($this->xmlWriter, $this->parentWriter, $element, true);
                    $elementWriter->write();
                } elseif ($element instanceof Link) {
                    $elementWriter = new Link($this->xmlWriter, $this->parentWriter, $element, true);
                    $elementWriter->write();
                }
            }
        }
        $this->xmlWriter->endElement();
    }
}
