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

use PhpOffice\PhpWord\Writer\HTML;

/**
 * PreserveText element HTML writer. Issue 2188.
 */
class PreserveText extends Text
{
    /**
     * Write text.
     *
     * @return string
     */
    public function write()
    {
        $this->processFontStyle();

        /** @var \PhpOffice\PhpWord\Element\PreserveText */
        $element = $this->element;
        $text = $element->getText();
        if (is_array($text)) {
            $text = implode('', $text);
        }

        $text = $this->parentWriter->escapeHTML($text ?? '');
        if (!$this->withoutP && !trim($text)) {
            $text = '&nbsp;';
        }

        $content = '';
        $content .= $this->writeOpening();
        $content .= $this->openingTags;
        $content .= $text;
        $content .= $this->closingTags;
        $content .= $this->writeClosing();

        return $content;
    }

    /**
     * Write opening.
     *
     * @return string
     */
    protected function writeOpening()
    {
        $content = '';
        if (!$this->withoutP) {
            $style = $this->getParagraphStyle();
            $content .= "<p{$style}>";
        }

        return $content;
    }

    /**
     * Write ending.
     *
     * @return string
     */
    protected function writeClosing()
    {
        $content = '';

        if (!$this->withoutP) {
            $content .= '</p>' . PHP_EOL;
        }

        return $content;
    }
}
