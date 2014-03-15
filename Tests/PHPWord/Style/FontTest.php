<?php
namespace PHPWord\Tests\Style;

use PHPWord;
use PHPWord_Style_Font;
use PHPWord\Tests\TestHelperDOCX;

/**
 * Class FontTest
 *
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class FontTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test initiation for style type and paragraph style
     */
    public function testInitiation()
    {
        $object = new PHPWord_Style_Font('text', array('align' => 'both'));

        $this->assertEquals('text', $object->getStyleType());
        $this->assertInstanceOf('PHPWord_Style_Paragraph', $object->getParagraphStyle());
    }

    /**
     * Test setting style values with null or empty value
     */
    public function testSetStyleValueWithNullOrEmpty()
    {
        $object = new PHPWord_Style_Font();

        $attributes = array(
            'name' => PHPWord::DEFAULT_FONT_NAME,
            'size' => PHPWord::DEFAULT_FONT_SIZE,
            'bold' => false,
            'italic' => false,
            'superScript' => false,
            'subScript' => false,
            'underline' => PHPWord_Style_Font::UNDERLINE_NONE,
            'strikethrough' => false,
            'color' => PHPWord::DEFAULT_FONT_COLOR,
            'fgColor' => null,
        );
        foreach ($attributes as $key => $default) {
            $get = "get{$key}";
            $object->setStyleValue("_$key", null);
            $this->assertEquals($default, $object->$get());
            $object->setStyleValue("_$key", '');
            $this->assertEquals($default, $object->$get());
        }
    }

    /**
     * Test setting style values with normal value
     */
    public function testSetStyleValueNormal()
    {
        $object = new PHPWord_Style_Font();

        $attributes = array(
            'name' => 'Times New Roman',
            'size' => 9,
            'bold' => true,
            'italic' => true,
            'superScript' => true,
            'subScript' => true,
            'underline' => PHPWord_Style_Font::UNDERLINE_HEAVY,
            'strikethrough' => true,
            'color' => '999999',
            'fgColor' => '999999',
        );
        foreach ($attributes as $key => $value) {
            $get = "get{$key}";
            $object->setStyleValue("_$key", $value);
            $this->assertEquals($value, $object->$get());
        }
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
