<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Element;

use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Writer\EPub3\Element\Text as TextWriter;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    /**
     * @var XMLWriter
     */
    private $xmlWriter;

    /**
     * @var Text
     */
    private $element;

    /**
     * @var TextWriter
     */
    private $writer;

    protected function setUp(): void
    {
        $this->xmlWriter = new XMLWriter();
        $this->element = new Text('Sample Text');
        $this->writer = new TextWriter($this->xmlWriter, $this->element);
    }

    public function testWrite(): void
    {
        $this->writer->write();

        $expected = "<p>\n  <span>Sample Text</span>\n</p>\n";
        self::assertEquals($expected, $this->xmlWriter->getData());
    }

    public function testWriteWithFontStyle(): void
    {
        $this->element->setFontStyle('customStyle');

        $this->writer->write();

        $expected = "<p>\n  <span class=\"customStyle\">Sample Text</span>\n</p>\n";
        self::assertEquals($expected, $this->xmlWriter->getData());
    }

    public function testWriteWithParagraphStyle(): void
    {
        $this->element->setParagraphStyle('paragraphStyle');

        $this->writer->write();

        $expected = "<p class=\"paragraphStyle\">\n  <span>Sample Text</span>\n</p>\n";
        self::assertEquals($expected, $this->xmlWriter->getData());
    }

    public function testWriteWithoutP(): void
    {
        $text = new Text('Sample Text');
        $xmlWriter = new XMLWriter();
        $this->writer = new TextWriter($xmlWriter, $text, true);

        $this->writer->write();

        $expected = "<span>Sample Text</span>\n";
        self::assertEquals($expected, $xmlWriter->getData());
    }

    public function testWriteWithInvalidElement(): void
    {
        $invalidElement = $this->createMock(\PhpOffice\PhpWord\Element\AbstractElement::class);
        $writer = new TextWriter($this->xmlWriter, $invalidElement);

        $writer->write();

        self::assertEquals('', $this->xmlWriter->getData());
    }
}
