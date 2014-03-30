<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Exceptions;

use PhpOffice\PhpWord\Exceptions\InvalidStyleException;

/**
 * Test class for PhpOffice\PhpWord\Exceptions\InvalidStyleException
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Exceptions\InvalidStyleException
 * @runTestsInSeparateProcesses
 */
class InvalidStyleExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Throw new exception
     *
     * @expectedException \PhpOffice\PhpWord\Exceptions\InvalidStyleException
     * @covers            \PhpOffice\PhpWord\Exceptions\InvalidStyleException
     */
    public function testThrowException()
    {
        throw new InvalidStyleException;
    }
}
