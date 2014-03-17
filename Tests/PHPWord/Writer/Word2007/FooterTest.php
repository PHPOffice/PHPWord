<?php
namespace PHPWord\Tests\Writer\Word2007;

use PHPWord_Writer_Word2007_Footer;
use PHPWord_Writer_Word2007;
use PHPWord_Section_Footer;
use PHPWord\Tests\TestHelperDOCX;

/**
 * Class FooterTest
 *
 * @package             PHPWord\Tests
 * @coversDefaultClass  PHPWord_Writer_Word2007_Footer
 * @runTestsInSeparateProcesses
 */
class FooterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers  ::writeFooter
     */
    public function testWriteFooter()
    {
        $imageSrc = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'PHPWord.png')
        );
        $container = new PHPWord_Section_Footer(1);
        $container->addText('');
        $container->addPreserveText('');
        $container->addTextBreak();
        $container->createTextRun();
        $container->addTable()->addRow()->addCell()->addText('');
        $container->addImage($imageSrc);

        $writer = new PHPWord_Writer_Word2007();
        $object = new PHPWord_Writer_Word2007_Footer();
        $object->setParentWriter($writer);
        $object->writeFooter($container);
        $writer->setUseDiskCaching(true);
        $xml = simplexml_load_string($object->writeFooter($container));

        $this->assertInstanceOf('SimpleXMLElement', $xml);
    }
}
