<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Exceptions;

use PhpOffice\PhpWord\Exceptions\InvalidImageException;

/**
 * Test class for PhpOffice\PhpWord\Exceptions\InvalidImageException
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Exceptions\InvalidImageException
 * @runTestsInSeparateProcesses
 */
class InvalidImageExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Throw new exception
     *
     * @expectedException \PhpOffice\PhpWord\Exceptions\InvalidImageException
     * @covers            \PhpOffice\PhpWord\Exceptions\InvalidImageException
     */
    public function testThrowException()
    {
        throw new InvalidImageException;
    }
}
