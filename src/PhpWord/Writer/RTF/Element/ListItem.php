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

use PhpOffice\PhpWord\Element\ListItem as Li;
use PhpOffice\PhpWord\Style;

/**
 * ListItem element RTF writer; extends from text.
 *
 * @since 0.11.0
 */
class ListItem extends Text
{
    /**
     * Write list item element.
     */
    public function write()
    {
        $element = $this->element;

        return ($element instanceof Li) ? $this->writeElement($element) : '';
    }

    /**
     * @return string
     */
    private function writeElement(Li $element)
    {
        $this->getStyles();

        $depth = (int) $element->getDepth();
        $style = $element->getStyle();
        $text = $element->getTextObject();

        // Bullet List
        $content = '';
        $content .= $this->writeOpening();
        if ($style instanceof Style\ListItem) {
            $numStyle = $style->getNumbering();
            if ($numStyle->getType() == 'singleLevel') {
                $depth = 0;
            }
            $levels = $numStyle->getLevels();
            $content .= '\ilvl' . $element->getDepth();
            $content .= '\ls' . $style->getNumId();
            $content .= '\tx' . $levels[$depth]->getTabPos();
            $content .= '\fi' . $levels[$depth]->getHanging() * -1;
            $content .= '\li' . $levels[$depth]->getLeft();
            $content .= '\lin' . $levels[$depth]->getLeft();
        }
        $content .= $this->writeFontStyle(); // ListItem Text has its own font style applied later.
        $content .= PHP_EOL;
        /* $content .= '{\listtext\f2 \\\'b7\tab }'; // Not sure if needed for listItemRun
        $content .= PHP_EOL; */
        $content .= '{';

        $textStart = $textStyle = $textEnd = '';
        $textFontStyle = null;
        $textObject = $element->getTextObject();
        if ($textObject !== null) {
            $textFontStyle = $textObject->getFontStyle();
            if (is_string($textFontStyle)) {
                $textFontStyle = Style::getStyle($textFontStyle);
            }
        }
        if ($textFontStyle instanceof Style\Font) {
            $this->fontStyle = $textFontStyle;
            $textStyle = $this->writeFontStyle();
            if ($textStyle !== '') {
                $textStart = '{';
                $textEnd = '}';
            }
        }

        $content .= $textStart . $textStyle . $this->writeText($element->getText()) . $textEnd;

        $content .= '}';

        $content .= PHP_EOL;
        $content .= $this->writeClosing();

        return $content;
    }
}
