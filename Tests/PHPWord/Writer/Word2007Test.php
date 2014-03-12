<?php
namespace PHPWord\Tests\Writer;

use PHPWord_Writer_Word2007;
use PHPWord;
use PHPWord\Tests\TestHelperDOCX;

/**
 * Class Word2007Test
 *
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class Word2007Test extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

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
        $file = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_DIR_ROOT, '_files', 'temp.docx')
        );
        $writer->save($file);
        $this->assertTrue(file_exists($file));
        unlink($file);
    }

    /**
     * @covers PHPWord_Writer_Word2007::checkContentTypes
     */
    public function testCheckContentTypes()
    {
        $phpWord = new PHPWord();
        $section = $phpWord->createSection();
        $section->addImage(PHPWORD_TESTS_DIR_ROOT . "/_files/images/mars_noext_jpg");
        $section->addImage(PHPWORD_TESTS_DIR_ROOT . "/_files/images/mars.jpg");
        $section->addImage(PHPWORD_TESTS_DIR_ROOT . "/_files/images/mario.gif");
        $section->addImage(PHPWORD_TESTS_DIR_ROOT . "/_files/images/firefox.png");
        $section->addImage(PHPWORD_TESTS_DIR_ROOT . "/_files/images/duke_nukem.bmp");
        $section->addImage(PHPWORD_TESTS_DIR_ROOT . "/_files/images/angela_merkel.tif");

        $doc = TestHelperDOCX::getDocument($phpWord);
        $mediaPath = $doc->getPath() . "/word/media";

        $this->assertFileEquals(PHPWORD_TESTS_DIR_ROOT . "/_files/images/mars_noext_jpg", $mediaPath . "/section_image1.jpg");
        $this->assertFileEquals(PHPWORD_TESTS_DIR_ROOT . "/_files/images/mars.jpg", $mediaPath . "/section_image2.jpg");
        $this->assertFileEquals(PHPWORD_TESTS_DIR_ROOT . "/_files/images/mario.gif", $mediaPath . "/section_image3.gif");
        $this->assertFileEquals(PHPWORD_TESTS_DIR_ROOT . "/_files/images/firefox.png", $mediaPath . "/section_image4.png");
        $this->assertFileEquals(PHPWORD_TESTS_DIR_ROOT . "/_files/images/duke_nukem.bmp", $mediaPath . "/section_image5.bmp");
        $this->assertFileEquals(PHPWORD_TESTS_DIR_ROOT . "/_files/images/angela_merkel.tif", $mediaPath . "/section_image6.tif");
    }
}
