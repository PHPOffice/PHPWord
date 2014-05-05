<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Tests\Exception;

use PhpOffice\PhpWord\Exception\InvalidStyleException;

/**
 * Test class for PhpOffice\PhpWord\Exception\InvalidStyleException
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Exception\InvalidStyleException
 * @runTestsInSeparateProcesses
 */
class InvalidStyleExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Throw new exception
     *
     * @expectedException \PhpOffice\PhpWord\Exception\InvalidStyleException
     * @covers            \PhpOffice\PhpWord\Exception\InvalidStyleException
     */
    public function testThrowException()
    {
        throw new InvalidStyleException;
    }
}
