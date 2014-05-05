<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\RTF\Element;

use PhpOffice\PhpWord\Element\Text as TextElement;

/**
 * TextRun element RTF writer
 *
 * @since 0.10.0
 */
class TextRun extends Element
{
    /**
     * Write element
     *
     * @return string
     */
    public function write()
    {
        $rtfText = '';
        $elements = $this->element->getElements();
        if (count($elements) > 0) {
            $rtfText .= '\pard\nowidctlpar' . PHP_EOL;
            foreach ($elements as $element) {
                if ($element instanceof TextElement) {
                    $elementWriter = new Element($this->parentWriter, $element, true);
                    $rtfText .= '{';
                    $rtfText .= $elementWriter->write();
                    $rtfText .= '}' . PHP_EOL;
                }
            }
            $rtfText .= '\par' . PHP_EOL;
        }

        return $rtfText;
    }
}
