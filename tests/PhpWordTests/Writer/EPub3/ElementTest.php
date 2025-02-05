<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3;

use PhpOffice\PhpWord\Element\AbstractElement;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Writer\EPub3\Element\AbstractElement as WriterElement;
use PHPUnit\Framework\TestCase;

class ElementTest extends TestCase
{
    public function testGetElementClass(): void
    {
        $element = new Text('test');
        $class = WriterElement::getElementClass($element);
        self::assertEquals('PhpOffice\\PhpWord\\Writer\\EPub3\\Element\\Text', $class);
    }

    public function testGetElementClassWithInvalidElement(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\Exception::class);

        $element = $this->createMock(AbstractElement::class);
        WriterElement::getElementClass($element);
    }
}
