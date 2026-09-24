<?php

namespace PhpOffice\PhpWordTests\Writer\ODText\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWordTests\TestHelperDOCX;

class WatermarkTest extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testWatermarkUsesPageAnchoredHeaderImage(): void
    {
        $phpWord = new PhpWord();
        $header = $phpWord->addSection()->addHeader();
        $header->addWatermark(__DIR__ . '/../../../_files/images/earth.jpg', [
            'width' => 100,
            'height' => 80,
            'marginLeft' => 12,
            'marginTop' => 18,
        ]);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $doc->setDefaultFile('styles.xml');
        $frame = '/office:document-styles/office:master-styles/style:master-page/style:header/text:p/draw:frame';

        self::assertTrue($doc->elementExists($frame));
        self::assertEquals('at-page', $doc->getElementAttribute($frame, 'text:anchor-type'));
        self::assertEquals('0.3175cm', $doc->getElementAttribute($frame, 'svg:x'));
        self::assertEquals('0.47625cm', $doc->getElementAttribute($frame, 'svg:y'));
        self::assertEquals('Pictures/header1_image1.jpg', $doc->getElementAttribute($frame . '/draw:image', 'xlink:href'));
    }
}
