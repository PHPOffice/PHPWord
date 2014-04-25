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
 * TextRun element writer
 *
 * @since 0.10.0
 */
class Title extends Element
{
    /**
     * Write title element
     */
    public function write()
    {
        $anchor = $this->element->getAnchor();
        $bookmarkId = $this->element->getBookmarkId();
        $style = $this->element->getStyle();
        $text = htmlspecialchars($this->element->getText());
        $text = String::controlCharacterPHP2OOXML($text);

        $this->xmlWriter->startElement('w:p');

        if (!empty($style)) {
            $this->xmlWriter->startElement('w:pPr');
            $this->xmlWriter->startElement('w:pStyle');
            $this->xmlWriter->writeAttribute('w:val', $style);
            $this->xmlWriter->endElement();
            $this->xmlWriter->endElement();
        }

        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:fldChar');
        $this->xmlWriter->writeAttribute('w:fldCharType', 'end');
        $this->xmlWriter->endElement();
        $this->xmlWriter->endElement();

        $this->xmlWriter->startElement('w:bookmarkStart');
        $this->xmlWriter->writeAttribute('w:id', $bookmarkId);
        $this->xmlWriter->writeAttribute('w:name', $anchor);
        $this->xmlWriter->endElement();

        $this->xmlWriter->startElement('w:r');
        $this->xmlWriter->startElement('w:t');
        $this->xmlWriter->writeRaw($text);
        $this->xmlWriter->endElement();
        $this->xmlWriter->endElement();

        $this->xmlWriter->startElement('w:bookmarkEnd');
        $this->xmlWriter->writeAttribute('w:id', $bookmarkId);
        $this->xmlWriter->endElement();

        $this->xmlWriter->endElement();
    }
}
