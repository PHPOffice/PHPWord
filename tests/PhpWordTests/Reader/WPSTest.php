<?php

namespace PhpWordTests\Reader;

use PhpOffice\PhpWord\Reader\WPS;
use PhpOffice\PhpWord\PhpWord;
use PHPUnit\Framework\TestCase;
use ZipArchive;

class WPSTest extends TestCase
{
    /**
     * @var string
     */
    private $xmlWpsFile;

    /**
     * @var string
     */
    private $binaryWpsFile;

    protected function setUp(): void
    {
        // Create a temporary XML-based WPS file
        $this->xmlWpsFile = tempnam(sys_get_temp_dir(), 'wps');
        $zip = new ZipArchive();
        $zip->open($this->xmlWpsFile, ZipArchive::CREATE);
        
        // Add content.xml
        $contentXml = '<?xml version="1.0" encoding="UTF-8"?>
            <office:document-content
                xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0"
                xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0">
                <office:body>
                    <office:text>
                        <text:p>Test paragraph in content.xml</text:p>
                    </office:text>
                </office:body>
            </office:document-content>';
        $zip->addFromString('content.xml', $contentXml);
        
        // Add meta.xml
        $metaXml = '<?xml version="1.0" encoding="UTF-8"?>
            <office:document-meta 
                xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0"
                xmlns:dc="http://purl.org/dc/elements/1.1/"
                xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0">
                <office:meta>
                    <dc:title>Test Document Title</dc:title>
                    <dc:creator>Test Author</dc:creator>
                </office:meta>
            </office:document-meta>';
        $zip->addFromString('meta.xml', $metaXml);
        
        // Add manifest.xml
        $manifestXml = '<?xml version="1.0" encoding="UTF-8"?>
            <manifest:manifest
                xmlns:manifest="urn:oasis:names:tc:opendocument:xmlns:manifest:1.0">
                <manifest:file-entry manifest:media-type="application/vnd.wps-office.document" manifest:full-path="/"/>
                <manifest:file-entry manifest:media-type="text/xml" manifest:full-path="content.xml"/>
                <manifest:file-entry manifest:media-type="text/xml" manifest:full-path="meta.xml"/>
            </manifest:manifest>';
        $zip->addEmptyDir('META-INF');
        $zip->addFromString('META-INF/manifest.xml', $manifestXml);
        
        $zip->close();
        
        // Create a temporary binary WPS file with magic pattern
        $this->binaryWpsFile = tempnam(sys_get_temp_dir(), 'wps');
        file_put_contents($this->binaryWpsFile, 'CHNKWKS' . str_repeat(' ', 100) . 'Test text content');
    }

    protected function tearDown(): void
    {
        if (file_exists($this->xmlWpsFile)) {
            unlink($this->xmlWpsFile);
        }
        
        if (file_exists($this->binaryWpsFile)) {
            unlink($this->binaryWpsFile);
        }
    }

    public function testLoadXmlBasedWpsFile(): void
    {
        $reader = new WPS();
        $phpWord = $reader->load($this->xmlWpsFile);

        $this->assertInstanceOf(PhpWord::class, $phpWord);
        
        // Check that document info was read from meta.xml
        $docInfo = $phpWord->getDocInfo();
        $this->assertEquals('Test Document Title', $docInfo->getTitle());
        $this->assertEquals('Test Author', $docInfo->getCreator());
    }

    public function testLoadBinaryWpsFile(): void
    {
        $reader = new WPS();
        $phpWord = $reader->load($this->binaryWpsFile);

        $this->assertInstanceOf(PhpWord::class, $phpWord);
        
        // Binary WPS should have created a section with the extracted text
        $sections = $phpWord->getSections();
        $this->assertCount(1, $sections);
    }
    
    public function testCanReadFlag(): void
    {
        $reader = new WPS();
        
        // XML-based WPS file
        $this->assertTrue($reader->canRead($this->xmlWpsFile));
        
        // Binary WPS file
        $this->assertTrue($reader->canRead($this->binaryWpsFile));
        
        // Non-WPS file
        $invalidFile = tempnam(sys_get_temp_dir(), 'txt');
        file_put_contents($invalidFile, 'Not a WPS file');
        $this->assertFalse($reader->canRead($invalidFile));
        unlink($invalidFile);
    }
    
    public function testInvalidFile(): void
    {
        $this->expectException(\Exception::class);
        
        $reader = new WPS();
        $reader->load('/path/to/non/existing/file.wps');

        // The exception should be thrown before this line
        $this->fail('Expected exception not thrown');
    }
}