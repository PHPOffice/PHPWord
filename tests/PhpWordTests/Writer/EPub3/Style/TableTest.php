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

        $this->assertStringContainsString('border-collapse: collapse;', $content);
        $this->assertStringContainsString('width: 100%;', $content);
        $this->assertStringContainsString('border: 1px solid black;', $content);
        $this->assertStringContainsString('padding: 8px;', $content);
        $this->assertStringContainsString('text-align: left;', $content);
        $this->assertStringContainsString('table {', $content);
        $this->assertStringContainsString('th, td {', $content);
        $this->assertStringEndsWith('}', $content);
    }
}
