<?php
namespace PHPWord\Tests\Exceptions;

use PhpOffice\PhpWord\Exceptions\InvalidImageException;

class InvalidImageExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \PhpOffice\PhpWord\Exceptions\InvalidImageException
     * @covers \PhpOffice\PhpWord\Exceptions\InvalidImageException
     */
    public function testThrowException()
    {
        throw new InvalidImageException;
    }
}