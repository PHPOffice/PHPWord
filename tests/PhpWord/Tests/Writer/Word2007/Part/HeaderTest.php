<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */
namespace PhpOffice\PhpWord\Tests\Writer\Word2007\Part;

use PhpOffice\PhpWord\Writer\Word2007\Part\Header;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Part\Header
 *
 * @runTestsInSeparateProcesses
 */
class HeaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Write header
     */
    public function testWriteHeader()
    {
        $imageSrc = __DIR__ . "/../../../_files/images/PhpWord.png";

        $container = new \PhpOffice\PhpWord\Element\Header(1);
        $container->addText('Test');
        $container->addPreserveText('');
        $container->addTextBreak();
        $container->addTextRun();
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
