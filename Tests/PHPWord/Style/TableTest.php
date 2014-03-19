<?php
namespace PHPWord\Tests\Style;

use PHPWord_Style_Table;

/**
 * Class TableTest
 *
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class TableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test set style value
     */
    public function testSetStyleValue()
    {
        $object = new PHPWord_Style_Table();
        $parts = array('Top', 'Left', 'Right', 'Bottom');

        $value = 240; // In twips
        foreach ($parts as $part) {
            $property = "_cellMargin{$part}";
            $get = "getCellMargin{$part}";
            $object->setStyleValue($property, $value);
            $this->assertEquals($value, $object->$get());
        }
    }

    /**
     * Test cell margin
     */
    public function testCellMargin()
    {
        $object = new PHPWord_Style_Table();
        $parts = array('Top', 'Left', 'Right', 'Bottom');

        // Set cell margin and test if each part has the same margin
        // While looping, push values array to be asserted with getCellMargin
        $value = 240; // In twips
        foreach ($parts as $part) {
            $set = "setCellMargin{$part}";
            $get = "getCellMargin{$part}";
            $values[] = $value;
            $object->$set($value);
            $this->assertEquals($value, $object->$get());
        }
        $this->assertEquals($values, $object->getCellMargin());
    }
}
