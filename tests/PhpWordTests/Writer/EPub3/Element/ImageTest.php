<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Element;

use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Style\Image as ImageStyle;
use PhpOffice\PhpWord\Writer\EPub3\Element\Image as ImageWriter;
use PHPUnit\Framework\TestCase;
use PhpOffice\PhpWord\Shared\XMLWriter;

class ImageTest extends TestCase
{
    /**
     * @var XMLWriter
     */
    private $xmlWriter;

    /**
     * @var Image
     */
    private $element;

    /**
     * @var ImageWriter
     */
    private $writer;

    protected function setUp(): void
    {
        $this->xmlWriter = new XMLWriter();
        $style = new ImageStyle();
        $style->setWidth(100);
        $style->setHeight(100);
        $this->element = new Image('tests/PhpWordTests/_files/images/earth.jpg', $style);
        $this->writer = new ImageWriter($this->xmlWriter, $this->element);
    }

    public function testWrite(): void
    {
        $this->writer->write();

        $expected = '<p><img src="media/image.jpg" style="width:500px;height:500px;"/></p>';
        $this->assertEquals($expected, $this->xmlWriter->getData());
    }

    public function testWriteWithoutP(): void
    {
        $style = new ImageStyle();
        $style->setWidth(100);
        $style->setHeight(100);
        $this->element = new Image('tests/PhpWordTests/_files/images/earth.jpg', $style);
        $this->writer = new ImageWriter($this->xmlWriter, $this->element, true);

        $this->writer->write();

        $expected = '<img src="media/image.jpg" style="width:500px;height:500px;"/>';
        $this->assertEquals($expected, $this->xmlWriter->getData());
    }

    public function testWriteWithInvalidElement(): void
    {
        $invalidElement = $this->createMock(\PhpOffice\PhpWord\Element\AbstractElement::class);
        $writer = new ImageWriter($this->xmlWriter, $invalidElement);

        $writer->write();

        $this->assertEquals('', $this->xmlWriter->getData());
    }
}
