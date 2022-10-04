<?php
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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\Style;

use PhpOffice\PhpWord\Style\Line;

/**
 * Test class for PhpOffice\PhpWord\Style\Image.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Image
 *
 * @runTestsInSeparateProcesses
 */
class LineTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test setting style with normal value.
     */
    public function testSetGetNormal(): void
    {
        $object = new Line();

        $properties = [
            'connectorType' => \PhpOffice\PhpWord\Style\Line::CONNECTOR_TYPE_STRAIGHT,
            'beginArrow' => \PhpOffice\PhpWord\Style\Line::ARROW_STYLE_BLOCK,
            'endArrow' => \PhpOffice\PhpWord\Style\Line::ARROW_STYLE_OVAL,
            'dash' => \PhpOffice\PhpWord\Style\Line::DASH_STYLE_LONG_DASH_DOT_DOT,
            'weight' => 10,
            'color' => 'red',
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
        $object = new Line();

        $properties = [
            'connectorType' => \PhpOffice\PhpWord\Style\Line::CONNECTOR_TYPE_STRAIGHT,
            'beginArrow' => \PhpOffice\PhpWord\Style\Line::ARROW_STYLE_BLOCK,
            'endArrow' => \PhpOffice\PhpWord\Style\Line::ARROW_STYLE_OVAL,
            'dash' => \PhpOffice\PhpWord\Style\Line::DASH_STYLE_LONG_DASH_DOT_DOT,
            'weight' => 10,
            'color' => 'red',
        ];
        foreach ($properties as $key => $value) {
            $get = "get{$key}";
            $object->setStyleValue("{$key}", $value);
            self::assertEquals($value, $object->$get());
        }
    }

    /**
     * Test set/get flip.
     */
    public function testSetGetFlip(): void
    {
        $expected = true;
        $object = new Line();
        $object->setFlip($expected);
        self::assertEquals($expected, $object->isFlip());
    }

    /**
     * Test set/get connectorType.
     */
    public function testSetGetConnectorType(): void
    {
        $expected = \PhpOffice\PhpWord\Style\Line::CONNECTOR_TYPE_STRAIGHT;
        $object = new Line();
        $object->setConnectorType($expected);
        self::assertEquals($expected, $object->getConnectorType());
    }

    /**
     * Test set/get weight.
     */
    public function testSetGetWeight(): void
    {
        $expected = 10;
        $object = new Line();
        $object->setWeight($expected);
        self::assertEquals($expected, $object->getWeight());
    }

    /**
     * Test set/get color.
     */
    public function testSetGetColor(): void
    {
        $expected = 'red';
        $object = new Line();
        $object->setColor($expected);
        self::assertEquals($expected, $object->getColor());
    }

    /**
     * Test set/get dash.
     */
    public function testSetGetDash(): void
    {
        $expected = \PhpOffice\PhpWord\Style\Line::DASH_STYLE_LONG_DASH_DOT_DOT;
        $object = new Line();
        $object->setDash($expected);
        self::assertEquals($expected, $object->getDash());
    }

    /**
     * Test set/get beginArrow.
     */
    public function testSetGetBeginArrow(): void
    {
        $expected = \PhpOffice\PhpWord\Style\Line::ARROW_STYLE_BLOCK;
        $object = new Line();
        $object->setBeginArrow($expected);
        self::assertEquals($expected, $object->getBeginArrow());
    }

    /**
     * Test set/get endArrow.
     */
    public function testSetGetEndArrow(): void
    {
        $expected = \PhpOffice\PhpWord\Style\Line::ARROW_STYLE_CLASSIC;
        $object = new Line();
        $object->setEndArrow($expected);
        self::assertEquals($expected, $object->getEndArrow());
    }
}
