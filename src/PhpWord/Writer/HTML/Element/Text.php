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
        $html = '';
        // Paragraph style
        $paragraphStyle = $this->element->getParagraphStyle();
        $pStyleIsObject = ($paragraphStyle instanceof Paragraph);
        if ($pStyleIsObject) {
            $styleWriter = new ParagraphStyleWriter($paragraphStyle);
            $paragraphStyle = $styleWriter->write();
        }
        // Font style
        $fontStyle = $this->element->getFontStyle();
        $fontStyleIsObject = ($fontStyle instanceof Font);
        if ($fontStyleIsObject) {
            $styleWriter = new FontStyleWriter($fontStyle);
            $fontStyle = $styleWriter->write();
        }

        if ($paragraphStyle && !$this->withoutP) {
            $attribute = $pStyleIsObject ? 'style' : 'class';
            $html .= "<p {$attribute}=\"{$paragraphStyle}\">";
        }
        if ($fontStyle) {
            $attribute = $fontStyleIsObject ? 'style' : 'class';
            $html .= "<span {$attribute}=\"{$fontStyle}\">";
        }
        $html .= htmlspecialchars($this->element->getText());
        if ($fontStyle) {
            $html .= '</span>';
        }
        if ($paragraphStyle && !$this->withoutP) {
            $html .= '</p>' . PHP_EOL;
        }

        return $html;
    }
}
