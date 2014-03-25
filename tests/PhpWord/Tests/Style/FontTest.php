<?php
namespace PhpOffice\PhpWord\Tests\Style;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;

/**
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
        $object = new Font('text', array('align' => 'both'));

        $this->assertEquals('text', $object->getStyleType());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $object->getParagraphStyle());
    }

    /**
     * Test setting style values with null or empty value
     */
    public function testSetStyleValueWithNullOrEmpty()
    {
        $object = new Font();

        $attributes = array(
            'name' => PhpWord::DEFAULT_FONT_NAME,
            'size' => PhpWord::DEFAULT_FONT_SIZE,
            'bold' => false,
            'italic' => false,
            'superScript' => false,
            'subScript' => false,
            'underline' => Font::UNDERLINE_NONE,
            'strikethrough' => false,
            'color' => PhpWord::DEFAULT_FONT_COLOR,
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
        $object = new Font();

        $attributes = array(
            'name' => 'Times New Roman',
            'size' => 9,
            'bold' => true,
            'italic' => true,
            'superScript' => true,
            'subScript' => true,
            'underline' => Font::UNDERLINE_HEAVY,
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
        $phpWord = new PhpWord();
        $section = $phpWord->createSection();

        // Test style array
        $text = $section->addText('This is a test', array(
            'line-height' => 2.0
        ));

        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:spacing');

        $lineHeight = $element->getAttribute('w:line');
        $lineRule = $element->getAttribute('w:lineRule');

        $this->assertEquals(480, $lineHeight);
        $this->assertEquals('auto', $lineRule);

        // Test setter
        $text->getFontStyle()->setLineHeight(3.0);
        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:spacing');

        $lineHeight = $element->getAttribute('w:line');
        $lineRule = $element->getAttribute('w:lineRule');

        $this->assertEquals(720, $lineHeight);
        $this->assertEquals('auto', $lineRule);
    }
}
