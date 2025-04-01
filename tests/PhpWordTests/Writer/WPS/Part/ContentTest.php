<?php

namespace PhpWordTests\Writer\WPS\Part;

use PhpOffice\PhpWord\Writer\WPS\Part\Content;
use PHPUnit\Framework\TestCase;

class ContentTest extends TestCase
{
    public function testWrite(): void
    {
        $content = new Content();
        $result = $content->write();
        
        // Assert that the result is a string
        $this->assertIsString($result);
        
        // Assert that the result contains expected XML structure
        $this->assertStringContainsString('<office:document-content', $result);
        $this->assertStringContainsString('xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0"', $result);
        $this->assertStringContainsString('xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0"', $result);
        $this->assertStringContainsString('xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0"', $result);
        $this->assertStringContainsString('xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0"', $result);
        $this->assertStringContainsString('xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0"', $result);
        $this->assertStringContainsString('xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0"', $result);
        $this->assertStringContainsString('xmlns:xlink="http://www.w3.org/1999/xlink"', $result);
        $this->assertStringContainsString('<office:scripts>', $result);
        $this->assertStringContainsString('<office:font-face-decls>', $result);
        $this->assertStringContainsString('<style:font-face style:name="Arial" svg:font-family="Arial"', $result);
        $this->assertStringContainsString('<office:automatic-styles>', $result);
        $this->assertStringContainsString('<office:body>', $result);
        $this->assertStringContainsString('<office:text>', $result);
    }
}