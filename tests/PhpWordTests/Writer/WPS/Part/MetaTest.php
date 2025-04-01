<?php

namespace PhpWordTests\Writer\WPS\Part;

use PhpOffice\PhpWord\Writer\WPS\Part\Meta;
use PHPUnit\Framework\TestCase;

class MetaTest extends TestCase
{
    public function testWrite(): void
    {
        $meta = new Meta();
        $result = $meta->write();

        // Assert that the result is a string
        self::assertIsString($result);

        // Assert that the result contains expected XML structure
        self::assertStringContainsString('<office:document-meta', $result);
        self::assertStringContainsString('xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0"', $result);
        self::assertStringContainsString('xmlns:xlink="http://www.w3.org/1999/xlink"', $result);
        self::assertStringContainsString('xmlns:dc="http://purl.org/dc/elements/1.1/"', $result);
        self::assertStringContainsString('xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0"', $result);
        self::assertStringContainsString('xmlns:wps="http://wps.kdanmobile.com/2017/office"', $result);
        self::assertStringContainsString('<office:meta>', $result);
    }
}
