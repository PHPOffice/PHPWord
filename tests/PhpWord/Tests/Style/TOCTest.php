<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Style;

use PhpOffice\PhpWord\Style\TOC;

/**
 * Test class for PhpOffice\PhpWord\Style\TOC
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\TOC
 * @runTestsInSeparateProcesses
 */
class TOCTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test properties with normal value
     */
    public function testProperties()
    {
        $object = new TOC();

        $properties = array(
            'position'    => 9062,
            'leader' => \PhpOffice\PhpWord\Style\Tab::TAB_LEADER_DOT,
            'indent'    => 200,
        );
        foreach ($properties as $key => $value) {
            // set/get
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            $this->assertEquals($value, $object->$get());
        }
    }
}
