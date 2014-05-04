<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Style;

use PhpOffice\PhpWord\Style\NumberingLevel;

/**
 * Test class for PhpOffice\PhpWord\Style\NumberingLevel
 *
 * @runTestsInSeparateProcesses
 */
class NumberingLevelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test setting style with normal value
     */
    public function testSetGetNormal()
    {
        $object = new NumberingLevel();

        $attributes = array(
            'level' => 1,
            'start' => 1,
            'format' => 'decimal',
            'restart' => 1,
            'suffix' => 'space',
            'text' => '%1.',
            'align' => 'left',
            'left' => 360,
            'hanging' => 360,
            'tabPos' => 360,
            'font' => 'Arial',
            'hint' => 'default',
        );
        foreach ($attributes as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            $this->assertEquals($value, $object->$get());
        }
    }
}
