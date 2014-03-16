<?php
namespace PHPWord\Tests\Writer\Word2007;

use PHPWord_Writer_Word2007_Header;
use PHPWord_Writer_Word2007;
use PHPWord_Section_Header;
use PHPWord\Tests\TestHelperDOCX;

/**
 * Class HeaderTest
 *
 * @package             PHPWord\Tests
 * @coversDefaultClass  PHPWord_Writer_Word2007_Header
 * @runTestsInSeparateProcesses
 */
class HeaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers  ::writeHeader
     */
    public function testWriteHeader()
    {
        $imageSrc = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'PHPWord.png')
        );

        $container = new PHPWord_Section_Header(1);
        $container->addText('Test');
        $container->addPreserveText('');
        $container->addTextBreak();
        $container->createTextRun();
        $container->addTable()->addRow()->addCell()->addText('');
        $container->addImage($imageSrc);
        $container->addWatermark($imageSrc);

        $writer = new PHPWord_Writer_Word2007();
        $object = new PHPWord_Writer_Word2007_Header();
        $object->setParentWriter($writer);
        $object->writeHeader($container);
        $writer->setUseDiskCaching(true);
        $xml = simplexml_load_string($object->writeHeader($container));

        $this->assertInstanceOf('SimpleXMLElement', $xml);
    }
}
