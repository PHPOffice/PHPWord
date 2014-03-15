<?php
namespace PHPWord\Tests\Exceptions;

use PhpOffice\PhpWord\Exceptions\UnsupportedImageTypeException;

class UnsupportedImageTypeExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \PhpOffice\PhpWord\Exceptions\UnsupportedImageTypeException
     * @covers \PhpOffice\PhpWord\Exceptions\UnsupportedImageTypeException
     */
    public function testThrowException()
    {
        throw new UnsupportedImageTypeException;
    }
}