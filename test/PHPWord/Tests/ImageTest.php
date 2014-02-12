<?php
namespace PHPWord\Tests;

use PHPUnit_Framework_TestCase;
use PHPWord;

class ImageTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        TestHelper::clear();
    }

    public function testImageWrappingStyleBehind()
    {
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();

        $section->addImage(
            __DIR__ . '/_files/images/earth.jpg',
            array(
                'marginTop' => -1,
                'marginLeft' => -1,
                'wrappingStyle' => 'behind'
            )
        );

        $doc = TestHelper::getDocument($PHPWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:r/w:pict/v:shape');

        $style = $element->getAttribute('style');

        $this->assertRegExp('/z\-index:\-[0-9]*/', $style);
        $this->assertRegExp('/position:absolute;/', $style);
    }
}