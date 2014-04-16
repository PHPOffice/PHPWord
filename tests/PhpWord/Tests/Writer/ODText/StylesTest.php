<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */
namespace PhpOffice\PhpWord\Tests\Writer\ODText;

use PhpOffice\PhpWord\Writer\ODText\Styles;

/**
 * Test class for PhpOffice\PhpWord\Writer\ODText\Styles
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\ODText\Styles
 * @runTestsInSeparateProcesses
 */
class StylesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test construct with no PhpWord
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage No PhpWord assigned.
     */
    public function testConstructNoPhpWord()
    {
        $object = new Styles();
        $object->writeStyles();
    }
}
