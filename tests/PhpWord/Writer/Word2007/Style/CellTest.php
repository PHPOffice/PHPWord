<?php
declare(strict_types=1);
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use PhpOffice\PhpWord\Style\Cell as CellStyle;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Colors\HighlightColor;
use PhpOffice\PhpWord\Style\Colors\Rgb;
use PhpOffice\PhpWord\Style\Colors\SystemColor;
use PhpOffice\PhpWord\Style\Colors\ThemeColor;
use PhpOffice\PhpWord\Style\Lengths\Absolute;
use PhpOffice\PhpWord\Style\Lengths\Auto;
use PhpOffice\PhpWord\Style\Lengths\Length;
use PhpOffice\PhpWord\Style\Lengths\Percent;
use PhpOffice\PhpWord\Style\Table;
use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Style\Table
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Style\Table
 * @runTestsInSeparateProcesses
 */
class CellTest extends \PHPUnit\Framework\TestCase
{
    public function testSetWidth()
    {
        $cellStyle = new CellStyle();

        $cellStyle->setWidth(Absolute::from('twip', 10.3));
        $width = $cellStyle->getWidth();
        $this->assertInstanceOf(Absolute::class, $width);
        $this->assertEquals($width->toFloat('twip'), 10.3);

        $cellStyle->setWidth(Absolute::from('twip', 0));
        $width = $cellStyle->getWidth();
        $this->assertInstanceOf(Absolute::class, $width);
        $this->assertEquals($width->toInt('twip'), 0);

        $cellStyle->setWidth(new Percent(10));
        $width = $cellStyle->getWidth();
        $this->assertInstanceOf(Percent::class, $width);
        $this->assertEquals($width->toInt(), 10);

        $cellStyle->setWidth(new Percent(0));
        $width = $cellStyle->getWidth();
        $this->assertInstanceOf(Percent::class, $width);
        $this->assertEquals($width->toInt(), 0);

        $cellStyle->setWidth(new Auto());
        $width = $cellStyle->getWidth();
        $this->assertInstanceOf(Auto::class, $width);
    }

    /**
     * @covers \PhpOffice\PhpWord\Writer\Word2007\Style\Cell
     * @expectedException \Exception
     * @expectedExceptionMessage Unsupported width `class@anonymous
     */
    public function testSetBadWidth()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable(new Table());
        $row = $table->addRow();
        $cellStyle = new CellStyle();
        $cellStyle->setWidth(new class() extends Length {
            public function isSpecified(): bool
            {
                return true;
            }
        });
        $row->addCell(null, $cellStyle);

        TestHelperDOCX::getDocument($phpWord, 'Word2007');
    }

    public function testWidth()
    {
        $widths = array(
            array(Absolute::from('twip', 10.3), 'dxa', 10.3),
            array(Absolute::from('twip', 0), 'nil', null),
            array(new Percent(50), 'pct', '50%'),
            array(new Percent(0), 'nil', null),
            array(new Auto(), 'auto', null),
        );

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable(new Table());
        $row = $table->addRow();
        foreach ($widths as $info) {
            list($width, $expectedType, $expectedWidth) = $info;
            $cellStyle = new CellStyle();
            $cellStyle->setWidth($width);
            $row->addCell(null, $cellStyle);
        }

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $cellId = 0;
        foreach ($widths as $info) {
            $cellId += 1;
            list($width, $expectedType, $expectedWidth) = $info;
            $message = get_class($width) . ' expecting [' . $expectedType . ',  ' . $expectedWidth . '] should be valid';
            $path = '/w:document/w:body/w:tbl/w:tr/w:tc[' . $cellId . ']/w:tcPr/w:tcW';
            $this->assertTrue($doc->elementExists($path), $message);
            $this->assertEquals($expectedType, $doc->getElementAttribute($path, 'w:type'), $message);
            $this->assertEquals($expectedWidth, $doc->getElementAttribute($path, 'w:w'), $message);
        }
    }

    public function testSetBgColor()
    {
        $colors = array(
            new Hex('0a1b2c'),
            new HighlightColor('yellow'),
            new Rgb(134, 230, 9),
            new ThemeColor('dk1'),
        );

        $cellStyle = new CellStyle();

        foreach ($colors as $color) {
            $cellStyle->setBgColor($color);
            $bgColor = $cellStyle->getBgColor();
            $this->assertEquals($color->toHexOrName(), $bgColor->toHexOrName());
        }
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessage Argument 1 passed to PhpOffice\PhpWord\Style\Cell::setBgColor() must be an instance of PhpOffice\PhpWord\Style\Colors\BasicColor, instance of PhpOffice\PhpWord\Style\Colors\SystemColor given
     */
    public function testSetBgColorSystemColor()
    {
        $cellStyle = new CellStyle();
        $cellStyle->setBgColor(new SystemColor('window', new Hex('123456')));
    }

    public function testBgColor()
    {
        $colors = array(
            new Hex('0a1b2c'),
            new HighlightColor('yellow'),
            new Rgb(134, 230, 9),
            new ThemeColor('dk1'),
        );

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable(new Table());
        $row = $table->addRow();
        foreach ($colors as $color) {
            $cellStyle = new CellStyle();
            $cellStyle->setBgColor($color);
            $row->addCell(null, $cellStyle);
        }

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $cellId = 0;
        foreach ($colors as $color) {
            $cellId += 1;
            $message = get_class($color) . '(' . $color->toHexOrName() . ') should be valid';
            $path = '/w:document/w:body/w:tbl/w:tr/w:tc[' . $cellId . ']/w:tcPr/w:shd';
            $this->assertTrue($doc->elementExists($path), $message);
            $this->assertEquals('clear', $doc->getElementAttribute($path, 'w:val'), $message);
            $this->assertEquals($color->toHexOrName(), $doc->getElementAttribute($path, 'w:fill'), $message);
        }
    }
}
