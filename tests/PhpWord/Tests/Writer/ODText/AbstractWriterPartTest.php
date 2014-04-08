<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */
namespace PhpOffice\PhpWord\Tests\Writer\ODText;

use PhpOffice\PhpWord\Writer\ODText;
use PhpWord\Tests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\ODText\AbstractWriterPart
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\ODText\AbstractWriterPart
 * @runTestsInSeparateProcesses
 */
class AbstractWriterPartTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers   ::setParentWriter
     * covers   ::getParentWriter
     */
    public function testSetGetParentWriter()
    {
        $object = $this->getMockForAbstractClass(
            'PhpOffice\\PhpWord\\Writer\\ODText\\AbstractWriterPart'
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
     * @expectedExceptionMessage No parent WriterInterface assigned.
     */
    public function testSetGetParentWriterNull()
    {
        $object = $this->getMockForAbstractClass(
            'PhpOffice\\PhpWord\\Writer\\ODText\\AbstractWriterPart'
        );
        $object->getParentWriter();
    }
}
