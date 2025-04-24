<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\EPub3;
use PhpOffice\PhpWord\Writer\EPub3\Part\Meta;
use PHPUnit\Framework\TestCase;

class MetaTest extends TestCase
{
    /**
     * @var Meta
     */
    private $meta;

    protected function setUp(): void
    {
        $this->meta = new Meta();
        $phpWord = new PhpWord();
        $writer = new EPub3($phpWord);
        $this->meta->setParentWriter($writer);
    }

    public function testWrite(): void
    {
        $result = $this->meta->write();

        self::assertIsString($result);
        self::assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $result);
        self::assertStringContainsString('<metadata', $result);
        self::assertStringContainsString('xmlns:dc="http://purl.org/dc/elements/1.1/"', $result);
    }

    public function testWriteWithDocInfo(): void
    {
        $phpWord = new PhpWord();
        $properties = $phpWord->getDocInfo();
        $properties->setCreator('PHPWord');
        $properties->setTitle('Test Title');
        $properties->setKeywords('test, keywords');

        $writer = new EPub3($phpWord);
        $this->meta->setParentWriter($writer);

        $expected = '<?xml version="1.0" encoding="UTF-8"?>\n<metadata xmlns="http://www.idpf.org/2007/opf" xmlns:dc="http://purl.org/dc/elements/1.1/"><dc:title>Test Title</dc:title><dc:language>en</dc:language><dc:identifier>urn:uuid:12345</dc:identifier><dc:creator>PHPWord</dc:creator><meta property="dcterms:modified">2023-01-01T00:00:00Z</meta></metadata>';

        $result = $this->meta->write();

        self::assertStringContainsString('<dc:creator>PHPWord</dc:creator>', $result);
        self::assertStringContainsString('<dc:title>Test Title</dc:title>', $result);
    }
}
