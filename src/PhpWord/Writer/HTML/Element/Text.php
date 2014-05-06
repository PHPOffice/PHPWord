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

namespace PhpOffice\PhpWord\Writer\HTML\Element;

use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Writer\HTML\Style\Font as FontStyleWriter;
use PhpOffice\PhpWord\Writer\HTML\Style\Paragraph as ParagraphStyleWriter;

/**
 * Text element HTML writer
 *
 * @since 0.10.0
 */
class Text extends Element
{
    /**
     * Write text
     *
     * @return string
     */
    public function write()
    {
        if (!$this->element instanceof \PhpOffice\PhpWord\Element\Text) {
            return;
        }

        // Paragraph style
        $paragraphStyle = $this->element->getParagraphStyle();
        $pStyleIsObject = ($paragraphStyle instanceof Paragraph);
        if ($pStyleIsObject) {
            $styleWriter = new ParagraphStyleWriter($paragraphStyle);
            $paragraphStyle = $styleWriter->write();
        }
        $hasParagraphStyle = $paragraphStyle && !$this->withoutP;

        // Font style
        $fontStyle = $this->element->getFontStyle();
        $fontStyleIsObject = ($fontStyle instanceof Font);
        if ($fontStyleIsObject) {
            $styleWriter = new FontStyleWriter($fontStyle);
            $fontStyle = $styleWriter->write();
        }

        $openingTags = '';
        $endingTags = '';
        if ($hasParagraphStyle) {
            $attribute = $pStyleIsObject ? 'style' : 'class';
            $openingTags = "<p {$attribute}=\"{$paragraphStyle}\">";
            $endingTags = '</p>' . PHP_EOL;
        }
        if ($fontStyle) {
            $attribute = $fontStyleIsObject ? 'style' : 'class';
            $openingTags = $openingTags . "<span {$attribute}=\"{$fontStyle}\">";
            $endingTags = '</span>' . $endingTags;
        }

        $html = $openingTags . htmlspecialchars($this->element->getText()) . $endingTags;

        return $html;
    }
}
