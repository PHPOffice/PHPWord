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
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Colors\ThemeColor
 */
class ThemeColorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Provided color must be a valid theme color. 'FakeColor' provided. Allowed:
     */
    public function testConversions()
    {
        // Prepare test values [ original, expected ]
        $values = array(
            'dk1',
            'lt1',
            'dk2',
            'lt2',
            'accent1',
            'accent2',
            'accent3',
            'accent4',
            'accent5',
            'accent6',
            'hlink',
            'folHlink',

            'FakeColor',
        );
        // Conduct test
        foreach ($values as $value) {
            $message = $value . ' should be a valid color';
            $result = new ThemeColor($value);
            $this->assertInstanceOf(BasicColor::class, $result);
            $this->assertInstanceOf(NamedColorInterface::class, $result);
            $this->assertEquals($value, $result->getName(), $message);
            $this->assertEquals($value, $result->toHexOrName(), $message);
            $this->assertTrue($result->isSpecified());
        }
    }
}
