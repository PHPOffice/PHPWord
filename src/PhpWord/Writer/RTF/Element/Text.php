<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\RTF\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style;

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
            if ($fontStyle->isBold()) {
                $rtfText .= '\b';
            }
            if ($fontStyle->isItalic()) {
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

            if ($fontStyle->isBold()) {
                $rtfText .= '\b0';
            }
            if ($fontStyle->isItalic()) {
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
