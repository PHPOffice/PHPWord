<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\EPub3;
use PhpOffice\PhpWord\Writer\EPub3\Part\Content;
use PHPUnit\Framework\TestCase;

class ContentTest extends TestCase
{
    /**
     * @var Content
     */
    private $content;

    protected function setUp(): void
    {
        $this->content = new Content();
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Test content');
        
        $writer = new EPub3($phpWord);
        $this->content->setParentWriter($writer);
    }

    public function testWrite(): void
    {
        $result = $this->content->write();
        
        $this->assertIsString($result);
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $result);
        $this->assertStringContainsString('<package', $result);
        $this->assertStringContainsString('<manifest>', $result);
        $this->assertStringContainsString('<spine>', $result);
    }
}
