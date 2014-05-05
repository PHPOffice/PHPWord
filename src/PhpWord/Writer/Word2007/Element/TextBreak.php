<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Writer\Word2007\Style\Font as FontStyleWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Paragraph as ParagraphStyleWriter;

/**
 * TextBreak element writer
 *
 * @since 0.10.0
 */
class TextBreak extends Element
{
    /**
     * Write text break element
     */
    public function write()
    {
        if (!$this->withoutP) {
            $hasStyle = false;
            $fontStyle = null;
            $paragraphStyle = null;
            if (!is_null($this->element)) {
                $fontStyle = $this->element->getFontStyle();
                $paragraphStyle = $this->element->getParagraphStyle();
                $hasStyle = !is_null($fontStyle) || !is_null($paragraphStyle);
            }
            if ($hasStyle) {
                $styleWriter = new ParagraphStyleWriter($this->xmlWriter, $paragraphStyle);
                $styleWriter->setIsInline(true);

                $this->xmlWriter->startElement('w:p');
                $styleWriter->write();
                if (!is_null($fontStyle)) {
                    $styleWriter = new FontStyleWriter($this->xmlWriter, $fontStyle);
                    $styleWriter->setIsInline(true);

                    $this->xmlWriter->startElement('w:pPr');
                    $styleWriter->write();
                    $this->xmlWriter->endElement(); // w:pPr
                }
                $this->xmlWriter->endElement(); // w:p
            } else {
                $this->xmlWriter->writeElement('w:p');
            }
        } else {
            $this->xmlWriter->writeElement('w:br');
        }
    }
}
