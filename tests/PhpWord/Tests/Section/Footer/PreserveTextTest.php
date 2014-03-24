<?php
namespace PhpOffice\PhpWord\Tests\Section\Footer;

use PhpOffice\PhpWord\Section\Footer\PreserveText;

class PreserveTextTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $oPreserveText = new PreserveText();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Footer\\PreserveText', $oPreserveText);
        $this->assertEquals($oPreserveText->getText(), null);
        $this->assertEquals($oPreserveText->getFontStyle(), null);
        $this->assertEquals($oPreserveText->getParagraphStyle(), null);
    }

    public function testConstructWithString()
    {
        $oPreserveText = new PreserveText('text', 'styleFont', 'styleParagraph');
        $this->assertEquals($oPreserveText->getText(), array('text'));
        $this->assertEquals($oPreserveText->getFontStyle(), 'styleFont');
        $this->assertEquals($oPreserveText->getParagraphStyle(), 'styleParagraph');
    }

    public function testConstructWithArray()
    {
        $oPreserveText = new PreserveText(
            'text',
            array('align' => 'center'),
            array('marginLeft' => 600, 'marginRight' => 600, 'marginTop' => 600, 'marginBottom' => 600)
        );
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $oPreserveText->getFontStyle());
        $this->assertInstanceOf(
            'PhpOffice\\PhpWord\\Style\\Paragraph',
            $oPreserveText->getParagraphStyle()
        );
    }
}
