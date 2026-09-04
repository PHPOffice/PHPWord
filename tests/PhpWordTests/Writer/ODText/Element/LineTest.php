<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @license http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\Writer\ODText\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\ODText\Element\Line.
 */
class LineTest extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testLineIsWrittenAsNativeOdfDrawing(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addLine([
            'width' => 100,
            'height' => 20,
            'marginLeft' => 10,
            'marginTop' => 5,
            'color' => '336699',
            'weight' => 2,
        ]);
        $section->addLine([
            'width' => 40,
            'height' => 0,
            'color' => '993300',
            'weight' => 1,
        ]);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $line = '/office:document-content/office:body/office:text/text:section/text:p[2]/draw:line';

        self::assertTrue($doc->elementExists($line));
        self::assertEquals(Converter::pointToCm(10) . 'cm', $doc->getElementAttribute($line, 'svg:x1'));
        self::assertEquals(Converter::pointToCm(5) . 'cm', $doc->getElementAttribute($line, 'svg:y1'));
        self::assertEquals(Converter::pointToCm(110) . 'cm', $doc->getElementAttribute($line, 'svg:x2'));
        self::assertEquals(Converter::pointToCm(25) . 'cm', $doc->getElementAttribute($line, 'svg:y2'));

        $styleName = $doc->getElementAttribute($line, 'draw:style-name');
        self::assertNotEmpty($styleName);
        $style = "/office:document-content/office:automatic-styles/style:style[@style:name='{$styleName}']";
        self::assertEquals('graphic', $doc->getElementAttribute($style, 'style:family'));
        self::assertEquals('solid', $doc->getElementAttribute($style . '/style:graphic-properties', 'draw:stroke'));
        self::assertEquals('#336699', $doc->getElementAttribute($style . '/style:graphic-properties', 'svg:stroke-color'));
        self::assertEquals('2pt', $doc->getElementAttribute($style . '/style:graphic-properties', 'svg:stroke-width'));

        $secondLine = '/office:document-content/office:body/office:text/text:section/text:p[3]/draw:line';
        $secondStyleName = $doc->getElementAttribute($secondLine, 'draw:style-name');
        self::assertNotSame($styleName, $secondStyleName);
        $secondStyle = "/office:document-content/office:automatic-styles/style:style[@style:name='{$secondStyleName}']";
        self::assertEquals('#993300', $doc->getElementAttribute($secondStyle . '/style:graphic-properties', 'svg:stroke-color'));
        self::assertEquals('1pt', $doc->getElementAttribute($secondStyle . '/style:graphic-properties', 'svg:stroke-width'));
    }

    public function testDefaultLineIsWritten(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addSection()->addLine();

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        self::assertTrue($doc->elementExists('/office:document-content/office:body/office:text/text:section/text:p[2]/draw:line'));
    }
}
