<?php
namespace PhpOffice\PhpWord\Tests\Section;

use PhpOffice\PhpWord\Section\Text;

class TextTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $oText = new Text();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Text', $oText);
        $this->assertEquals(null, $oText->getText());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oText->getFontStyle());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oText->getParagraphStyle());
    }

    public function testText()
    {
        $oText = new Text('text');

        $this->assertEquals($oText->getText(), 'text');
    }

    public function testFont()
    {
        $oText = new Text('text', 'fontStyle');
        $this->assertEquals($oText->getFontStyle(), 'fontStyle');

        $oText->setFontStyle(array('bold' => true, 'italic' => true, 'size' => 16));
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oText->getFontStyle());
    }

    public function testParagraph()
    {
        $oText = new Text('text', 'fontStyle', 'paragraphStyle');
        $this->assertEquals($oText->getParagraphStyle(), 'paragraphStyle');

        $oText->setParagraphStyle(array('align' => 'center', 'spaceAfter' => 100));
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oText->getParagraphStyle());
    }
}
