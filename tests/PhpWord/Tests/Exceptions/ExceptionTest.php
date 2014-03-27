<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Exceptions;

use PhpOffice\PhpWord\Exceptions\Exception;

/**
 * Test class for PhpOffice\PhpWord\Exceptions\Exception
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Exceptions\Exception
 * @runTestsInSeparateProcesses
 */
class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \PhpOffice\PhpWord\Exceptions\Exception
     * @covers            \PhpOffice\PhpWord\Exceptions\Exception
     */
    public function testThrowException()
    {
        throw new Exception;
    }
}
