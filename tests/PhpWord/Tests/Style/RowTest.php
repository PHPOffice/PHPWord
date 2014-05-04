<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Style;

use PhpOffice\PhpWord\Style\Row;

/**
 * Test class for PhpOffice\PhpWord\Style\Row
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Row
 * @runTestsInSeparateProcesses
 */
class RowTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test properties with boolean value
     */
    public function testBooleanValue()
    {
        $object = new Row();

        $properties = array(
            'tblHeader' => true,
            'cantSplit' => false,
            'exactHeight' => true,
        );
        foreach ($properties as $key => $value) {
            // set/get
            $set = "set{$key}";
            $get = "get{$key}";
            $expected = $value ? 1 : 0;
            $object->$set($value);
            $this->assertEquals($expected, $object->$get());

            // setStyleValue
            $value = !$value;
            $expected = $value ? 1 : 0;
            $object->setStyleValue("{$key}", $value);
            $this->assertEquals($expected, $object->$get());
        }
    }

    /**
     * Test properties with nonboolean values, which will return default value
     */
    public function testNonBooleanValue()
    {
        $object = new Row();

        $properties = array(
            'tblHeader' => 'a',
            'cantSplit' => 'b',
            'exactHeight' => 'c',
        );
        foreach ($properties as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            $this->assertFalse($object->$get());
        }
    }
}
