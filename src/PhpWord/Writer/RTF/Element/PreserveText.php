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

namespace PhpOffice\PhpWord\Writer\RTF\Element;

/**
 * Text element RTF writer.
 *
 * @since 0.10.0
 */
class PreserveText extends AbstractElement
{
    /**
     * Write element.
     *
     * @return string
     */
    public function write()
    {
        /** @var \PhpOffice\PhpWord\Element\PreserveText $element Type hint */
        $element = $this->element;
        if (!$element instanceof \PhpOffice\PhpWord\Element\PreserveText) {
            return '';
        }

        $this->getStyles();

        $content = '';
        $content .= $this->writeOpening();
        $content .= '{';
        $content .= $this->writeFontStyle();
        foreach($element->getText() as $text) {
            if (preg_match('/[{}]/', $text) == 1) {
                $text = str_replace(array('{', '}'), '', $text);
                $content .= '{\field {\*\fldinst {' . $text . '}}{\\fldrslt {}}}';
            } else {
                $content .= $this->writeText($text);
            }
        }
        $content .= '}';
        $content .= $this->writeClosing();

        return $content;
    }
}
