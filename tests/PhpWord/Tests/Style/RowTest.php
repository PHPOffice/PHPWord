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
     * Test properties with normal value
     */
    public function testProperties()
    {
        $object = new Row();

        $properties = array(
            'tblHeader' => true,
            'cantSplit' => false,
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
            $object->setStyleValue("_{$key}", $value);
            $this->assertEquals($expected, $object->$get());
        }
    }
}
