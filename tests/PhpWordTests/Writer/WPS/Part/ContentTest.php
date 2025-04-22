<?php

namespace PhpWordTests\Writer\WPS\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\WPS;
use PhpOffice\PhpWord\Writer\WPS\Part\Content;
use PHPUnit\Framework\TestCase;

class ContentTest extends TestCase
{
    public function testWrite(): void
    {
        // Arrange: Create necessary objects and set parent writer
        $phpWord = new PhpWord();
        $phpWord->addSection(); // Add a section to avoid errors during write
        $writer = new WPS($phpWord);
        /** @var Content $content */
        $content = $writer->getWriterPart('content'); // Get part from writer

        // Act: Call the write method
        $result = $content->write();

        // Assert that the result is a string
        self::assertIsString($result);
        // Assert that the result contains expected XML structure
        self::assertStringContainsString('<office:document-content', $result);
        self::assertStringContainsString('xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0"', $result);
        self::assertStringContainsString('xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0"', $result);
        self::assertStringContainsString('xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0"', $result);
        self::assertStringContainsString('xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0"', $result);
        self::assertStringContainsString('xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0"', $result);
        self::assertStringContainsString('xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0"', $result);
        self::assertStringContainsString('xmlns:xlink="http://www.w3.org/1999/xlink"', $result);
        self::assertStringContainsString('<office:scripts>', $result);
        self::assertStringContainsString('<office:font-face-decls>', $result);
        self::assertStringContainsString('<style:font-face style:name="Arial" svg:font-family="Arial"', $result);
        self::assertStringContainsString('<office:automatic-styles>', $result);
        self::assertStringContainsString('<office:body>', $result);
        self::assertStringContainsString('<office:text>', $result);
    }
}
