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
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Colors\Hex
 */
class HexTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Hex value must match `([0-9a-f]{3}){1,2}`. `0F9D` provided
     */
    public function testHexConversions()
    {
        // Prepare test values [ original, expected ]
        $values = array(
            // 6 characters
            array('FC94BD', 'FC94BD', array(252, 148, 189), '6 character uppercase hex values should be accepted'),
            array('Ef03Cb', 'EF03CB', array(239, 3, 203), '6 character lowercase hex values should be accepted'),
            array('72AeCd', '72AECD', array(114, 174, 205), '6 character mixed case hex values should be accepted'),

            // 3 characters
            array('d8e', 'DD88EE', array(221, 136, 238), '3 character uppercase hex values should be accepted and expanded to 6'),
            array('af5', 'AAFF55', array(170, 255, 85), '3 character lowercase hex values should be accepted and expanded to 6'),
            array('b3c', 'BB33CC', array(187, 51, 204), '3 character mixed case hex values should be accepted and expanded to 6'),

            array('000', '000000', array(0, 0, 0), 'Black should be valid'),
            array('fff', 'FFFFFF', array(255, 255, 255), 'White should be valid'),

            // Null
            array(null, null, null, 'NULL values should be accepted and converted to NULL'),

            // Invalid
            array('0F9D', null, null, '4 character hex values should fail'),
        );
        // Conduct test
        foreach ($values as $value) {
            $message = $value[0] . ': ' . $value[3];
            $result = new Hex($value[0]);
            $this->assertEquals($value[1], $result->toHex(), $message);
            $this->assertEquals($value[1] === null ? null : '#' . $value[1], $result->toHex(true), $message);
            $this->assertEquals($value[1], $result->toHexOrName(), $message);
            $this->assertEquals($value[1] === null ? null : '#' . $value[1], $result->toHexOrName(true), $message);
            $this->assertEquals($value[2], $result->toRgb(), $message);
        }
    }
}
