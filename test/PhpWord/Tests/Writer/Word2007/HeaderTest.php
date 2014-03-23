<?php
namespace PhpOffice\PhpWord\Tests\Writer\Word2007;

use PhpOffice\PhpWord\Writer\Word2007\Header;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;

/**
 * Class HeaderTest
 *
 * @package             PhpWord\Tests
 * @coversDefaultClass  PhpOffice\PhpWord\Writer\Word2007\Header
 * @runTestsInSeparateProcesses
 */
class HeaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers  ::writeHeader
     */
    public function testWriteHeader()
    {
        $imageSrc = __DIR__ . "/../../_files/images/PhpWord.png";

        $container = new \PhpOffice\PhpWord\Section\Header(1);
        $container->addText('Test');
        $container->addPreserveText('');
        $container->addTextBreak();
        $container->createTextRun();
        $container->addTable()->addRow()->addCell()->addText('');
        $container->addImage($imageSrc);
        $container->addWatermark($imageSrc);

        $writer = new Word2007();
        $object = new Header();
        $object->setParentWriter($writer);
        $object->writeHeader($container);
        $writer->setUseDiskCaching(true);
        $xml = simplexml_load_string($object->writeHeader($container));

        $this->assertInstanceOf('SimpleXMLElement', $xml);
    }
}
