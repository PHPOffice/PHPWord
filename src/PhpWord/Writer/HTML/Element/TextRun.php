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

use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Writer\HTML\Style\Paragraph as ParagraphStyleWriter;

/**
 * TextRun element HTML writer
 *
 * @since 0.10.0
 */
class TextRun extends Element
{
    /**
     * Write text run
     *
     * @return string
     */
    public function write()
    {
        if (!$this->element instanceof \PhpOffice\PhpWord\Element\TextRun) {
            return;
        }

        $html = '';
        $elements = $this->element->getElements();
        if (count($elements) > 0) {
            // Paragraph style
            $paragraphStyle = $this->element->getParagraphStyle();
            $pStyleIsObject = ($paragraphStyle instanceof Paragraph);
            if ($pStyleIsObject) {
                $styleWriter = new ParagraphStyleWriter($paragraphStyle);
                $paragraphStyle = $styleWriter->write();
            }
            $tag = $this->withoutP ? 'span' : 'p';
            $attribute = $pStyleIsObject ? 'style' : 'class';
            $html .= "<{$tag} {$attribute}=\"{$paragraphStyle}\">";
            foreach ($elements as $element) {
                $elementWriter = new Element($this->parentWriter, $element, true);
                $html .= $elementWriter->write();
            }
            $html .= "</{$tag}>";
            $html .= PHP_EOL;
        }

        return $html;
    }
}
