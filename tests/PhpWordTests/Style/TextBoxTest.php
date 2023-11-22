<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\Style;

use InvalidArgumentException;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\TextBox;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PhpOffice\PhpWord\Style\Image.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Image
 *
 * @runTestsInSeparateProcesses
 */
class TextBoxTest extends TestCase
{
    /**
     * Test setting style with normal value.
     */
    public function testSetGetNormal(): void
    {
        $object = new TextBox();

        $properties = [
            'width' => 200,
            'height' => 200,
            'alignment' => Jc::START,
            'marginTop' => 240,
            'marginLeft' => 240,
            'wrappingStyle' => 'inline',
            'positioning' => 'absolute',
            'posHorizontal' => 'center',
            'posVertical' => 'top',
            'posHorizontalRel' => 'margin',
            'posVerticalRel' => 'page',
            'innerMarginTop' => '5',
            'innerMarginRight' => '5',
            'innerMarginBottom' => '5',
            'innerMarginLeft' => '5',
            'borderSize' => '2',
            'borderColor' => 'red',
            'bgColor' => 'blue',
        ];
        foreach ($properties as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            self::assertEquals($value, $object->$get());
        }
    }

    /**
     * Test setStyleValue method.
     */
    public function testSetStyleValue(): void
    {
        $object = new TextBox();

        $properties = [
            'width' => 200,
            'height' => 200,
            'alignment' => Jc::START,
            'marginTop' => 240,
            'marginLeft' => 240,
            'wrappingStyle' => 'inline',
            'positioning' => 'absolute',
            'posHorizontal' => 'center',
            'posVertical' => 'top',
            'posHorizontalRel' => 'margin',
            'posVerticalRel' => 'page',
            'innerMarginTop' => '5',
            'innerMarginRight' => '5',
            'innerMarginBottom' => '5',
            'innerMarginLeft' => '5',
            'borderSize' => '2',
            'borderColor' => 'red',
            'bgColor' => 'blue',
        ];
        foreach ($properties as $key => $value) {
            $get = "get{$key}";
            $object->setStyleValue("{$key}", $value);
            self::assertEquals($value, $object->$get());
        }
    }

    /**
     * Test setWrappingStyle exception.
     */
    public function testSetWrappingStyleException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $object = new TextBox();
        $object->setWrappingStyle('foo');
    }

    /**
     * Test set/get width.
     */
    public function testSetGetWidth(): void
    {
        $expected = 200;
        $object = new TextBox();
        $object->setWidth($expected);
        self::assertEquals($expected, $object->getWidth());
    }

    /**
     * Test set/get height.
     */
    public function testSetGetHeight(): void
    {
        $expected = 200;
        $object = new TextBox();
        $object->setHeight($expected);
        self::assertEquals($expected, $object->getHeight());
    }

    /**
     * Test set/get height.
     */
    public function testSetGetAlign(): void
    {
        $textBox = new TextBox();

        $expectedAlignment = Jc::START;
        $textBox->setAlignment($expectedAlignment);
        self::assertEquals($expectedAlignment, $textBox->getAlignment());
    }

    /**
     * Test set/get marginTop.
     */
    public function testSetGetMarginTop(): void
    {
        $expected = 5;
        $object = new TextBox();
        $object->setMarginTop($expected);
        self::assertEquals($expected, $object->getMarginTop());
    }

    /**
     * Test set/get marginLeft.
     */
    public function testSetGetMarginLeft(): void
    {
        $expected = 5;
        $object = new TextBox();
        $object->setMarginLeft($expected);
        self::assertEquals($expected, $object->getMarginLeft());
    }

    /**
     * Test set/get innerMarginTop.
     */
    public function testSetGetInnerMarginTop(): void
    {
        $expected = 5;
        $object = new TextBox();
        $object->setInnerMarginTop($expected);
        self::assertEquals($expected, $object->getInnerMarginTop());
    }

    /**
     * Test set/get wrappingStyle.
     */
    public function testSetGetWrappingStyle(): void
    {
        $expected = 'inline';
        $object = new TextBox();
        $object->setWrappingStyle($expected);
        self::assertEquals($expected, $object->getWrappingStyle());
    }

    /**
     * Test set/get positioning.
     */
    public function testSetGetPositioning(): void
    {
        $expected = 'absolute';
        $object = new TextBox();
        $object->setPositioning($expected);
        self::assertEquals($expected, $object->getPositioning());
    }

    /**
     * Test set/get posHorizontal.
     */
    public function testSetGetPosHorizontal(): void
    {
        $expected = 'center';
        $object = new TextBox();
        $object->setPosHorizontal($expected);
        self::assertEquals($expected, $object->getPosHorizontal());
    }

    /**
     * Test set/get posVertical.
     */
    public function testSetGetPosVertical(): void
    {
        $expected = 'top';
        $object = new TextBox();
        $object->setPosVertical($expected);
        self::assertEquals($expected, $object->getPosVertical());
    }

    /**
     * Test set/get posHorizontalRel.
     */
    public function testSetGetPosHorizontalRel(): void
    {
        $expected = 'margin';
        $object = new TextBox();
        $object->setPosHorizontalRel($expected);
        self::assertEquals($expected, $object->getPosHorizontalRel());
    }

    /**
     * Test set/get posVerticalRel.
     */
    public function testSetGetPosVerticalRel(): void
    {
        $expected = 'page';
        $object = new TextBox();
        $object->setPosVerticalRel($expected);
        self::assertEquals($expected, $object->getPosVerticalRel());
    }

    /**
     * Test set/get innerMarginRight.
     */
    public function testSetGetInnerMarginRight(): void
    {
        $expected = 5;
        $object = new TextBox();
        $object->setInnerMarginRight($expected);
        self::assertEquals($expected, $object->getInnerMarginRight());
    }

    /**
     * Test set/get innerMarginBottom.
     */
    public function testSetGetInnerMarginBottom(): void
    {
        $expected = 5;
        $object = new TextBox();
        $object->setInnerMarginBottom($expected);
        self::assertEquals($expected, $object->getInnerMarginBottom());
    }

    /**
     * Test set/get innerMarginLeft.
     */
    public function testSetGetInnerMarginLeft(): void
    {
        $expected = 5;
        $object = new TextBox();
        $object->setInnerMarginLeft($expected);
        self::assertEquals($expected, $object->getInnerMarginLeft());
    }

    /**
     * Test set/get innerMarginLeft.
     */
    public function testSetGetInnerMargin(): void
    {
        $expected = 5;
        $object = new TextBox();
        $object->setInnerMargin($expected);
        self::assertEquals([$expected, $expected, $expected, $expected], $object->getInnerMargin());
    }

    /**
     * Test set/get borderSize.
     */
    public function testSetGetBorderSize(): void
    {
        $expected = 2;
        $object = new TextBox();
        $object->setBorderSize($expected);
        self::assertEquals($expected, $object->getBorderSize());
    }

    /**
     * Test set/get borderColor.
     */
    public function testSetGetBorderColor(): void
    {
        $expected = 'red';
        $object = new TextBox();
        $object->setBorderColor($expected);
        self::assertEquals($expected, $object->getBorderColor());
    }

    /**
     * Test set/get bgColor.
     */
    public function testSetGetBgColor(): void
    {
        $expected = 'blue';
        $object = new TextBox();
        $object->setBgColor($expected);
        self::assertEquals($expected, $object->getBgColor());
    }
}
