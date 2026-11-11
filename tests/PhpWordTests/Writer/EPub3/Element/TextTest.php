<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Element;

use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Writer\EPub3\Element\Text as TextWriter;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    public function testWrite(): void
    {
        $xmlWriter = new XMLWriter();
        $element = new Text('Sample Text');
        $writer = new TextWriter($xmlWriter, $element);
        $writer->write();

        $expected = "<p>\n  <span>Sample Text</span>\n</p>\n";
        self::assertSame($expected, $xmlWriter->getData());
    }

    public function testWriteWithFontStyle(): void
    {
        $xmlWriter = new XMLWriter();
        $element = new Text('Sample Text');
        $writer = new TextWriter($xmlWriter, $element);
        $element->setFontStyle('customStyle');

        $writer->write();

        $expected = "<p>\n  <span class=\"customStyle\">Sample Text</span>\n</p>\n";
        self::assertSame($expected, $xmlWriter->getData());
    }

    public function testWriteWithParagraphStyle(): void
    {
        $xmlWriter = new XMLWriter();
        $element = new Text('Sample Text');
        $writer = new TextWriter($xmlWriter, $element);
        $element->setParagraphStyle('paragraphStyle');

        $writer->write();

        $expected = "<p class=\"paragraphStyle\">\n  <span>Sample Text</span>\n</p>\n";
        self::assertSame($expected, $xmlWriter->getData());
    }

    public function testWriteWithoutP(): void
    {
        $text = new Text('Sample Text');
        $xmlWriter = new XMLWriter();
        $writer = new TextWriter($xmlWriter, $text, true);

        $writer->write();

        $expected = "<span>Sample Text</span>\n";
        self::assertSame($expected, $xmlWriter->getData());
    }

    public function testWriteWithInvalidElement(): void
    {
        $xmlWriter = new XMLWriter();
        $invalidElement = new FakeElement();
        $writer = new TextWriter($xmlWriter, $invalidElement);

        $writer->write();

        self::assertSame('', $xmlWriter->getData());
    }
}
