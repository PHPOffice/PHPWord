<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\RTF\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font;

/**
 * Text element RTF writer
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
        $rtfText = '';

        $fontStyle = $this->element->getFontStyle();
        if (is_string($fontStyle)) {
            $fontStyle = Style::getStyle($fontStyle);
        }

        $paragraphStyle = $this->element->getParagraphStyle();
        if (is_string($paragraphStyle)) {
            $paragraphStyle = Style::getStyle($paragraphStyle);
        }

        if ($paragraphStyle && !$this->withoutP) {
            if ($this->parentWriter->getLastParagraphStyle() != $this->element->getParagraphStyle()) {
                $rtfText .= '\pard\nowidctlpar';
                if ($paragraphStyle->getSpaceAfter() != null) {
                    $rtfText .= '\sa' . $paragraphStyle->getSpaceAfter();
                }
                if ($paragraphStyle->getAlign() != null) {
                    if ($paragraphStyle->getAlign() == 'center') {
                        $rtfText .= '\qc';
                    }
                }
                $this->parentWriter->setLastParagraphStyle($this->element->getParagraphStyle());
            } else {
                $this->parentWriter->setLastParagraphStyle();
            }
        } else {
            $this->parentWriter->setLastParagraphStyle();
        }

        if ($fontStyle instanceof Font) {
            if ($fontStyle->getColor() != null) {
                $idxColor = array_search($fontStyle->getColor(), $this->parentWriter->getColorTable());
                if ($idxColor !== false) {
                    $rtfText .= '\cf' . ($idxColor + 1);
                }
            } else {
                $rtfText .= '\cf0';
            }
            if ($fontStyle->getName() != null) {
                $idxFont = array_search($fontStyle->getName(), $this->parentWriter->getFontTable());
                if ($idxFont !== false) {
                    $rtfText .= '\f' . $idxFont;
                }
            } else {
                $rtfText .= '\f0';
            }
            if ($fontStyle->getBold()) {
                $rtfText .= '\b';
            }
            if ($fontStyle->getItalic()) {
                $rtfText .= '\i';
            }
            if ($fontStyle->getSize()) {
                $rtfText .= '\fs' . ($fontStyle->getSize() * 2);
            }
        }
        if ($this->parentWriter->getLastParagraphStyle() != '' || $fontStyle) {
            $rtfText .= ' ';
        }
        $rtfText .= $this->element->getText();

        if ($fontStyle instanceof Font) {
            $rtfText .= '\cf0';
            $rtfText .= '\f0';

            if ($fontStyle->getBold()) {
                $rtfText .= '\b0';
            }
            if ($fontStyle->getItalic()) {
                $rtfText .= '\i0';
            }
            if ($fontStyle->getSize()) {
                $rtfText .= '\fs' . (PhpWord::DEFAULT_FONT_SIZE * 2);
            }
        }

        if (!$this->withoutP) {
            $rtfText .= '\par' . PHP_EOL;
        }
        return $rtfText;
    }
}
