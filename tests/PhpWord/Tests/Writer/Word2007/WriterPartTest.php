<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */
namespace PhpOffice\PhpWord\Tests\Writer\Word2007;

use PhpOffice\PhpWord\Writer\Word2007\WriterPart;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpWord\Tests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\WriterPart
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\WriterPart
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
