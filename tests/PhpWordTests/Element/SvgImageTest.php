<?php

namespace PhpOffice\PhpWord\Tests\Element;

use PhpOffice\PhpWord\Element\Image as ImageElement;
use PhpOffice\PhpWord\Exception\InvalidImageException;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Image;
use PHPUnit\Framework\TestCase;

class SvgImageTest extends TestCase
{
    private const SVG_PATH = 'samples/resources/sample.svg';

    public function testAddSvgImageWithoutStyles(): void
    {
        $svgPath = self::SVG_PATH;
        if (!file_exists($svgPath)) {
            self::markTestSkipped('SVG file not found: ' . $svgPath);
        }
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $image = $section->addImage($svgPath);

        self::assertSame($svgPath, $image->getSource());
        self::assertSame('image/svg+xml', $image->getImageType());
    }

    public function testAddSvgImageWithStyles(): void
    {
        $svgPath = self::SVG_PATH;
        if (!file_exists($svgPath)) {
            self::markTestSkipped('SVG file not found: ' . $svgPath);
        }
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $options = [
            'width' => 200,
            'height' => 200,
            'wrappingStyle' => Image::WRAPPING_STYLE_BEHIND,
        ];

        $image = $section->addImage($svgPath, $options);

        self::assertSame(200, $image->getStyle()->getWidth());
        self::assertSame(200, $image->getStyle()->getHeight());
        self::assertSame(Image::WRAPPING_STYLE_BEHIND, $image->getStyle()->getWrappingStyle());
    }

    public function testNonExistentSvg(): void
    {
        $this->expectException(InvalidImageException::class);
        $this->expectExceptionMessage('Impossible de lire');
        $element = new ImageElement('xyz.svg');
    }

    public function testInvalidSvg(): void
    {
        $this->expectException(InvalidImageException::class);
        $this->expectExceptionMessage('SVG invalide');
        $element = new ImageElement(__DIR__ . '/invalid.svg');
    }

    public function testNoWidthSvg(): void
    {
        $this->expectException(InvalidImageException::class);
        $this->expectExceptionMessage('width/height ou viewBox');
        $element = new ImageElement(__DIR__ . '/nowidth.svg');
    }
}
