<?php
namespace PHPWord\Tests\Writer;

use PHPWord_Writer_ODText;
use PHPWord;

/**
 * Class ODTextTest
 *
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class ODTextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test construct
     */
    public function testConstruct()
    {
        $object = new PHPWord_Writer_ODText(new PHPWord());

        $this->assertInstanceOf('PHPWord', $object->getPHPWord());
        $this->assertInstanceOf("PHPWord_HashTable", $object->getDrawingHashTable());

        $this->assertEquals('./', $object->getDiskCachingDirectory());
        $writerParts = array('Content', 'Manifest', 'Meta', 'Mimetype', 'Styles');
        foreach ($writerParts as $part) {
            $this->assertInstanceOf(
                "PHPWord_Writer_ODText_{$part}",
                $object->getWriterPart($part)
            );
            $this->assertInstanceOf(
                "PHPWord_Writer_ODText",
                $object->getWriterPart($part)->getParentWriter()
            );
        }
    }

    /**
     * Test construct with null value/without PHPWord
     *
     * @expectedException Exception
     * @expectedExceptionMessage No PHPWord assigned.
     */
    public function testConstructWithNull()
    {
        $object = new PHPWord_Writer_ODText();
        $object->getPHPWord();
    }

    /**
     * Test save()
     */
    public function testSave()
    {
        $phpWord = new PHPWord();
        $phpWord->addFontStyle('Font', array('size' => 11));
        $phpWord->addParagraphStyle('Paragraph', array('align' => 'center'));
        $section = $phpWord->createSection();
        $section->addText('Test 1', 'Font', 'Paragraph');
        $section->addTextBreak();
        $section->addText('Test 2');
        $section = $phpWord->createSection();
        $textrun = $section->createTextRun();
        $textrun->addText('Test 3');

        $writer = new PHPWord_Writer_ODText($phpWord);
        $file = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_DIR_ROOT, '_files', 'temp.odt')
        );
        $writer->save($file);
        $this->assertTrue(file_exists($file));
        unlink($file);
    }

    /**
     * Test disk caching parameters
     */
    public function testSetDiskCaching()
    {
        $object = new PHPWord_Writer_ODText();
        $object->setUseDiskCaching(true, PHPWORD_TESTS_DIR_ROOT);
        $this->assertTrue($object->getUseDiskCaching());
        $this->assertEquals(PHPWORD_TESTS_DIR_ROOT, $object->getDiskCachingDirectory());
    }
}
