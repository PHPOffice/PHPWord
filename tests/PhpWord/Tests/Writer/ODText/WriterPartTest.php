<?php
namespace PhpOffice\PhpWord\Tests\Writer\ODText;

use PhpOffice\PhpWord\Writer\ODText;
use PhpWord\Tests\TestHelperDOCX;

/**
 * Class WriterPartTest
 *
 * @package             PhpWord\Tests
 * @coversDefaultClass  PhpWord_Writer_ODText_WriterPart
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
            'PhpOffice\\PhpWord\\Writer\\ODText\\WriterPart'
        );
        $object->setParentWriter(new ODText());
        $this->assertEquals(
            new ODText(),
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
            'PhpOffice\\PhpWord\\Writer\\ODText\\WriterPart'
        );
        $object->getParentWriter();
    }
}
