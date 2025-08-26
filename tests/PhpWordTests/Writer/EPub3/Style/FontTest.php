<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Style;

use PhpOffice\PhpWord\Writer\EPub3\Style\Font;
use PHPUnit\Framework\TestCase;

class FontTest extends TestCase
{
    /**
     * Test write method.
     */
    public function testWrite(): void
    {
        $style = new Font();

        $content = $style->write();

        self::assertStringContainsString('font-family: "Times New Roman", Times, serif;', $content);
        self::assertStringContainsString('font-size: 12pt;', $content);
        self::assertStringContainsString('color: #000000;', $content);
        self::assertStringStartsWith('body {', $content);
        self::assertStringEndsWith('}', $content);
    }
}
