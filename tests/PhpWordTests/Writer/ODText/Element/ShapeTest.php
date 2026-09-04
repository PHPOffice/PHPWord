<?php

namespace PhpOffice\PhpWordTests\Writer\ODText\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWordTests\TestHelperDOCX;

class ShapeTest extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testBasicShapesUseNativeOdfElementsAndGraphicStyles(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addShape('rect', [
            'frame' => ['width' => 100, 'height' => 50, 'left' => 4, 'top' => 6],
            'fill' => ['color' => '#ffcc33'],
            'outline' => ['color' => '#990000', 'weight' => 2],
        ]);
        $section->addShape('oval', ['frame' => ['width' => 80, 'height' => 40]]);
        $section->addShape('polyline', ['points' => '1,2 3,4']);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $root = '/office:document-content/office:body/office:text/text:section';

        $rect = $root . '/draw:rect[1]';
        self::assertTrue($doc->elementExists($rect));
        self::assertEquals(Converter::pointToCm(100) . 'cm', $doc->getElementAttribute($rect, 'svg:width'));
        self::assertEquals(Converter::pointToCm(50) . 'cm', $doc->getElementAttribute($rect, 'svg:height'));
        self::assertEquals(Converter::pointToCm(4) . 'cm', $doc->getElementAttribute($rect, 'svg:x'));
        self::assertEquals(Converter::pointToCm(6) . 'cm', $doc->getElementAttribute($rect, 'svg:y'));

        $styleName = $doc->getElementAttribute($rect, 'draw:style-name');
        $style = "/office:document-content/office:automatic-styles/style:style[@style:name='{$styleName}']/style:graphic-properties";
        self::assertEquals('solid', $doc->getElementAttribute($style, 'draw:fill'));
        self::assertEquals('#ffcc33', $doc->getElementAttribute($style, 'draw:fill-color'));
        self::assertEquals('solid', $doc->getElementAttribute($style, 'draw:stroke'));
        self::assertEquals('#990000', $doc->getElementAttribute($style, 'svg:stroke-color'));
        self::assertEquals('2pt', $doc->getElementAttribute($style, 'svg:stroke-width'));

        self::assertTrue($doc->elementExists($root . '/draw:ellipse[1]'));
        $polyline = $root . '/draw:polyline[1]';
        self::assertEquals('1,2 3,4', $doc->getElementAttribute($polyline, 'draw:points'));
    }
}
