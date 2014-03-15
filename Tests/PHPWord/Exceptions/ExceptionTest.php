<?php
namespace PHPWord\Tests\Exceptions;

use PhpOffice\PhpWord\Exceptions\Exception;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \PhpOffice\PhpWord\Exceptions\Exception
     * @covers \PhpOffice\PhpWord\Exceptions\Exception
     */
    public function testThrowException()
    {
        throw new Exception;
    }
}