<?php
namespace PHPWord\Tests\Writer;

use PHPWord_Writer_ODText;
use PHPWord;

/**
 * Class ODTextTest
 *
 * @package             PHPWord\Tests
 * @coversDefaultClass  PHPWord_Writer_ODText
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
     * @covers                      ::getPHPWord
     * @expectedException           Exception
     * @expectedExceptionMessage    No PHPWord assigned.
     */
    public function testConstructWithNull()
    {
        $object = new PHPWord_Writer_ODText();
        $object->getPHPWord();
    }

    /**
     * @covers  ::save
     */
    public function testSave()
    {
        $imageSrc = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'PHPWord.png')
        );
        $objectSrc = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'documents', 'sheet.xls')
        );
        $file = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'temp.odt')
        );

        $phpWord = new PHPWord();
        $phpWord->addFontStyle('Font', array('size' => 11));
        $phpWord->addParagraphStyle('Paragraph', array('align' => 'center'));
        $section = $phpWord->createSection();
        $section->addText('Test 1', 'Font');
        $section->addTextBreak();
        $section->addText('Test 2', null, 'Paragraph');
        $section->addLink('http://test.com');
        $section->addTitle('Test', 1);
        $section->addPageBreak();
        $section->addTable();
        $section->addListItem('Test');
        $section->addImage($imageSrc);
        $section->addObject($objectSrc);
        $section->addTOC();
        $section = $phpWord->createSection();
        $textrun = $section->createTextRun();
        $textrun->addText('Test 3');
        $writer = new PHPWord_Writer_ODText($phpWord);
        $writer->save($file);

        $this->assertTrue(file_exists($file));

        unlink($file);
    }

    /**
     * @covers                      ::save
     * @expectedException           Exception
     * @expectedExceptionMessage    PHPWord object unassigned.
     */
    public function testSaveException()
    {
        $writer = new PHPWord_Writer_ODText();
        $writer->save();
    }

    /**
     * @covers  ::getWriterPart
     */
    public function testGetWriterPartNull()
    {
        $object = new PHPWord_Writer_ODText();
        $this->assertNull($object->getWriterPart('foo'));
    }

    /**
     * @covers  ::setUseDiskCaching
     * @covers  ::getUseDiskCaching
     */
    public function testSetGetUseDiskCaching()
    {
        $object = new PHPWord_Writer_ODText();
        $object->setUseDiskCaching(true, PHPWORD_TESTS_DIR_ROOT);
        $this->assertTrue($object->getUseDiskCaching());
        $this->assertEquals(PHPWORD_TESTS_DIR_ROOT, $object->getDiskCachingDirectory());
    }

    /**
     * @covers              ::setUseDiskCaching
     * @expectedException   Exception
     */
    public function testSetUseDiskCachingException()
    {
        $dir = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, 'foo')
        );

        $object = new PHPWord_Writer_ODText($phpWord);
        $object->setUseDiskCaching(true, $dir);
    }
}
