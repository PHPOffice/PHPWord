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

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Style subnamespace
 */
class StyleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test empty styles
     */
    public function testEmptyStyles()
    {
        $styles = array(
            'Cell', 'Font', 'Image', 'Indentation', 'LineNumbering',
            'Paragraph', 'Row', 'Section', 'Shading', 'Spacing', 'Tab', 'Table',
            'TextBox', 'Line', 'Shape', 'Frame', 'Outline', 'Fill', 'Shadow', 'Extrusion',
        );
        foreach ($styles as $style) {
            $objectClass = 'PhpOffice\\PhpWord\\Writer\\Word2007\\Style\\' . $style;
            $xmlWriter = new XMLWriter();
            $object = new $objectClass($xmlWriter);
            $object->write();

            $this->assertEquals('', $xmlWriter->getData());
        }
    }

    /**
     * Test method exceptions
     */
    public function testMethodExceptions()
    {
        $styles = array(
            'Frame'     => 'writeAlignment',
            'Line'      => 'writeStroke',
            'TextBox'   => 'writeBorder',
        );
        foreach ($styles as $style => $method) {
            $objectClass = 'PhpOffice\\PhpWord\\Writer\\Word2007\\Style\\' . $style;
            $xmlWriter = new XMLWriter();
            $object = new $objectClass($xmlWriter);
            $object->$method();

            $this->assertEquals('', $xmlWriter->getData());
        }
    }
}
