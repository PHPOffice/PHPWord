<?php
namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

/**
 * @coversDefaultClass         \PhpOffice\PhpWord\IOFactory
 * @runTestsInSeparateProcesses
 */
final class IOFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::createWriter
     */
    final public function testExistingWriterCanBeCreated()
    {
        $this->assertInstanceOf(
            'PhpOffice\\PhpWord\\Writer\\Word2007',
            IOFactory::createWriter(new PhpWord(), 'Word2007')
        );
    }

    /**
     * @covers                   ::createWriter
     * @expectedException        \PhpOffice\PhpWord\Exceptions\Exception
     */
    final public function testNonexistentWriterCanNotBeCreated()
    {
        IOFactory::createWriter(new PhpWord(), 'Word2006');
    }

    /**
     * @covers ::createReader
     */
    final public function testExistingReaderCanBeCreated()
    {
        $this->assertInstanceOf(
            'PhpOffice\\PhpWord\\Reader\\Word2007',
            IOFactory::createReader('Word2007')
        );
    }

    /**
     * @covers                   ::createReader
     * @expectedException        \PhpOffice\PhpWord\Exceptions\Exception
     */
    final public function testNonexistentReaderCanNotBeCreated()
    {
        IOFactory::createReader('Word2006');
    }
}
