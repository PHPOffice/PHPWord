<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */
namespace PhpOffice\PhpWord\Tests\Writer;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007
 *
 * @runTestsInSeparateProcesses
 */
class Word2007Test extends \PHPUnit_Framework_TestCase
{
    /**
     * Tear down after each test
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Construct
     */
    public function testConstruct()
    {
        $object = new Word2007(new PhpWord());

        $writerParts = array(
            'ContentTypes' => 'ContentTypes',
            'Rels' => 'Rels',
            'DocProps' => 'DocProps',
            'Document' => 'Document',
            'Styles' => 'Styles',
            'Numbering' => 'Numbering',
            'Settings' => 'Settings',
            'WebSettings' => 'WebSettings',
            'Header' => 'Header',
            'Footer' => 'Footer',
            'Footnotes' => 'Footnotes',
            'Endnotes' => 'Footnotes',
        );
        foreach ($writerParts as $part => $type) {
            $this->assertInstanceOf(
                "PhpOffice\\PhpWord\\Writer\\Word2007\\Part\\{$type}",
                $object->getWriterPart($part)
            );
            $this->assertInstanceOf(
                'PhpOffice\\PhpWord\\Writer\\Word2007',
                $object->getWriterPart($part)->getParentWriter()
            );
        }
    }

    /**
     * Save
     */
    public function testSave()
    {
        $localImage = __DIR__ . '/../_files/images/earth.jpg';
        $remoteImage = 'http://php.net//images/logos/php-med-trans-light.gif';
        $phpWord = new PhpWord();
        $phpWord->addFontStyle('Font', array('size' => 11));
        $phpWord->addParagraphStyle('Paragraph', array('align' => 'center'));
        $section = $phpWord->addSection();
        $section->addText('Test 1', 'Font', 'Paragraph');
        $section->addTextBreak();
        $section->addText('Test 2');
        $section = $phpWord->addSection();
        $textrun = $section->addTextRun();
        $textrun->addText('Test 3');
        $footnote = $textrun->addFootnote();
        $footnote->addLink('http://test.com');
        $header = $section->addHeader();
        $header->addImage($localImage);
        $footer = $section->addFooter();
        $footer->addImage($remoteImage);

        $writer = new Word2007($phpWord);
        $file = __DIR__ . "/../_files/temp.docx";
        $writer->save($file);

        $this->assertTrue(file_exists($file));

        unlink($file);
    }

    /**
     * Save using disk caching
     */
    public function testSaveUseDiskCaching()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Test');
        $footnote = $section->addFootnote();
        $footnote->addText('Test');

        $writer = new Word2007($phpWord);
        $writer->setUseDiskCaching(true);
        $file = __DIR__ . "/../_files/temp.docx";
        $writer->save($file);

        $this->assertTrue(file_exists($file));

        unlink($file);
    }

    /**
     * Save with no PhpWord object assigned
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage PhpWord object unassigned.
     */
    public function testSaveException()
    {
        $writer = new Word2007();
        $writer->save();
    }

    /**
     * Check content types
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
        $section = $phpWord->addSection();
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
     * Get writer part return null value
     */
    public function testGetWriterPartNull()
    {
        $object = new Word2007();
        $this->assertNull($object->getWriterPart());
    }

    /**
     * Set/get use disk caching
     */
    public function testSetGetUseDiskCaching()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $object = new Word2007($phpWord);
        $object->setUseDiskCaching(true, PHPWORD_TESTS_BASE_DIR);
        $writer = new Word2007($phpWord);
        $writer->save('php://output');

        $this->assertTrue($object->getUseDiskCaching());
    }

    /**
     * Use disk caching exception
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     */
    public function testSetUseDiskCachingException()
    {
        $dir = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_BASE_DIR, 'foo')
        );

        $object = new Word2007();
        $object->setUseDiskCaching(true, $dir);
    }
}
