<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\Writer;

use finfo;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Writer\Word2007;
use PhpOffice\PhpWordTests\AbstractWebServerEmbeddedTest;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007.
 *
 * @runTestsInSeparateProcesses
 */
class Word2007Test extends AbstractWebServerEmbeddedTest
{
    /**
     * Tear down after each test.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Construct.
     */
    public function testConstruct(): void
    {
        $object = new Word2007(new PhpWord());

        $writerParts = [
            'ContentTypes' => 'ContentTypes',
            'Rels' => 'Rels',
            'DocPropsApp' => 'DocPropsApp',
            'Document' => 'Document',
            'Styles' => 'Styles',
            'Numbering' => 'Numbering',
            'Settings' => 'Settings',
            'WebSettings' => 'WebSettings',
            'Header' => 'Header',
            'Footer' => 'Footer',
            'Footnotes' => 'Footnotes',
            'Endnotes' => 'Footnotes',
        ];
        foreach ($writerParts as $part => $type) {
            self::assertInstanceOf(
                "PhpOffice\\PhpWord\\Writer\\Word2007\\Part\\{$type}",
                $object->getWriterPart($part)
            );
            self::assertInstanceOf(
                'PhpOffice\\PhpWord\\Writer\\Word2007',
                $object->getWriterPart($part)->getParentWriter()
            );
        }
    }

    /**
     * Save.
     */
    public function testSave(): void
    {
        $localImage = __DIR__ . '/../_files/images/earth.jpg';
        $remoteImage = self::getRemoteGifImageUrl();
        $phpWord = new PhpWord();
        $phpWord->addFontStyle('Font', ['size' => 11]);
        $phpWord->addParagraphStyle('Paragraph', ['alignment' => Jc::CENTER]);
        $section = $phpWord->addSection();
        $section->addText('Test 1', 'Font', 'Paragraph');
        $section->addTextBreak();
        $section->addText('Test 2');
        $section = $phpWord->addSection();
        $textrun = $section->addTextRun();
        $textrun->addText('Test 3');
        $footnote = $textrun->addFootnote();
        $footnote->addLink('https://github.com/PHPOffice/PHPWord');
        $header = $section->addHeader();
        $header->addImage($localImage);
        $footer = $section->addFooter();
        $footer->addImage($remoteImage);

        $writer = new Word2007($phpWord);
        $file = __DIR__ . '/../_files/temp.docx';
        $writer->save($file);

        self::assertFileExists($file);

        unlink($file);
    }

    /**
     * Save using disk caching.
     */
    public function testSaveUseDiskCaching(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Test');
        $footnote = $section->addFootnote();
        $footnote->addText('Test');

        $writer = new Word2007($phpWord);
        $dir = Settings::getTempDir() . DIRECTORY_SEPARATOR . 'phpwordcachefooter';
        if (!is_dir($dir) && !mkdir($dir)) {
            self::fail('Unable to create temp directory');
        }
        $writer->setUseDiskCaching(true, $dir);
        $file = __DIR__ . '/../_files/temp.docx';
        $writer->save($file);

        self::assertFileExists($file);

        unlink($file);
        TestHelperDOCX::deleteDir($dir);
    }

    /**
     * Check content types.
     */
    public function testCheckContentTypes(): void
    {
        $images = [
            'mars_noext_jpg' => '1.jpg',
            'mars.jpg' => '2.jpg',
            'mario.gif' => '3.gif',
            'firefox.png' => '4.png',
            'duke_nukem.bmp' => '5.bmp',
            'angela_merkel.tif' => '6.tif',
        ];
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        foreach ($images as $source => $target) {
            $section->addImage(__DIR__ . "/../_files/images/{$source}");
        }

        $doc = TestHelperDOCX::getDocument($phpWord);
        $mediaPath = $doc->getPath() . '/word/media';

        foreach ($images as $source => $target) {
            self::assertFileEquals(
                __DIR__ . "/../_files/images/{$source}",
                $mediaPath . "/section_image{$target}"
            );
        }
    }

    /**
     * Get writer part return null value.
     */
    public function testGetWriterPartNull(): void
    {
        $object = new Word2007();
        self::assertNull($object->getWriterPart());
    }

    /**
     * Set/get use disk caching.
     */
    public function testSetGetUseDiskCaching(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addSection();
        $object = new Word2007($phpWord);
        $object->setUseDiskCaching(true, PHPWORD_TESTS_BASE_DIR);
        $writer = new Word2007($phpWord);
        ob_start();
        $writer->save('php://output');
        $contents = ob_get_contents();
        self::assertTrue(ob_end_clean());
        self::assertTrue($object->isUseDiskCaching());
        self::assertNotEmpty($contents);
    }

    /**
     * Use disk caching exception.
     */
    public function testSetUseDiskCachingException(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\Exception::class);
        $dir = implode(DIRECTORY_SEPARATOR, [PHPWORD_TESTS_BASE_DIR, 'foo']);

        $object = new Word2007();
        $object->setUseDiskCaching(true, $dir);
    }

    /**
     * File is detected as Word 2007.
     */
    public function testMime(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Test 1');

        $writer = new Word2007($phpWord);
        $file = __DIR__ . '/../_files/temp.docx';
        $writer->save($file);

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file);

        self::assertEquals('application/vnd.openxmlformats-officedocument.wordprocessingml.document', $mime);

        unlink($file);
    }
}
