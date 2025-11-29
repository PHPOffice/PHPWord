<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Element;

use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style\Image as ImageStyle;
use PhpOffice\PhpWord\Writer\EPub3\Element\Image as ImageWriter;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function testWrite(): void
    {
        $xmlWriter = new XMLWriter();
        $style = new ImageStyle();
        $style->setWidth(100);
        $style->setHeight(100);
        $element = new Image('tests/PhpWordTests/_files/images/earth.jpg', $style);
        $writer = new ImageWriter($xmlWriter, $element);
        $writer->write();

        $expected = '<p><img src="media/image.jpg" style="width:100px;height:100px;"/></p>';
        self::assertSame($expected, $xmlWriter->getData());
    }

    public function testWriteWithoutP(): void
    {
        $xmlWriter = new XMLWriter();
        $style = new ImageStyle();
        $style->setWidth(100);
        $style->setHeight(100);
        $element = new Image('tests/PhpWordTests/_files/images/earth.jpg', $style);
        $writer = new ImageWriter($xmlWriter, $element, true);

        $writer->write();

        $expected = '<img src="media/image.jpg" style="width:100px;height:100px;"/>';
        self::assertSame($expected, $xmlWriter->getData());
    }

    public function testWriteWithInvalidElement(): void
    {
        $xmlWriter = new XMLWriter();
        $invalidElement = $this->createMock(\PhpOffice\PhpWord\Element\AbstractElement::class);
        $writer = new ImageWriter($xmlWriter, $invalidElement);

        $writer->write();

        self::assertSame('', $xmlWriter->getData());
    }
}
