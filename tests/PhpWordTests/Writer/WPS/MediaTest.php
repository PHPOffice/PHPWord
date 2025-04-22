<?php

namespace PhpWordTests\Writer\WPS;

use PhpOffice\PhpWord\Element\Image; // Add import
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
        $imagePath = 'tests/PhpWordTests/_files/images/earth.jpg';
        $imageElement = new Image($imagePath);

        // Add section media
        Media::addElement('section', $imageElement);
        $elements = Media::getElements('section');
        self::assertCount(1, $elements);
        self::assertEquals('section', $elements[0]['target']);
        self::assertEquals($imagePath, $elements[0]['source']);
        self::assertStringStartsWith('image1.', $elements[0]['target']);

        // Add header media
        $headerImageElement = new Image($imagePath);
        Media::addElement('header', $headerImageElement);
        $headerElements = Media::getElements('header');
        self::assertCount(1, $headerElements);
        self::assertEquals('header', $headerElements[0]['target']);
        self::assertEquals($imagePath, $headerElements[0]['source']);
        self::assertStringStartsWith('image1.', $headerElements[0]['target']);

        // Add footer media
        $footerImageElement = new Image($imagePath);
        Media::addElement('footer', $footerImageElement);
        $footerElements = Media::getElements('footer');
        self::assertCount(1, $footerElements);
        self::assertEquals('footer', $footerElements[0]['target']);
        self::assertEquals($imagePath, $footerElements[0]['source']);
        self::assertStringStartsWith('image1.', $footerElements[0]['target']);

        // Test invalid container type - Note: addElement doesn't validate docPart string anymore,
        // it just creates a new key in the $elements array.
        // This part of the test might need reconsideration based on desired behavior.
        $invalidImageElement = new Image($imagePath);
        Media::addElement('invalid', $invalidImageElement);
        $invalidElements = Media::getElements('invalid');
        self::assertCount(1, $invalidElements); // Element is added under 'invalid' key

        // Check counts for valid types remain unchanged by the 'invalid' add
        $allValidElements = ['section', 'header', 'footer'];
        foreach ($allValidElements as $container) {
            $elements = Media::getElements($container);
            self::assertCount(1, $elements);
        }
    }

    public function testClearElements(): void
    {
        $imagePath = 'tests/PhpWordTests/_files/images/earth.jpg';
        $sectionImage = new Image($imagePath);
        $headerImage = new Image($imagePath);

        // Add some elements
        Media::addElement('section', $sectionImage); // Pass Image element
        Media::addElement('header', $headerImage); // Pass Image element

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
