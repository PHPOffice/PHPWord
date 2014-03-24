<?php
namespace PhpOffice\PhpWord\Tests\Writer;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;

/**
 * @coversDefaultClass          \PhpOffice\PhpWord\Writer\Word2007
 * @runTestsInSeparateProcesses
 */
class Word2007Test extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * covers ::__construct
     */
    public function testConstruct()
    {
        $object = new Word2007(new PhpWord());

        $writerParts = array(
            'ContentTypes',
            'Rels',
            'DocProps',
            'DocumentRels',
            'Document',
            'Styles',
            'Header',
            'Footer',
            'Footnotes',
            'FootnotesRels',
        );
        foreach ($writerParts as $part) {
            $this->assertInstanceOf(
                "PhpOffice\\PhpWord\\Writer\\Word2007\\{$part}",
                $object->getWriterPart($part)
            );
            $this->assertInstanceOf(
                'PhpOffice\\PhpWord\\Writer\\Word2007',
                $object->getWriterPart($part)->getParentWriter()
            );
        }
    }

    /**
     * @covers ::save
     */
    public function testSave()
    {
        $phpWord = new PhpWord();
        $phpWord->addFontStyle('Font', array('size' => 11));
        $phpWord->addParagraphStyle('Paragraph', array('align' => 'center'));
        $section = $phpWord->createSection();
        $section->addText('Test 1', 'Font', 'Paragraph');
        $section->addTextBreak();
        $section->addText('Test 2');
        $section = $phpWord->createSection();
        $textrun = $section->createTextRun();
        $textrun->addText('Test 3');

        $writer = new Word2007($phpWord);
        $file = __DIR__ . "/../_files/temp.docx";
        $writer->save($file);
        $this->assertTrue(\file_exists($file));
        unlink($file);
    }

    /**
     * @covers ::checkContentTypes
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
        $phpWord = new PhpWord();
        $section = $phpWord->createSection();
        foreach ($images as $source => $target) {
            $section->addImage(__DIR__ . "/../_files/images/{$source}");
        }

        $doc = TestHelperDOCX::getDocument($phpWord);
        $mediaPath = $doc->getPath() . "/word/media";

        foreach ($images as $source => $target) {
            $this->assertFileEquals(
                __DIR__ . "/../_files/images/{$source}",
                $mediaPath . "/section_image{$target}"
            );
        }
    }

    /**
     * @covers ::setUseDiskCaching
     * @covers ::getUseDiskCaching
     */
    public function testSetGetUseDiskCaching()
    {
        $object = new Word2007();
        $object->setUseDiskCaching(true, \PHPWORD_TESTS_BASE_DIR);

        $this->assertTrue($object->getUseDiskCaching());
    }

    /**
     * @covers             ::setUseDiskCaching
     * @expectedException  \PhpOffice\PhpWord\Exceptions\Exception
     */
    public function testSetUseDiskCachingException()
    {
        $dir = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_BASE_DIR, 'foo')
        );

        $object = new Word2007();
        $object->setUseDiskCaching(true, $dir);
    }
}
