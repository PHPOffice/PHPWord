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

        $this->assertStringContainsString('font-family: "Times New Roman", Times, serif;', $content);
        $this->assertStringContainsString('font-size: 12pt;', $content);
        $this->assertStringContainsString('color: #000000;', $content);
        $this->assertStringStartsWith('body {', $content);
        $this->assertStringEndsWith('}', $content);
    }
}
