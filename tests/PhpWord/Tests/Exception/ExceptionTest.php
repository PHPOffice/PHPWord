<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Exception;

use PhpOffice\PhpWord\Exception\Exception;

/**
 * Test class for PhpOffice\PhpWord\Exception\Exception
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Exception\Exception
 * @runTestsInSeparateProcesses
 */
class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Throw new exception
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @covers            \PhpOffice\PhpWord\Exception\Exception
     */
    public function testThrowException()
    {
        throw new Exception;
    }
}
