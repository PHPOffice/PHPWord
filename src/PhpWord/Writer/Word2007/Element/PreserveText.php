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

/**
 * PreserveText element writer
 *
 * @since 0.10.0
 */
class PreserveText extends Element
{
    /**
     * Write preserve text element
     */
    public function write()
    {
        $fStyle = $this->element->getFontStyle();
        $pStyle = $this->element->getParagraphStyle();
        $texts = $this->element->getText();
        if (!is_array($texts)) {
            $texts = array($texts);
        }

        $this->xmlWriter->startElement('w:p');
        $this->parentWriter->writeInlineParagraphStyle($this->xmlWriter, $pStyle);
        foreach ($texts as $text) {
            if (substr($text, 0, 1) == '{') {
                $text = substr($text, 1, -1);

                $this->xmlWriter->startElement('w:r');
                $this->xmlWriter->startElement('w:fldChar');
                $this->xmlWriter->writeAttribute('w:fldCharType', 'begin');
                $this->xmlWriter->endElement();
                $this->xmlWriter->endElement();

                $this->xmlWriter->startElement('w:r');
                $this->parentWriter->writeInlineFontStyle($this->xmlWriter, $fStyle);
                $this->xmlWriter->startElement('w:instrText');
                $this->xmlWriter->writeAttribute('xml:space', 'preserve');
                $this->xmlWriter->writeRaw($text);
                $this->xmlWriter->endElement();
                $this->xmlWriter->endElement();

                $this->xmlWriter->startElement('w:r');
                $this->xmlWriter->startElement('w:fldChar');
                $this->xmlWriter->writeAttribute('w:fldCharType', 'separate');
                $this->xmlWriter->endElement();
                $this->xmlWriter->endElement();

                $this->xmlWriter->startElement('w:r');
                $this->xmlWriter->startElement('w:fldChar');
                $this->xmlWriter->writeAttribute('w:fldCharType', 'end');
                $this->xmlWriter->endElement();
                $this->xmlWriter->endElement();
            } else {
                $text = htmlspecialchars($text);
                $text = String::controlCharacterPHP2OOXML($text);

                $this->xmlWriter->startElement('w:r');
                $this->parentWriter->writeInlineFontStyle($this->xmlWriter, $fStyle);
                $this->xmlWriter->startElement('w:t');
                $this->xmlWriter->writeAttribute('xml:space', 'preserve');
                $this->xmlWriter->writeRaw($text);
                $this->xmlWriter->endElement();
                $this->xmlWriter->endElement();
            }
        }

        $this->xmlWriter->endElement(); // p
    }
}
