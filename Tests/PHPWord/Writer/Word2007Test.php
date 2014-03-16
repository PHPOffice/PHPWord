<?php
namespace PHPWord\Tests\Writer;

use PHPWord_Writer_Word2007;
use PHPWord;
use PHPWord\Tests\TestHelperDOCX;

/**
 * Class Word2007Test
 *
 * @package             PHPWord\Tests
 * @coversDefaultClass  PHPWord_Writer_Word2007
 * @runTestsInSeparateProcesses
 */
class Word2007Test extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * covers  ::__construct
     */
    public function testConstruct()
    {
        $object = new PHPWord_Writer_Word2007(new PHPWord());

        $writerParts = array('ContentTypes', 'Rels', 'DocProps',
            'DocumentRels', 'Document', 'Styles', 'Header', 'Footer',
            'Footnotes', 'FootnotesRels');
        foreach ($writerParts as $part) {
            $this->assertInstanceOf(
                "PHPWord_Writer_Word2007_{$part}",
                $object->getWriterPart($part)
            );
            $this->assertInstanceOf(
                "PHPWord_Writer_Word2007",
                $object->getWriterPart($part)->getParentWriter()
            );
        }
    }

    /**
     * @covers  ::save
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

        $writer = new PHPWord_Writer_Word2007($phpWord);
        $file = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'temp.docx')
        );
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
        $writer = new PHPWord_Writer_Word2007();
        $writer->save();
    }

    /**
     * @covers  ::getWriterPart
     */
    public function testGetWriterPartNull()
    {
        $object = new PHPWord_Writer_Word2007();
        $this->assertNull($object->getWriterPart('foo'));
    }

    /**
     * @covers  ::checkContentTypes
     */
    public function testCheckContentTypes()
    {
        $images = array(
            'mars_noext_jpg'    => '1.jpg',
            'mars.jpg'          => '2.jpg',
            'mario.gif'         => '3.gif',
            'firefox.png'       => '4.png',
            'duke_nukem.bmp'    => '5.bmp',
            'angela_merkel.tif' => '6.tif',
        );
        $phpWord = new PHPWord();
        $section = $phpWord->createSection();
        foreach ($images as $source => $target) {
            $section->addImage(PHPWORD_TESTS_DIR_ROOT . "/_files/images/{$source}");
        }

        $doc = TestHelperDOCX::getDocument($phpWord);
        $mediaPath = $doc->getPath() . "/word/media";

        foreach ($images as $source => $target) {
            $this->assertFileEquals(
                PHPWORD_TESTS_DIR_ROOT . "/_files/images/{$source}",
                $mediaPath . "/section_image{$target}"
            );
        }
    }

    /**
     * @covers  ::setUseDiskCaching
     * @covers  ::getUseDiskCaching
     * @covers  ::getDiskCachingDirectory
     */
    public function testSetGetUseDiskCaching()
    {
        $object = new PHPWord_Writer_Word2007();
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

        $object = new PHPWord_Writer_Word2007();
        $object->setUseDiskCaching(true, $dir);
    }
}
