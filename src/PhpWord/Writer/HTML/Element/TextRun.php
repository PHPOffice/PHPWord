<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\HTML\Element;

use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Writer\HTML\Style\Style as StyleWriter;

/**
 * TextRun element HTML writer
 *
 * @since 0.10.0
 */
class TextRun extends Element
{
    /**
     * Write text run
     *
     * @return string
     */
    public function write()
    {
        $html = '';
        $elements = $this->element->getElements();
        if (count($elements) > 0) {
            // Paragraph style
            $pStyle = $this->element->getParagraphStyle();
            $pStyleIsObject = ($pStyle instanceof Paragraph);
            if ($pStyleIsObject) {
                $styleWriter = new StyleWriter($this->parentWriter, $pStyle);
                $pStyle = $styleWriter->write();
            }
            $tag = $this->withoutP ? 'span' : 'p';
            $attribute = $pStyleIsObject ? 'style' : 'class';
            $html .= "<{$tag} {$attribute}=\"{$pStyle}\">";
            foreach ($elements as $element) {
                $elementWriter = new Element($this->parentWriter, $element, true);
                $html .= $elementWriter->write();
            }
            $html .= "</{$tag}>";
            $html .= PHP_EOL;
        }

        return $html;
    }
}
