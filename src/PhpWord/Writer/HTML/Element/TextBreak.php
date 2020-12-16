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
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\HTML\Element;

use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Writer\HTML\Style\Font as FontStyleWriter;

/**
 * TextBreak element HTML writer
 *
 * @since 0.10.0
 */
class TextBreak extends AbstractElement
{
    /**
     * Write text break
     *
     * @return string
     */
    public function write()
    {
        if ($this->withoutP) {
            $content = '<br />' . PHP_EOL;
        } else {
            $style = $this->getFontStyle();
            $content = "<p{$style}>&nbsp;</p>" . PHP_EOL;
        }

        return $content;
    }

    /**
     * Get font style.
     */
    private function getFontStyle()
    {
        /** @var \PhpOffice\PhpWord\Element\Text $element Type hint */
        $element = $this->element;
        $pargraphStyle = $element->getParagraphStyle();
        if (!$pargraphStyle) {
            return '';
        }

        $style = '';
        $fontStyle = $pargraphStyle->getFontStyle();
        $fStyleIsObject = ($fontStyle instanceof Font);
        if ($fStyleIsObject) {
            $styleWriter = new FontStyleWriter($fontStyle);
            $style = $styleWriter->write();
        } elseif (is_string($fontStyle)) {
            $style = $fontStyle;
        }
        if ($style) {
            $attribute = $fStyleIsObject ? 'style' : 'class';

            return " {$attribute}=\"{$style}\"";
        }

        return '';
    }
}
