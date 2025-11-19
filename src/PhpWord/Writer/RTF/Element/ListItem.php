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
        if ($style instanceof \PhpOffice\PhpWord\Style\ListItem) {
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
        $content .= $this->writeFontStyle(); // Doesn't work. Don't know why. Probably something to do with \PhpOffice\PhpWord\Element\ListItem storing styles in a textObject type \PhpOffice\PhpWord\Element\Text rather than within the Element itself
        $content .= PHP_EOL;
        /* $content .= '{\listtext\f2 \\\'b7\tab }'; // Not sure if needed for listItemRun
        $content .= PHP_EOL; */
        $content .= '{';
        $content .= $this->writeText($element->getText());
        $content .= '}';
        $content .= PHP_EOL;
        $content .= $this->writeClosing();

        return $content;
    }
}
