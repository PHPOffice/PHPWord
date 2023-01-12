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
 * Field element writer.
 *
 * Note: for now, only date, page and numpages fields are implemented for RTF.
 */
class Field extends Text
{
    /**
     * Write field element.
     */
    public function write()
    {
        $element = $this->element;
        if (!$element instanceof \PhpOffice\PhpWord\Element\Field) {
            return;
        }

        $this->getStyles();

        $content = '';
        $content .= $this->writeOpening();
        $content .= '{';
        $content .= $this->writeFontStyle();

        $methodName = 'write' . ucfirst(strtolower($element->getType()));
        if (!method_exists($this, $methodName)) {
            // Unsupported field
            $content .= '';
        } else {
            $content .= '\\field{\\*\\fldinst ';
            $content .= $this->$methodName($element);
            $content .= '}{\\fldrslt}';
        }
        $content .= '}';
        $content .= $this->writeClosing();

        return $content;
    }

    protected function writePage()
    {
        return 'PAGE';
    }

    protected function writeNumpages()
    {
        return 'NUMPAGES';
    }

    protected function writeDate(\PhpOffice\PhpWord\Element\Field $element)
    {
        $content = '';
        $content .= 'DATE';
        $properties = $element->getProperties();
        if (isset($properties['dateformat'])) {
            $content .= ' \\\\@ "' . $properties['dateformat'] . '"';
        }

        return $content;
    }
}
