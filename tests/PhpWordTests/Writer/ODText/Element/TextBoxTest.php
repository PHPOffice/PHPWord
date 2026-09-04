<?php

namespace PhpOffice\PhpWordTests\Writer\ODText\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWordTests\TestHelperDOCX;

class TextBoxTest extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testTextBoxContainsNativeRichTextContentAndGraphicProperties(): void
    {
        $phpWord = new PhpWord();
        $textBox = $phpWord->addSection()->addTextBox([
            'width' => 200,
            'height' => 100,
            'bgColor' => '#eeeeee',
            'borderSize' => 1,
            'borderColor' => '#333333',
            'innerMargin' => 100,
        ]);
        $textBox->addText('Plain text');
        $run = $textBox->addTextRun();
        $run->addText('Bold text', ['bold' => true]);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $root = '/office:document-content/office:body/office:text/text:section';
        $frame = $root . '/draw:frame[1]';

        self::assertTrue($doc->elementExists($frame . '/draw:text-box/text:p[1]'));
        self::assertEquals('Plain text', $doc->getElement($frame . '/draw:text-box/text:p[1]/text:span')->textContent);
        self::assertEquals('Bold text', $doc->getElement($frame . '/draw:text-box/text:p[2]/text:span')->textContent);

        $styleName = $doc->getElementAttribute($frame, 'draw:style-name');
        $properties = "/office:document-content/office:automatic-styles/style:style[@style:name='{$styleName}']/style:graphic-properties";
        self::assertEquals('solid', $doc->getElementAttribute($properties, 'draw:fill'));
        self::assertEquals('#eeeeee', $doc->getElementAttribute($properties, 'draw:fill-color'));
        self::assertEquals('#333333', $doc->getElementAttribute($properties, 'svg:stroke-color'));
        self::assertNotEmpty($doc->getElementAttribute($properties, 'fo:padding'));
    }
}
