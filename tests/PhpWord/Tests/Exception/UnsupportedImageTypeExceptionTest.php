<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Exception;

use PhpOffice\PhpWord\Exception\UnsupportedImageTypeException;

/**
 * Test class for PhpOffice\PhpWord\Exception\UnsupportedImageTypeExceptionTest
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Exception\UnsupportedImageTypeExceptionTest
 * @runTestsInSeparateProcesses
 */
class UnsupportedImageTypeExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Throw new exception
     *
     * @expectedException \PhpOffice\PhpWord\Exception\UnsupportedImageTypeException
     * @covers            \PhpOffice\PhpWord\Exception\UnsupportedImageTypeException
     */
    public function testThrowException()
    {
        throw new UnsupportedImageTypeException;
    }
}
