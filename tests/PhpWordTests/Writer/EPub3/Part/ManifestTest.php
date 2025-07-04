<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\EPub3;
use PhpOffice\PhpWord\Writer\EPub3\Part\Manifest;
use PHPUnit\Framework\TestCase;

class ManifestTest extends TestCase
{
    /**
     * @var Manifest
     */
    private $manifest;

    protected function setUp(): void
    {
        $this->manifest = new Manifest();
        $phpWord = new PhpWord();
        $writer = new EPub3($phpWord);
        $this->manifest->setParentWriter($writer);
    }

    public function testWrite(): void
    {
        $result = $this->manifest->write();

        self::assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $result);
        self::assertIsString($result);
        self::assertStringContainsString('<container version="1.0"', $result);
        self::assertStringContainsString('<rootfiles>', $result);
        self::assertStringContainsString('<rootfile full-path="content.opf"', $result);
    }
}
