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

        $styleFont = $this->element->getFontStyle();
        if (is_string($styleFont)) {
            $styleFont = Style::getStyle($styleFont);
        }

        $styleParagraph = $this->element->getParagraphStyle();
        if (is_string($styleParagraph)) {
            $styleParagraph = Style::getStyle($styleParagraph);
        }

        if ($styleParagraph && !$this->withoutP) {
            if ($this->parentWriter->getLastParagraphStyle() != $this->element->getParagraphStyle()) {
                $rtfText .= '\pard\nowidctlpar';
                if ($styleParagraph->getSpaceAfter() != null) {
                    $rtfText .= '\sa' . $styleParagraph->getSpaceAfter();
                }
                if ($styleParagraph->getAlign() != null) {
                    if ($styleParagraph->getAlign() == 'center') {
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

        if ($styleFont instanceof Font) {
            if ($styleFont->getColor() != null) {
                $idxColor = array_search($styleFont->getColor(), $this->parentWriter->getColorTable());
                if ($idxColor !== false) {
                    $rtfText .= '\cf' . ($idxColor + 1);
                }
            } else {
                $rtfText .= '\cf0';
            }
            if ($styleFont->getName() != null) {
                $idxFont = array_search($styleFont->getName(), $this->parentWriter->getFontTable());
                if ($idxFont !== false) {
                    $rtfText .= '\f' . $idxFont;
                }
            } else {
                $rtfText .= '\f0';
            }
            if ($styleFont->getBold()) {
                $rtfText .= '\b';
            }
            if ($styleFont->getItalic()) {
                $rtfText .= '\i';
            }
            if ($styleFont->getSize()) {
                $rtfText .= '\fs' . ($styleFont->getSize() * 2);
            }
        }
        if ($this->parentWriter->getLastParagraphStyle() != '' || $styleFont) {
            $rtfText .= ' ';
        }
        $rtfText .= $this->element->getText();

        if ($styleFont instanceof Font) {
            $rtfText .= '\cf0';
            $rtfText .= '\f0';

            if ($styleFont->getBold()) {
                $rtfText .= '\b0';
            }
            if ($styleFont->getItalic()) {
                $rtfText .= '\i0';
            }
            if ($styleFont->getSize()) {
                $rtfText .= '\fs' . (PhpWord::DEFAULT_FONT_SIZE * 2);
            }
        }

        if (!$this->withoutP) {
            $rtfText .= '\par' . PHP_EOL;
        }
        return $rtfText;
    }
}
