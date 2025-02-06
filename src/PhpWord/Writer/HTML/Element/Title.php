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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\HTML\Element;

use PhpOffice\PhpWord\Element\Title as PhpWordTitle;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Writer\HTML;
use PhpOffice\PhpWord\Writer\HTML\Style\Font;
use PhpOffice\PhpWord\Writer\HTML\Style\Paragraph;

/**
 * TextRun element HTML writer.
 *
 * @since 0.10.0
 */
class Title extends AbstractElement
{
    /**
     * Write heading.
     *
     * @return string
     */
    public function write()
    {
        if (!$this->element instanceof PhpWordTitle) {
            return '';
        }

        $tag = 'h' . $this->element->getDepth();

        $text = $this->element->getText();
        $paragraphStyle = null;
        if (is_string($text)) {
            $text = $this->parentWriter->escapeHTML($text);
        } else {
            $paragraphStyle = $text->getParagraphStyle();
            $writer = new Container($this->parentWriter, $text);
            $text = $writer->write();
        }
        $css = '';
        $write1 = $write2 = $write3 = '';
        $style = Style::getStyle('Heading_' . $this->element->getDepth());
        if ($style !== null) {
            $styleWriter = new Font($style);
            $write1 = $styleWriter->write();
        }
        if (is_object($paragraphStyle)) {
            $styleWriter = new Paragraph($paragraphStyle);
            $write3 = $styleWriter->write();
            if ($write1 !== '' && $write3 !== '') {
                $write2 = ' ';
            }
        }
        $css = "$write1$write2$write3";
        if ($css !== '') {
            $css = " style=\"$css\"";
        }

        $content = "<{$tag}{$css}>{$text}</{$tag}>" . PHP_EOL;

        return $content;
    }
}
