<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\ODText\Element;

/**
 * Text element writer
 *
 * @since 0.10.0
 */
class Text extends Element
{
    /**
     * Write element
     */
    public function write()
    {
        $fontStyle = $this->element->getFontStyle();
        $paragraphStyle = $this->element->getParagraphStyle();

        // @todo Commented for TextRun. Should really checkout this value
        // $SfIsObject = ($fontStyle instanceof Font) ? true : false;
        $SfIsObject = false;

        if ($SfIsObject) {
            // Don't never be the case, because I browse all sections for cleaning all styles not declared
            throw new Exception('PhpWord : $SfIsObject wouldn\'t be an object');
        } else {
            if (!$this->withoutP) {
                $this->xmlWriter->startElement('text:p'); // text:p
            }
            if (empty($fontStyle)) {
                if (empty($paragraphStyle)) {
                    $this->xmlWriter->writeAttribute('text:style-name', 'P1');
                } elseif (is_string($paragraphStyle)) {
                    $this->xmlWriter->writeAttribute('text:style-name', $paragraphStyle);
                }
                $this->xmlWriter->writeRaw($this->element->getText());
            } else {
                if (empty($paragraphStyle)) {
                    $this->xmlWriter->writeAttribute('text:style-name', 'Standard');
                } elseif (is_string($paragraphStyle)) {
                    $this->xmlWriter->writeAttribute('text:style-name', $paragraphStyle);
                }
                // text:span
                $this->xmlWriter->startElement('text:span');
                if (is_string($fontStyle)) {
                    $this->xmlWriter->writeAttribute('text:style-name', $fontStyle);
                }
                $this->xmlWriter->writeRaw($this->element->getText());
                $this->xmlWriter->endElement();
            }
            if (!$this->withoutP) {
                $this->xmlWriter->endElement(); // text:p
            }
        }
    }
}
