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

namespace PhpOffice\PhpWordTests\Style;

use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\NumberingLevel;

/**
 * Test class for PhpOffice\PhpWord\Style\NumberingLevel.
 *
 * @runTestsInSeparateProcesses
 */
class NumberingLevelTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test setting style with normal value.
     */
    public function testSetGetNormal(): void
    {
        $object = new NumberingLevel();

        $attributes = [
            'level' => 1,
            'start' => 1,
            'format' => 'decimal',
            'restart' => 1,
            'pStyle' => 'pStyle',
            'suffix' => 'space',
            'text' => '%1.',
            'alignment' => Jc::START,
            'left' => 360,
            'hanging' => 360,
            'tabPos' => 360,
            'font' => 'Arial',
            'hint' => 'default',
        ];
        foreach ($attributes as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            self::assertEquals($value, $object->$get());
        }
    }
}
