<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\HTML\Element;

use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Writer\HTML\Style\Style as StyleWriter;

/**
 * Text element HTML writer
 *
 * @since 0.10.0
 */
class Text extends Element
{
    /**
     * Write text
     *
     * @return string
     */
    public function write()
    {
        $html = '';
        // Paragraph style
        $pStyle = $this->element->getParagraphStyle();
        $pStyleIsObject = ($pStyle instanceof Paragraph);
        if ($pStyleIsObject) {
            $styleWriter = new StyleWriter($this->parentWriter, $pStyle);
            $pStyle = $styleWriter->write();
        }
        // Font style
        $fStyle = $this->element->getFontStyle();
        $fStyleIsObject = ($fStyle instanceof Font);
        if ($fStyleIsObject) {
            $styleWriter = new StyleWriter($this->parentWriter, $fStyle);
            $fStyle = $styleWriter->write();
        }

        if ($pStyle && !$this->withoutP) {
            $attribute = $pStyleIsObject ? 'style' : 'class';
            $html .= "<p {$attribute}=\"{$pStyle}\">";
        }
        if ($fStyle) {
            $attribute = $fStyleIsObject ? 'style' : 'class';
            $html .= "<span {$attribute}=\"{$fStyle}\">";
        }
        $html .= htmlspecialchars($this->element->getText());
        if ($fStyle) {
            $html .= '</span>';
        }
        if ($pStyle && !$this->withoutP) {
            $html .= '</p>' . PHP_EOL;
        }

        return $html;
    }
}
