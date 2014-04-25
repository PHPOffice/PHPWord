<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Shared\String;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Text element writer
 *
 * @since 0.10.0
 */
class Text extends Element
{
    /**
     * Write text element
     */
    public function write()
    {
        $fStyle = $this->element->getFontStyle();
        $pStyle = $this->element->getParagraphStyle();
        $text = htmlspecialchars($this->element->getText());
        $text = String::controlCharacterPHP2OOXML($text);

        if (!$this->withoutP) {
            $this->xmlWriter->startElement('w:p');
            $this->parentWriter->writeInlineParagraphStyle($this->xmlWriter, $pStyle);
        }
        $this->xmlWriter->startElement('w:r');
        $this->parentWriter->writeInlineFontStyle($this->xmlWriter, $fStyle);
        $this->xmlWriter->startElement('w:t');
        $this->xmlWriter->writeAttribute('xml:space', 'preserve');
        $this->xmlWriter->writeRaw($text);
        $this->xmlWriter->endElement();
        $this->xmlWriter->endElement(); // w:r
        if (!$this->withoutP) {
            $this->xmlWriter->endElement(); // w:p
        }
    }
}
