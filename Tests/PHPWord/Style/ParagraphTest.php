<?php
namespace PHPWord\Tests\Style;

use PHPWord;
use PHPWord_Style_Paragraph;
use PHPWord_Style_Tab;
use PHPWord\Tests\TestHelperDOCX;

/**
 * Class ParagraphTest
 *
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class ParagraphTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test setting style values with null or empty value
     */
    public function testSetStyleValueWithNullOrEmpty()
    {
        $object = new PHPWord_Style_Paragraph();

        $attributes = array(
            'tabs' => null,
            'widowControl' => true,
            'keepNext' => false,
            'keepLines' => false,
            'pageBreakBefore' => false,
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
        $object = new PHPWord_Style_Paragraph();

        $attributes = array(
            'align' => 'justify',
            'spaceAfter' => 240,
            'spaceBefore' => 240,
            'indent' => 1,
            'hanging' => 1,
            'spacing' => 120,
            'basedOn' => 'Normal',
            'next' => 'Normal',
            'widowControl' => false,
            'keepNext' => true,
            'keepLines' => true,
            'pageBreakBefore' => true,
        );
        foreach ($attributes as $key => $value) {
            $get = "get{$key}";
            $object->setStyleValue("_$key", $value);
            if ($key == 'align') {
                if ($value == 'justify') {
                    $value = 'both';
                }
            } elseif ($key == 'indent' || $key == 'hanging') {
                $value = $value * 720;
            } elseif ($key == 'spacing') {
                $value += 240;
            }
            $this->assertEquals($value, $object->$get());
        }
    }

    /**
     * Test tabs
     */
    public function testTabs()
    {
        $object = new PHPWord_Style_Paragraph();
        $object->setTabs(array(
            new PHPWord_Style_Tab('left', 1550),
            new PHPWord_Style_Tab('right', 5300),
        ));
        $this->assertInstanceOf('PHPWord_Style_Tabs', $object->getTabs());
    }

    public function testLineHeight()
    {
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();

        // Test style array
        $text = $section->addText('This is a test', array(), array(
            'line-height' => 2.0
        ));

        $doc = TestHelperDOCX::getDocument($PHPWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:spacing');

        $lineHeight = $element->getAttribute('w:line');
        $lineRule = $element->getAttribute('w:lineRule');

        $this->assertEquals(480, $lineHeight);
        $this->assertEquals('auto', $lineRule);

        // Test setter
        $text->getParagraphStyle()->setLineHeight(3.0);
        $doc = TestHelperDOCX::getDocument($PHPWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:spacing');

        $lineHeight = $element->getAttribute('w:line');
        $lineRule = $element->getAttribute('w:lineRule');

        $this->assertEquals(720, $lineHeight);
        $this->assertEquals('auto', $lineRule);
    }

    /**
     * Test setLineHeight validation
     */
    public function testLineHeightValidation()
    {
        $object = new PHPWord_Style_Paragraph();
        $object->setLineHeight('12.5pt');
        $this->assertEquals(12.5, $object->getLineHeight());
    }
}
