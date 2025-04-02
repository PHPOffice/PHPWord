<?php

namespace PhpWordTests\Reader\WPS;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Reader\WPS\Content;
use PHPUnit\Framework\TestCase;
use ZipArchive;

class ContentTest extends TestCase
{
    /**
     * @var string
     */
    private $tempFile;

    protected function setUp(): void
    {
        // Create temporary WPS file for testing
        $this->tempFile = tempnam(sys_get_temp_dir(), 'wps');
        $zip = new ZipArchive();
        $zip->open($this->tempFile, ZipArchive::CREATE);

        // Add content.xml with sample data
        $contentXml = '<?xml version="1.0" encoding="UTF-8"?>
            <office:document-content
                xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0"
                xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0"
                xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0"
                xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0">
                <office:body>
                    <office:text>
                        <text:h text:outline-level="1">Heading 1</text:h>
                        <text:p>Simple paragraph</text:p>
                        <text:p>Paragraph with <text:span>styled text</text:span></text:p>
                        <text:p>Paragraph with <text:line-break/>line break</text:p>
                    </office:text>
                </office:body>
            </office:document-content>';
        $zip->addFromString('content.xml', $contentXml);
        $zip->close();
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }

    public function testRead(): void
    {
        $phpWord = new PhpWord();

        self::assertFileExists($this->tempFile);
        // Added check to ensure file is not empty, preventing use of empty file in ZipArchive
        self::assertGreaterThan(0, filesize($this->tempFile), 'Generated file is empty.');

        $zip = new ZipArchive();
        $openResult = $zip->open($this->tempFile);
        self::assertTrue($openResult === true, 'Unable to open generated zip archive');

        $content = new Content($this->tempFile, 'content.xml');
        $content->read($phpWord);

        // Verify section and content was added
        $sections = $phpWord->getSections();
        self::assertCount(1, $sections);
        $section = $sections[0];
        $elements = $section->getElements();

        // Should have elements: heading, and 3 paragraphs
        self::assertCount(4, $elements);

        // Test heading
        $heading = $elements[0];
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Title', $heading);
        self::assertEquals('Heading 1', $heading->getText());
        self::assertEquals(1, $heading->getDepth());

        // Test simple paragraph
        $paragraph1 = $elements[1];
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $paragraph1);
        $paragraphElements = $paragraph1->getElements();
        self::assertCount(1, $paragraphElements);
        if ($paragraphElements[0] instanceof \PhpOffice\PhpWord\Element\Text) {
            self::assertEquals('Simple paragraph', $paragraphElements[0]->getText());
        }

        // Test paragraph with styled text
        $paragraph2 = $elements[2];
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $paragraph2);
        $paragraphElements = $paragraph2->getElements();
        self::assertCount(2, $paragraphElements);
        if ($paragraphElements[0] instanceof \PhpOffice\PhpWord\Element\Text) {
            self::assertEquals('Paragraph with ', $paragraphElements[0]->getText());
        }
        if ($paragraphElements[1] instanceof \PhpOffice\PhpWord\Element\Text) {
            self::assertEquals('styled text', $paragraphElements[1]->getText());
        }

        // Test paragraph with line break
        $paragraph3 = $elements[3];
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $paragraph3);
        $paragraphElements = $paragraph3->getElements();
        self::assertCount(3, $paragraphElements);
        if ($paragraphElements[0] instanceof \PhpOffice\PhpWord\Element\Text) {
            self::assertEquals('Paragraph with ', $paragraphElements[0]->getText());
        }
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextBreak', $paragraphElements[1]);
        if ($paragraphElements[2] instanceof \PhpOffice\PhpWord\Element\Text) {
            self::assertEquals('line break', $paragraphElements[2]->getText());
        }
    }

    public function testReadEmptyContent(): void
    {
        // Create empty content file
        $emptyFile = tempnam(sys_get_temp_dir(), 'wps');
        $zip = new ZipArchive();
        $zip->open($emptyFile, ZipArchive::CREATE);
        $contentXml = '<?xml version="1.0" encoding="UTF-8"?>
            <office:document-content
                xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0">
                <office:body>
                    <office:text>
                    </office:text>
                </office:body>
            </office:document-content>';
        $zip->addFromString('content.xml', $contentXml);
        $zip->close();

        $phpWord = new PhpWord();
        $content = new Content($emptyFile, 'content.xml');
        $content->read($phpWord);

        // Verify that no elements were added to the section
        $sections = $phpWord->getSections();
        self::assertCount(0, $sections);

        unlink($emptyFile);
    }
}
