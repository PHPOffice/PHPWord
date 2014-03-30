<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Exceptions;

use PhpOffice\PhpWord\Exceptions\UnsupportedImageTypeException;

/**
 * Test class for PhpOffice\PhpWord\Exceptions\UnsupportedImageTypeExceptionTest
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Exceptions\UnsupportedImageTypeExceptionTest
 * @runTestsInSeparateProcesses
 */
class UnsupportedImageTypeExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Throw new exception
     *
     * @expectedException \PhpOffice\PhpWord\Exceptions\UnsupportedImageTypeException
     * @covers            \PhpOffice\PhpWord\Exceptions\UnsupportedImageTypeException
     */
    public function testThrowException()
    {
        throw new UnsupportedImageTypeException;
    }
}
