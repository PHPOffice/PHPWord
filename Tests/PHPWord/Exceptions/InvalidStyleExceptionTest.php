<?php
namespace PHPWord\Tests\Exceptions;

use PhpOffice\PhpWord\Exceptions\InvalidStyleException;

class InvalidStyleExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \PhpOffice\PhpWord\Exceptions\InvalidStyleException
     * @covers \PhpOffice\PhpWord\Exceptions\InvalidStyleException
     */
    public function testThrowException()
    {
        throw new InvalidStyleException;
    }
}