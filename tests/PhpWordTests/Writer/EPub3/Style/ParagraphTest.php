<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Style;

use PhpOffice\PhpWord\Writer\EPub3\Style\Paragraph;
use PHPUnit\Framework\TestCase;

class ParagraphTest extends TestCase
{
    /**
     * Test write method.
     */
    public function testWrite(): void
    {
        $style = new Paragraph();

        $content = $style->write();

        $this->assertStringContainsString('margin-top: 0;', $content);
        $this->assertStringContainsString('margin-bottom: 1em;', $content);
        $this->assertStringContainsString('text-align: left;', $content);
        $this->assertStringStartsWith('p {', $content);
        $this->assertStringEndsWith('}', $content);
    }
}
