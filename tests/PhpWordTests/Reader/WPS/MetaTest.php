<?php

namespace PhpWordTests\Reader\WPS;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Reader\WPS\Meta;
use PHPUnit\Framework\TestCase;
use ZipArchive;

class MetaTest extends TestCase
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

        // Add meta.xml with sample document properties
        $metaXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
            <office:document-meta 
                xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                xmlns:dc="http://purl.org/dc/elements/1.1/"
                xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0"
                xmlns:wps="http://wps.kdanmobile.com/2017/office">
                <office:meta>
                    <meta:initial-creator>Test Creator</meta:initial-creator>
                    <dc:creator>Test Creator</dc:creator>
                    <meta:creation-date>2025-04-01T10:00:00</meta:creation-date>
                    <dc:date>2025-04-01T11:00:00</dc:date>
                    <dc:title>Test Document Title</dc:title>
                    <dc:description>Test Document Description</dc:description>
                    <dc:subject>Test Document Subject</dc:subject>
                    <meta:keyword>test, keywords, phpword</meta:keyword>
                    <meta:user-defined meta:name="Category">Test Category</meta:user-defined>
                    <meta:user-defined meta:name="Company">Test Company</meta:user-defined>
                </office:meta>
            </office:document-meta>';
        $zip->addFromString('meta.xml', $metaXml);
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
        $meta = new Meta($this->tempFile, 'meta.xml');
        $meta->read($phpWord);

        $docInfo = $phpWord->getDocInfo();
        
        // Verify all metadata properties were correctly extracted
        $this->assertEquals('Test Creator', $docInfo->getCreator());
        $this->assertEquals('Test Document Title', $docInfo->getTitle());
        $this->assertEquals('Test Document Subject', $docInfo->getSubject());
        $this->assertEquals('Test Document Description', $docInfo->getDescription());
        $this->assertEquals('test, keywords, phpword', $docInfo->getKeywords());
        $this->assertEquals('Test Category', $docInfo->getCategory());
        $this->assertEquals('Test Company', $docInfo->getCompany());
    }
    
    public function testReadWithMissingProperties(): void
    {
        // Create a file with minimal metadata
        $minimalFile = tempnam(sys_get_temp_dir(), 'wps');
        $zip = new ZipArchive();
        $zip->open($minimalFile, ZipArchive::CREATE);
        
        $minimalMetaXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
            <office:document-meta 
                xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0"
                xmlns:dc="http://purl.org/dc/elements/1.1/"
                xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0">
                <office:meta>
                    <dc:title>Only Title</dc:title>
                </office:meta>
            </office:document-meta>';
        $zip->addFromString('meta.xml', $minimalMetaXml);
        $zip->close();

        $phpWord = new PhpWord();
        $meta = new Meta($minimalFile, 'meta.xml');
        $meta->read($phpWord);

        $docInfo = $phpWord->getDocInfo();
        
        // Verify only the title was set, other properties should have default values
        $this->assertEquals('Only Title', $docInfo->getTitle());
        $this->assertNull($docInfo->getCreator());
        $this->assertNull($docInfo->getSubject());
        $this->assertNull($docInfo->getDescription());
        $this->assertNull($docInfo->getKeywords());
        $this->assertNull($docInfo->getCategory());
        $this->assertNull($docInfo->getCompany());
        
        unlink($minimalFile);
    }
}