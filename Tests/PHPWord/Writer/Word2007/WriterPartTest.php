<?php
namespace PHPWord\Tests\Writer\Word2007;

use PHPWord_Writer_Word2007_WriterPart;
use PHPWord_Writer_Word2007;
use PHPWord\Tests\TestHelperDOCX;

/**
 * Class WriterPartTest
 *
 * @package             PHPWord\Tests
 * @coversDefaultClass  PHPWord_Writer_Word2007_WriterPart
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
        $object->setParentWriter(new PHPWord_Writer_Word2007());
        $this->assertEquals(
            new PHPWord_Writer_Word2007(),
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
