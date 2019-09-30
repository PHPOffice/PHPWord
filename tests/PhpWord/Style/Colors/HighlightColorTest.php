<?php
declare(strict_types=1);
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

namespace PhpOffice\PhpWord\Style\Colors;

/**
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Colors\HighlightColor
 */
class HighlightColorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Provided color must be a valid highlight color. 'fakeColor' provided. Allowed:
     */
    public function testConversions()
    {
        // Prepare test values [ original, expected ]
        $values = array(
            // Allowed values
            array('yellow', true, 'FF0000', array(255, 0, 0)),
            array('green', true, '00FF00', array(0, 255, 0)),
            array('cyan', true, '00FFFF', array(0, 255, 255)),
            array('magenta', true, 'FF00FF', array(255, 0, 255)),
            array('blue', true, '0000FF', array(0, 0, 255)),
            array('red', true, 'FF0000', array(255, 0, 0)),
            array('darkBlue', true, '000080', array(0, 0, 128)),
            array('darkCyan', true, '008080', array(0, 128, 128)),
            array('darkGreen', true, '008000', array(0, 128, 0)),
            array('darkMagenta', true, '800080', array(128, 0, 128)),
            array('darkRed', true, '800000', array(128, 0, 0)),
            array('darkYellow', true, '808000', array(128, 128, 0)),
            array('darkGray', true, '808080', array(128, 128, 128)),
            array('lightGray', true, 'C0C0C0', array(192, 192, 192)),
            array('black', true, '000000', array(0, 0, 0)),

            // Null
            array(null, false, null, null),

            // Invalid value
            array('fakeColor', null, null),
        );
        // Conduct test
        foreach ($values as $value) {
            $message = $value[0] . ' should be a valid foreground color';
            $result = new HighlightColor($value[0]);
            $this->assertEquals($value[2], $result->toHex(), $message);
            $this->assertEquals($value[2] === null ? null : '#' . $value[2], $result->toHex(true), $message);
            $this->assertEquals($value[0], $result->toHexOrName(), $message);
            $this->assertEquals($value[0], $result->toHexOrName(true), $message);
            $this->assertEquals($value[1], $result->isSpecified(), $message);
            $this->assertEquals($value[3], $result->toRgb(), $message);
            $this->assertEquals($value[0], $result->getName(), $message);
        }
    }
}
