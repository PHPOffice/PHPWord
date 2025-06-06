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

namespace PhpOffice\PhpWordTests\Writer\Word2007;

use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Frame;
use PhpOffice\PhpWord\Writer\Word2007\Style\Line;
use PhpOffice\PhpWord\Writer\Word2007\Style\TextBox;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Style subnamespace.
 */
class StyleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test empty styles.
     */
    public function testEmptyStyles(): void
    {
        $styles = [
            'Cell', 'Font', 'Image', 'Indentation', 'LineNumbering',
            'Paragraph', 'Row', 'Section', 'Shading', 'Spacing', 'Tab', 'Table',
            'TextBox', 'Line', 'Shape', 'Frame', 'Outline', 'Fill', 'Shadow', 'Extrusion',
        ];
        foreach ($styles as $style) {
            $objectClass = 'PhpOffice\\PhpWord\\Writer\\Word2007\\Style\\' . $style;
            $xmlWriter = new XMLWriter();
            $object = new $objectClass($xmlWriter);
            $object->write();

            self::assertEquals('', $xmlWriter->getData());
        }
    }

    /**
     * Test method exceptions.
     */
    public function testMethodExceptionsFrame(): void
    {
        $xmlWriter = new XMLWriter();
        $object = new Frame($xmlWriter);
        $object->writeAlignment();

        self::assertEquals('', $xmlWriter->getData());
    }

    /**
     * Test method exceptions.
     */
    public function testMethodExceptionsLine(): void
    {
        $xmlWriter = new XMLWriter();
        $object = new Line($xmlWriter);
        $object->writeStroke();

        self::assertEquals('', $xmlWriter->getData());
    }

    /**
     * Test method exceptions.
     */
    public function testMethodExceptionsTextBox(): void
    {
        $xmlWriter = new XMLWriter();
        $object = new TextBox($xmlWriter);
        $object->writeBorder();

        self::assertEquals('', $xmlWriter->getData());
    }
}
