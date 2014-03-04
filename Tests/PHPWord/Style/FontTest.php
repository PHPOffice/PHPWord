<?php
namespace PHPWord\Tests\Style;

use PHPUnit_Framework_TestCase;
use PHPWord;
use PHPWord\Tests\TestHelperDOCX;

/**
 * Class PHPWord_Writer_Word2007_BaseTest
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class FontTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    public function testLineHeight()
    {
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();

        // Test style array
        $text = $section->addText('This is a test', array(
            'line-height' => 2.0
        ));

        $doc = TestHelperDOCX::getDocument($PHPWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:spacing');

        $lineHeight = $element->getAttribute('w:line');
        $lineRule = $element->getAttribute('w:lineRule');

        $this->assertEquals(480, $lineHeight);
        $this->assertEquals('auto', $lineRule);

        // Test setter
        $text->getFontStyle()->setLineHeight(3.0);
        $doc = TestHelperDOCX::getDocument($PHPWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:spacing');

        $lineHeight = $element->getAttribute('w:line');
        $lineRule = $element->getAttribute('w:lineRule');

        $this->assertEquals(720, $lineHeight);
        $this->assertEquals('auto', $lineRule);
    }
}