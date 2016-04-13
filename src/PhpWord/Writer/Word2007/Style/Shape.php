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
 * @copyright   2010-2015 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

/**
 * Shape style writer
 *
 * @since 0.12.0
 */
class Shape extends AbstractStyle
{
    /**
     * Write style.
     *
     * @return void
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Shape) {
            return;
        }

        $xmlWriter = $this->getXmlWriter();

        $childStyles = array('Frame', 'Fill', 'Outline', 'Shadow', 'Extrusion');
        foreach ($childStyles as $childStyle) {
            $method = "get{$childStyle}";
            $this->writeChildStyle($xmlWriter, $childStyle, $style->$method());
        }
    }
}
