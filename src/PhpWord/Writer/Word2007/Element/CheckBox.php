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
use PhpOffice\PhpWord\Writer\Word2007\Style\Font as FontStyleWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Paragraph as ParagraphStyleWriter;

/**
 * CheckBox element writer
 *
 * @since 0.10.0
 */
class CheckBox extends Element
{
    /**
     * Write element
     */
    public function write()
    {
        $name = htmlspecialchars($this->element->getName());
        $name = String::controlCharacterPHP2OOXML($name);
        $text = htmlspecialchars($this->element->getText());
        $text = String::controlCharacterPHP2OOXML($text);
        $fontStyle = $this->element->getFontStyle();
        $paragraphStyle = $this->element->getParagraphStyle();

        if (!$this->withoutP) {
            $styleWriter = new ParagraphStyleWriter($this->xmlWriter, $paragraphStyle);
            $styleWriter->setIsInline(true);

            $this->xmlWriter->startElement('w:p');
            $styleWriter->write();
        }

        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:fldChar');
        $this->xmlWriter->writeAttribute('w:fldCharType', 'begin');
        $this->xmlWriter->startElement('w:ffData');
        $this->xmlWriter->startElement('w:name');
        $this->xmlWriter->writeAttribute('w:val', $name);
        $this->xmlWriter->endElement(); //w:name
        $this->xmlWriter->writeAttribute('w:enabled', '');
        $this->xmlWriter->startElement('w:calcOnExit');
        $this->xmlWriter->writeAttribute('w:val', '0');
        $this->xmlWriter->endElement(); //w:calcOnExit
        $this->xmlWriter->startElement('w:checkBox');
        $this->xmlWriter->writeAttribute('w:sizeAuto', '');
        $this->xmlWriter->startElement('w:default');
        $this->xmlWriter->writeAttribute('w:val', 0);
        $this->xmlWriter->endElement(); //w:default
        $this->xmlWriter->endElement(); //w:checkBox
        $this->xmlWriter->endElement(); // w:ffData
        $this->xmlWriter->endElement(); // w:fldChar
        $this->xmlWriter->endElement(); // w:r

        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:instrText');
        $this->xmlWriter->writeAttribute('xml:space', 'preserve');
        $this->xmlWriter->writeRaw(' FORMCHECKBOX ');
        $this->xmlWriter->endElement();// w:instrText
        $this->xmlWriter->endElement(); // w:r
        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:fldChar');
        $this->xmlWriter->writeAttribute('w:fldCharType', 'seperate');
        $this->xmlWriter->endElement();// w:fldChar
        $this->xmlWriter->endElement(); // w:r
        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:fldChar');
        $this->xmlWriter->writeAttribute('w:fldCharType', 'end');
        $this->xmlWriter->endElement();// w:fldChar
        $this->xmlWriter->endElement(); // w:r

        $styleWriter = new FontStyleWriter($this->xmlWriter, $fontStyle);
        $styleWriter->setIsInline(true);

        $this->xmlWriter->startElement('w:r');
        $styleWriter->write();
        $this->xmlWriter->startElement('w:t');
        $this->xmlWriter->writeAttribute('xml:space', 'preserve');
        $this->xmlWriter->writeRaw($text);
        $this->xmlWriter->endElement(); // w:t
        $this->xmlWriter->endElement(); // w:r

        if (!$this->withoutP) {
            $this->xmlWriter->endElement(); // w:p
        }
    }
}
