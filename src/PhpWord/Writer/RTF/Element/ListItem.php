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
 * ListItem element RTF writer; extends from text.
 *
 * @since 0.11.0
 */
class ListItem extends Text
{
        /** @var \PhpOffice\PhpWord\Element\Text $element Type hint */
        $element = $this->element;
        if (!$element instanceof \PhpOffice\PhpWord\Element\ListItem) {
            return;
        }

        $this->getStyles();
        
        $depth = (int) $element->getDepth();
        $style = $element->getStyle();
        $numStyle = $style->getNumberingStyle();
        $levels = $numStyle->getLevels();
        $text = $element->getTextObject();

        // Bullet List
        $content = '';
        $content .= $this->writeOpening();
        $content .= '\ilvl' . $element->getDepth();
        $content .= '\ls' . $style->getNumId();
        $content .= '\tx' . $levels[$depth]->getTabPos();
        $hanging = $levels[$depth]->getLeft() + $levels[$depth]->getHanging();
        $left = 0 - $levels[$depth]->getHanging();
        $content .= '\fi' . $left;
        $content .= '\li' . $hanging;
        $content .= '\lin' . $hanging;
        $content .= $this->writeFontStyle(); // Doesn't work. Don't know why. Probalby something to do with \PphOffice\PhpWord\Element\ListItem storing styles in a textObject type \PphOffice\PhpWord\Element\Text rather than within the Element itself
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
