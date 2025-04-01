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
        $this->assertIsArray($elements);
        $this->assertEmpty($elements);
        
        // Test all elements
        $allElements = Media::getElements(null);
        $this->assertIsArray($allElements);
        $this->assertArrayHasKey('section', $allElements);
        $this->assertArrayHasKey('header', $allElements);
        $this->assertArrayHasKey('footer', $allElements);
    }

    public function testAddElement(): void
    {
        // Add section media
        Media::addElement('section', 'image', __DIR__ . '/../../_files/images/earth.jpg');
        $elements = Media::getElements('section');
        $this->assertCount(1, $elements);
        $this->assertEquals('section', $elements[0]['target']);
        $this->assertEquals(__DIR__ . '/../../_files/images/earth.jpg', $elements[0]['source']);
        
        // Add header media
        Media::addElement('header', 'image', __DIR__ . '/../../_files/images/earth.jpg');
        $headerElements = Media::getElements('header');
        $this->assertCount(1, $headerElements);
        
        // Add footer media
        Media::addElement('footer', 'image', __DIR__ . '/../../_files/images/earth.jpg');
        $footerElements = Media::getElements('footer');
        $this->assertCount(1, $footerElements);
        
        // Test invalid container type
        Media::addElement('invalid', 'image', __DIR__ . '/../../_files/images/earth.jpg');
        $allElements = Media::getElements(null);
        $this->assertCount(1, $allElements['section']);
        $this->assertCount(1, $allElements['header']);
        $this->assertCount(1, $allElements['footer']);
    }
    
    public function testClearElements(): void
    {
        // Add some elements
        Media::addElement('section', 'image', __DIR__ . '/../../_files/images/earth.jpg');
        Media::addElement('header', 'image', __DIR__ . '/../../_files/images/earth.jpg');
        
        // Verify elements exist
        $this->assertNotEmpty(Media::getElements('section'));
        $this->assertNotEmpty(Media::getElements('header'));
        
        // Clear elements
        Media::clearElements();
        
        // Verify elements are cleared
        $this->assertEmpty(Media::getElements('section'));
        $this->assertEmpty(Media::getElements('header'));
        $this->assertEmpty(Media::getElements('footer'));
    }
    
    public function testGetElementsWithUndefinedType(): void
    {
        // Test with undefined type
        $elements = Media::getElements('undefined');
        $this->assertIsArray($elements);
        $this->assertEmpty($elements);
    }
}