<?php

namespace PhpWordTests\Writer\WPS\Part;

use PhpOffice\PhpWord\Writer\WPS\Part\Manifest;
use PHPUnit\Framework\TestCase;

class ManifestTest extends TestCase
{
    public function testWrite(): void
    {
        $manifest = new Manifest();
        $result = $manifest->write();

        // Assert that the result is a string
        $this->assertIsString($result);

        // Assert that the result contains expected XML structure
        $this->assertStringContainsString('<manifest:manifest', $result);
        $this->assertStringContainsString('xmlns:manifest="urn:oasis:names:tc:opendocument:xmlns:manifest:1.0"', $result);
        $this->assertStringContainsString('<manifest:file-entry manifest:media-type="application/vnd.wps-office.document" manifest:full-path="/"', $result);
        $this->assertStringContainsString('<manifest:file-entry manifest:media-type="text/xml" manifest:full-path="content.xml"', $result);
        $this->assertStringContainsString('<manifest:file-entry manifest:media-type="text/xml" manifest:full-path="meta.xml"', $result);
    }
}