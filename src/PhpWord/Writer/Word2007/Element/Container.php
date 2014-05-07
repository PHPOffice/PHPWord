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

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\Element\TextBreak as TextBreakElement;

/**
 * Container element writer (section, textrun, header, footnote, cell, etc.)
 *
 * @since 0.11.0
 */
class Container extends AbstractElement
{
    /**
     * Write element
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();

        // Loop through subelements
        $containerClass = basename(get_class($element));
        $subelements = $element->getElements();
        $withoutP = in_array($containerClass, array('TextRun', 'Footnote', 'Endnote')) ? true : false;
        if (count($subelements) > 0) {
            foreach ($subelements as $subelement) {
                $writerClass = dirname(get_class($this)) . '\\' . basename(get_class($subelement));
                if (class_exists($writerClass)) {
                    $writer = new $writerClass($xmlWriter, $subelement, $withoutP);
                    $writer->write();
                }
            }
        } else {
            // Special case for Cell: They have to contain a TextBreak at least
            if ($containerClass == 'Cell') {
                $writerClass = dirname(get_class($this)) . '\\TextBreak';
                $writer = new $writerClass($xmlWriter, new TextBreakElement(), $withoutP);
                $writer->write();
            }
        }
    }
}
