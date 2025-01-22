<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3;

use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Writer\EPub3\Element\AbstractElement;
use PHPUnit\Framework\TestCase;

class ElementTest extends TestCase
{
    public function testGetElementClass(): void
    {
        $element = new Text('test');
        $class = AbstractElement::getElementClass($element);
        
        $this->assertEquals('PhpOffice\\PhpWord\\Writer\\EPub3\\Element\\Text', $class);
    }

    public function testGetElementClassWithInvalidElement(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\Exception::class);
        
        $element = new \stdClass();
        AbstractElement::getElementClass($element);
    }
}
