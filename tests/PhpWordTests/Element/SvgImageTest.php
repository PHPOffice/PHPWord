<?php
namespace PhpOffice\PhpWord\Tests\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Image;
use PHPUnit\Framework\TestCase;

class SvgImageTest extends TestCase
{

    protected function setUp(): void
    {
        $svgPath = __DIR__ . '/../_files/images/sample.svg';
        if (!file_exists($svgPath)) {
            $this->markTestSkipped('SVG file not found: ' . $svgPath);
        }
    }

    public function testAddSvgImageWithoutStyles(): void
    {
        $svgPath = __DIR__ . '/../_files/images/sample.svg';
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $image = $section->addImage($svgPath);

        $this->assertEquals($svgPath, $image->getSource());
        $this->assertEquals('image/svg+xml', $image->getImageType());
    }

    public function testAddSvgImageWithStyles(): void
    {
        $svgPath = __DIR__ . '/../_files/images/sample.svg';
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $options = [
            'width' => 200,
            'height' => 200,
            'wrappingStyle' => Image::WRAPPING_STYLE_BEHIND,
        ];

        $image = $section->addImage($svgPath, $options);

        $this->assertEquals(200, $image->getStyle()->getWidth());
        $this->assertEquals(200, $image->getStyle()->getHeight());
        $this->assertEquals(Image::WRAPPING_STYLE_BEHIND, $image->getStyle()->getWrappingStyle());
    }
}
