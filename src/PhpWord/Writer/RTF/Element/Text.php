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

use PhpOffice\PhpWord\Element\Text as TextElement;
use PhpOffice\PhpWord\Shared\String;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Font as FontStyle;
use PhpOffice\PhpWord\Writer\RTF\Style\Font as FontStyleWriter;
use PhpOffice\PhpWord\Writer\RTF\Style\Paragraph as ParagraphStyleWriter;

/**
 * Text element RTF writer
 *
 * @since 0.10.0
 */
class Text extends AbstractElement
{
    /**
     * Write element
     */
    public function write()
    {
        if (!$this->element instanceof \PhpOffice\PhpWord\Element\Text) {
            return;
        }

        /** @var \PhpOffice\PhpWord\Style\Font $fontStyle Scrutinizer type hint */
        $fontStyle = $this->getFontStyle($this->element);
        /** @var \PhpOffice\PhpWord\Writer\RTF $parentWriter Scrutinizer type hint */
        $parentWriter = $this->parentWriter;

        $content = '';
        $content .= $this->writeParagraphStyle($this->element);
        $content .= '{';
        $content .= $this->writeFontStyle($fontStyle);
        if ($fontStyle || $parentWriter->getLastParagraphStyle() != '') {
            $content .= ' ';
        }
        $content .= String::toUnicode($this->element->getText());
        $content .= '}';

        // Remarked to test using closure {} to avoid closing tags
        // @since 0.11.0
        // $content .= $this->writeFontStyleClosing($fontStyle);

        if (!$this->withoutP) {
            $content .= '\par' . PHP_EOL;
        }

        return $content;
    }

    /**
     * Write paragraph style
     *
     * @return string
     */
    private function writeParagraphStyle(TextElement $element)
    {
        /** @var \PhpOffice\PhpWord\Writer\RTF $parentWriter Scrutinizer type hint */
        $parentWriter = $this->parentWriter;
        $content = '';

        // Get paragraph style
        $paragraphStyle = $element->getParagraphStyle();
        if (is_string($paragraphStyle)) {
            $paragraphStyle = Style::getStyle($paragraphStyle);
        }

        // Write style when applicable
        if ($paragraphStyle && !$this->withoutP) {
            if ($parentWriter->getLastParagraphStyle() != $element->getParagraphStyle()) {
                $parentWriter->setLastParagraphStyle($element->getParagraphStyle());

                $styleWriter = new ParagraphStyleWriter($paragraphStyle);
                $content = $styleWriter->write();
            } else {
                $parentWriter->setLastParagraphStyle();
            }
        } else {
            $parentWriter->setLastParagraphStyle();
        }

        return $content;
    }

    /**
     * Write font style beginning
     *
     * @param mixed $style
     * @return string
     */
    private function writeFontStyle($style)
    {
        if (!$style instanceof FontStyle) {
            return '';
        }

        /** @var \PhpOffice\PhpWord\Writer\RTF $parentWriter Scrutinizer type hint */
        $parentWriter = $this->parentWriter;

        // Create style writer and set color/name index
        $styleWriter = new FontStyleWriter($style);
        if ($style->getColor() != null) {
            $colorIndex = array_search($style->getColor(), $parentWriter->getColorTable());
            if ($colorIndex !== false) {
                $styleWriter->setColorIndex($colorIndex + 1);
            }
        }
        if ($style->getName() != null) {
            $fontIndex = array_search($style->getName(), $parentWriter->getFontTable());
            if ($fontIndex !== false) {
                $styleWriter->setNameIndex($fontIndex + 1);
            }
        }

        // Write style
        $content = $styleWriter->write();

        return $content;
    }

    /**
     * Write font style ending
     *
     * @param \PhpOffice\PhpWord\Style\Font $style
     * @return string
     */
    private function writeFontStyleClosing($style)
    {
        if (!$style instanceof FontStyle) {
            return '';
        }

        $styleWriter = new FontStyleWriter($style);
        $content = $styleWriter->writeClosing();

        return $content;
    }

    /**
     * Get font style
     *
     * @return \PhpOffice\PhpWord\Style\Font
     */
    private function getFontStyle(TextElement $element)
    {
        $fontStyle = $element->getFontStyle();
        if (is_string($fontStyle)) {
            $fontStyle = Style::getStyle($fontStyle);
        }

        return $fontStyle;
    }
}
