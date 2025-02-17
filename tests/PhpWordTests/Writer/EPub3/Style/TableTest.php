<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Style;

use PhpOffice\PhpWord\Writer\EPub3\Style\Table;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{
    /**
     * Test write method.
     */
    public function testWrite(): void
    {
        $style = new Table();

        $content = $style->write();

        self::assertStringContainsString('border-collapse: collapse;', $content);
        self::assertStringContainsString('width: 100%;', $content);
        self::assertStringContainsString('border: 1px solid black;', $content);
        self::assertStringContainsString('padding: 8px;', $content);
        self::assertStringContainsString('text-align: left;', $content);
        self::assertStringContainsString('table {', $content);
        self::assertStringContainsString('th, td {', $content);
        self::assertStringEndsWith('}', $content);
    }
}
