<?php
namespace PhpOffice\PhpWord\Tests\Writer\Word2007;

use PhpOffice\PhpWord\Writer\Word2007\WriterPart;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpWord\Tests\TestHelperDOCX;

/**
 * Class WriterPartTest
 *
 * @package             PhpWord\Tests
 * @coversDefaultClass  PhpWord\Writer\Word2007\WriterPart
 * @runTestsInSeparateProcesses
 */
class WriterPartTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers   ::setParentWriter
     * covers   ::getParentWriter
     */
    public function testSetGetParentWriter()
    {
        $object = $this->getMockForAbstractClass(
            'PhpOffice\\PhpWord\\Writer\\Word2007\\WriterPart'
        );
        $object->setParentWriter(new Word2007());
        $this->assertEquals(
            new Word2007(),
            $object->getParentWriter()
        );
    }

    /**
     * covers   ::getParentWriter
     * @expectedException Exception
     * @expectedExceptionMessage No parent IWriter assigned.
     */
    public function testSetGetParentWriterNull()
    {
        $object = $this->getMockForAbstractClass(
            'PhpOffice\\PhpWord\\Writer\\Word2007\\WriterPart'
        );
        $object->getParentWriter();
    }
}
