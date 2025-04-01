<?php

namespace PhpWordTests\Writer\WPS;

use PhpOffice\PhpWord\Writer\WPS\Media;
use PHPUnit\Framework\TestCase;

class MediaTest extends TestCase
{
    protected function setUp(): void
    {
        // Clear media elements before each test
        Media::clearElements();
    }

    public function testGetElements(): void
    {
        // Test empty elements
        $elements = Media::getElements('section');
        self::assertIsArray($elements);
        self::assertEmpty($elements);

        // Test all elements
        $allElements = ['section', 'header', 'footer'];
        foreach ($allElements as $container) {
            $elements = Media::getElements($container);
            self::assertIsArray($elements);
        }
    }

    public function testAddElement(): void
    {
        // Add section media
        Media::addElement('section', 'image', __DIR__ . '/../../_files/images/earth.jpg');
        $elements = Media::getElements('section');
        self::assertCount(1, $elements);
        self::assertEquals('section', $elements[0]['target']);
        self::assertEquals(__DIR__ . '/../../_files/images/earth.jpg', $elements[0]['source']);

        // Add header media
        Media::addElement('header', 'image', __DIR__ . '/../../_files/images/earth.jpg');
        $headerElements = Media::getElements('header');
        self::assertCount(1, $headerElements);

        // Add footer media
        Media::addElement('footer', 'image', __DIR__ . '/../../_files/images/earth.jpg');
        $footerElements = Media::getElements('footer');
        self::assertCount(1, $footerElements);

        // Test invalid container type
        Media::addElement('invalid', 'image', __DIR__ . '/../../_files/images/earth.jpg');
        $allElements = ['section', 'header', 'footer'];
        foreach ($allElements as $container) {
            $elements = Media::getElements($container);
            self::assertCount(1, $elements);
        }
    }

    public function testClearElements(): void
    {
        // Add some elements
        Media::addElement('section', 'image', __DIR__ . '/../../_files/images/earth.jpg');
        Media::addElement('header', 'image', __DIR__ . '/../../_files/images/earth.jpg');

        // Verify elements exist
        self::assertNotEmpty(Media::getElements('section'));
        self::assertNotEmpty(Media::getElements('header'));

        // Clear elements
        Media::clearElements();

        // Verify elements are cleared
        $allElements = ['section', 'header', 'footer'];
        foreach ($allElements as $container) {
            $elements = Media::getElements($container);
            self::assertIsArray($elements);
            self::assertEmpty($elements);
        }
    }

    public function testGetElementsWithUndefinedType(): void
    {
        // Test with undefined type
        $elements = Media::getElements('undefined');
        self::assertIsArray($elements);
        self::assertEmpty($elements);
    }
}
