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
        $phpWord = new PhpWord();
        $this->content = new Content($phpWord);
        $section = $phpWord->addSection();
        $section->addText('Test content');

        $writer = new EPub3($phpWord);
        $this->content->setParentWriter($writer);
    }

    public function testWrite(): void
    {
        $result = $this->content->write();

        self::assertIsString($result);
        self::assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $result);
        self::assertStringContainsString('<package', $result);
        self::assertStringContainsString('<manifest>', $result);
        self::assertStringContainsString('<spine>', $result);
    }
}
