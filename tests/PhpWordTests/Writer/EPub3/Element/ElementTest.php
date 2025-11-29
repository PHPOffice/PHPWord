<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Element;

use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Exception\Exception as WordException;
use PhpOffice\PhpWord\Writer\EPub3\Element\AbstractElement as WriterElement;
use PhpOffice\PhpWord\Writer\EPub3\Element\Text as TextWriter;
use PHPUnit\Framework\TestCase;

class ElementTest extends TestCase
{
    public function testGetElementClass(): void
    {
        $element = new Text('test');
        $class = WriterElement::getElementClass($element);
        self::assertSame(TextWriter::class, $class);
    }

    public function testGetElementClassWithInvalidElement(): void
    {
        $this->expectException(WordException::class);
        $this->expectExceptionMessage('Writer element class');

        $element = new FakeElement();
        WriterElement::getElementClass($element);
    }
}
