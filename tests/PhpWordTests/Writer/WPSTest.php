<?php

namespace PhpWordTests\Writer;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\WPS;
use PhpOffice\PhpWord\Writer\WPS\Media;
use PHPUnit\Framework\TestCase;
use ZipArchive;

class WPSWriterTest extends TestCase
{
    protected function setUp(): void
    {
        // Clear media elements before each test
        Media::clearElements();
    }
    
    public function testSaveWpsFile(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Hello, WPS!');

        $writer = new WPS($phpWord);
        $tempFile = tempnam(sys_get_temp_dir(), 'wps');
        $writer->save($tempFile);

        $this->assertFileExists($tempFile);
        
        // Test ZIP archive content
        $zip = new ZipArchive();
        $zip->open($tempFile);
        
        // Verify required files exist
        $this->assertTrue($zip->locateName('content.xml') !== false);
        $this->assertTrue($zip->locateName('meta.xml') !== false);
        $this->assertTrue($zip->locateName('META-INF/manifest.xml') !== false);
        
        $zip->close();
        unlink($tempFile);
    }
    
    public function testWriterParts(): void
    {
        $phpWord = new PhpWord();
        $writer = new WPS($phpWord);
        
        // Test the writer parts are initialized correctly
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Writer\\WPS\\Part\\Content', $writer->getWriterPart('content'));
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Writer\\WPS\\Part\\Meta', $writer->getWriterPart('meta'));
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Writer\\WPS\\Part\\Manifest', $writer->getWriterPart('manifest'));
    }
    
    public function testWithMedia(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        
        // Add an image to the document
        $imagePath = __DIR__ . '/../../_files/images/earth.jpg';
        $section->addImage($imagePath);
        
        // Create header and add an image to it
        $header = $section->addHeader();
        $header->addImage($imagePath);
        
        // Create footer and add an image to it
        $footer = $section->addFooter();
        $footer->addImage($imagePath);
        
        $writer = new WPS($phpWord);
        $tempFile = tempnam(sys_get_temp_dir(), 'wps');
        $writer->save($tempFile);
        
        // Test ZIP archive contains images
        $zip = new ZipArchive();
        $zip->open($tempFile);
        
        // The exact path to images depends on the media handler implementation
        // Just verify the Pictures directory exists
        $this->assertTrue($zip->locateName('Pictures/') !== false);
        
        $zip->close();
        unlink($tempFile);
    }
    
    public function testSaveToOutput(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Hello, WPS!');
        
        $writer = new WPS($phpWord);
        
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();
        
        // Check that the output starts with the ZIP file signature (PK header)
        $this->assertEquals('PK', substr($content, 0, 2));
    }
}