<?php
namespace PHPWord\Tests\Writer\ODText;

use PHPWord_Writer_ODText_WriterPart;
use PHPWord_Writer_ODText;
use PHPWord\Tests\TestHelperDOCX;

/**
 * Class WriterPartTest
 *
 * @package             PHPWord\Tests
 * @coversDefaultClass  PHPWord_Writer_ODText_WriterPart
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
            'PHPWord_Writer_Word2007_WriterPart'
        );
        $object->setParentWriter(new PHPWord_Writer_ODText());
        $this->assertEquals(
            new PHPWord_Writer_ODText(),
            $object->getParentWriter()
        );
    }

    /**
     * covers   ::getParentWriter
     * @expectedException Exception
     * @expectedExceptionMessage No parent PHPWord_Writer_IWriter assigned.
     */
    public function testSetGetParentWriterNull()
    {
        $object = $this->getMockForAbstractClass(
            'PHPWord_Writer_Word2007_WriterPart'
        );
        $object->getParentWriter();
    }
}
