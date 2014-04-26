<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
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
            $fStyle = null;
            $pStyle = null;
            if (!is_null($this->element)) {
                $fStyle = $this->element->getFontStyle();
                $pStyle = $this->element->getParagraphStyle();
                $hasStyle = !is_null($fStyle) || !is_null($pStyle);
            }
            if ($hasStyle) {
                $styleWriter = new ParagraphStyleWriter($this->xmlWriter, $pStyle);
                $styleWriter->setIsInline(true);

                $this->xmlWriter->startElement('w:p');
                $styleWriter->write();
                if (!is_null($fStyle)) {
                    $styleWriter = new FontStyleWriter($this->xmlWriter, $fStyle);
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
