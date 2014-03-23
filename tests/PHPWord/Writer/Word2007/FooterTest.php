<?php
namespace PhpWord\Tests\Writer\Word2007;

use PhpOffice\PhpWord\Writer\Word2007\Footer;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpWord\Tests\TestHelperDOCX;

/**
 * Class FooterTest
 *
 * @package             PhpWord\Tests
 * @coversDefaultClass  PhpOffice\PhpWord\Writer\Word2007\Footer
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
            array(\PHPWORD_TESTS_BASE_DIR, 'data', 'images', 'PhpWord.png')
        );
        $container = new \PhpOffice\PhpWord\Section\Footer(1);
        $container->addText('');
        $container->addPreserveText('');
        $container->addTextBreak();
        $container->createTextRun();
        $container->addTable()->addRow()->addCell()->addText('');
        $container->addImage($imageSrc);

        $writer = new Word2007();
        $object = new Footer();
        $object->setParentWriter($writer);
        $object->writeFooter($container);
        $writer->setUseDiskCaching(true);
        $xml = simplexml_load_string($object->writeFooter($container));

        $this->assertInstanceOf('SimpleXMLElement', $xml);
    }
}
