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

        self::assertStringContainsString('margin-top: 0;', $content);
        self::assertStringContainsString('margin-bottom: 1em;', $content);
        self::assertStringContainsString('text-align: left;', $content);
        self::assertStringStartsWith('p {', $content);
        self::assertStringEndsWith('}', $content);
    }
}
