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
        // Create temporary WPS file for testing with a non-empty zip archive
        $this->tempFile = tempnam(sys_get_temp_dir(), 'wps');
        $zip = new ZipArchive();
        $zip->open($this->tempFile, ZipArchive::CREATE);

        // Using a minimal meta.xml with sample data
        $metaXml = '<?xml version="1.0" encoding="UTF-8"?>
            <office:document-meta
                xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0"
                xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0">
                <office:meta>
                    <meta:generator>PHPWord</meta:generator>
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
        self::assertEquals('Test Creator', $docInfo->getCreator());
        self::assertEquals('Test Document Title', $docInfo->getTitle());
        self::assertEquals('Test Document Subject', $docInfo->getSubject());
        self::assertEquals('Test Document Description', $docInfo->getDescription());
        self::assertEquals('test, keywords, phpword', $docInfo->getKeywords());
        self::assertEquals('Test Category', $docInfo->getCategory());
        self::assertEquals('Test Company', $docInfo->getCompany());
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
        self::assertEquals('Only Title', $docInfo->getTitle());
        self::assertEquals('', $docInfo->getCreator());
        self::assertEquals('', $docInfo->getSubject());
        self::assertEquals('', $docInfo->getDescription());
        self::assertEquals('', $docInfo->getKeywords());
        self::assertEquals('', $docInfo->getCategory());
        self::assertEquals('', $docInfo->getCompany());

        unlink($minimalFile);
    }
}
