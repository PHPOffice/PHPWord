<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */
namespace PhpOffice\PhpWord\Tests\Writer\ODText\Part;

use PhpOffice\PhpWord\Writer\ODText;
use PhpWord\Tests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\ODText\Part\AbstractPart
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\ODText\Part\AbstractPart
 * @runTestsInSeparateProcesses
 */
class AbstractPartTest extends \PHPUnit_Framework_TestCase
{
    /**
     * covers   ::setParentWriter
     * covers   ::getParentWriter
     */
    public function testSetGetParentWriter()
    {
        $object = $this->getMockForAbstractClass(
            'PhpOffice\\PhpWord\\Writer\\ODText\\Part\\AbstractPart'
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
            'PhpOffice\\PhpWord\\Writer\\ODText\\Part\\AbstractPart'
        );
        $object->getParentWriter();
    }
}
