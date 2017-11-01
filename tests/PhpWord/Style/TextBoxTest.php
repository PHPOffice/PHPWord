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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\SimpleType\Jc;

/**
 * Test class for PhpOffice\PhpWord\Style\Image
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Image
 * @runTestsInSeparateProcesses
 */
class TextBoxTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test setting style with normal value
     */
    public function testSetGetNormal()
    {
        $object = new TextBox();

        $properties = array(
            'width'             => 200,
            'height'            => 200,
            'alignment'         => Jc::START,
            'marginTop'         => 240,
            'marginLeft'        => 240,
            'wrappingStyle'     => 'inline',
            'positioning'       => 'absolute',
            'posHorizontal'     => 'center',
            'posVertical'       => 'top',
            'posHorizontalRel'  => 'margin',
            'posVerticalRel'    => 'page',
            'innerMarginTop'    => '5',
            'innerMarginRight'  => '5',
            'innerMarginBottom' => '5',
            'innerMarginLeft'   => '5',
            'borderSize'        => '2',
            'borderColor'       => 'red',
        );
        foreach ($properties as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            $this->assertEquals($value, $object->$get());
        }
    }

    /**
     * Test setStyleValue method
     */
    public function testSetStyleValue()
    {
        $object = new TextBox();

        $properties = array(
            'width'             => 200,
            'height'            => 200,
            'alignment'         => Jc::START,
            'marginTop'         => 240,
            'marginLeft'        => 240,
            'wrappingStyle'     => 'inline',
            'positioning'       => 'absolute',
            'posHorizontal'     => 'center',
            'posVertical'       => 'top',
            'posHorizontalRel'  => 'margin',
            'posVerticalRel'    => 'page',
            'innerMarginTop'    => '5',
            'innerMarginRight'  => '5',
            'innerMarginBottom' => '5',
            'innerMarginLeft'   => '5',
            'borderSize'        => '2',
            'borderColor'       => 'red',
        );
        foreach ($properties as $key => $value) {
            $get = "get{$key}";
            $object->setStyleValue("{$key}", $value);
            $this->assertEquals($value, $object->$get());
        }
    }

    /**
     * Test setWrappingStyle exception
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetWrappingStyleException()
    {
        $object = new TextBox();
        $object->setWrappingStyle('foo');
    }

    /**
     * Test set/get width
     */
    public function testSetGetWidth()
    {
        $expected = 200;
        $object = new TextBox();
        $object->setWidth($expected);
        $this->assertEquals($expected, $object->getWidth());
    }

    /**
     * Test set/get height
     */
    public function testSetGetHeight()
    {
        $expected = 200;
        $object = new TextBox();
        $object->setHeight($expected);
        $this->assertEquals($expected, $object->getHeight());
    }

    /**
     * Test set/get height
     */
    public function testSetGetAlign()
    {
        $textBox = new TextBox();

        $expectedAlignment = Jc::START;
        $textBox->setAlignment($expectedAlignment);
        $this->assertEquals($expectedAlignment, $textBox->getAlignment());
    }

    /**
     * Test set/get marginTop
     */
    public function testSetGetMarginTop()
    {
        $expected = 5;
        $object = new TextBox();
        $object->setMarginTop($expected);
        $this->assertEquals($expected, $object->getMarginTop());
    }

    /**
     * Test set/get marginLeft
     */
    public function testSetGetMarginLeft()
    {
        $expected = 5;
        $object = new TextBox();
        $object->setMarginLeft($expected);
        $this->assertEquals($expected, $object->getMarginLeft());
    }

    /**
     * Test set/get innerMarginTop
     */
    public function testSetGetInnerMarginTop()
    {
        $expected = 5;
        $object = new TextBox();
        $object->setInnerMarginTop($expected);
        $this->assertEquals($expected, $object->getInnerMarginTop());
    }

    /**
     * Test set/get wrappingStyle
     */
    public function testSetGetWrappingStyle()
    {
        $expected = 'inline';
        $object = new TextBox();
        $object->setWrappingStyle($expected);
        $this->assertEquals($expected, $object->getWrappingStyle());
    }

    /**
     * Test set/get positioning
     */
    public function testSetGetPositioning()
    {
        $expected = 'absolute';
        $object = new TextBox();
        $object->setPositioning($expected);
        $this->assertEquals($expected, $object->getPositioning());
    }

    /**
     * Test set/get posHorizontal
     */
    public function testSetGetPosHorizontal()
    {
        $expected = 'center';
        $object = new TextBox();
        $object->setPosHorizontal($expected);
        $this->assertEquals($expected, $object->getPosHorizontal());
    }

    /**
     * Test set/get posVertical
     */
    public function testSetGetPosVertical()
    {
        $expected = 'top';
        $object = new TextBox();
        $object->setPosVertical($expected);
        $this->assertEquals($expected, $object->getPosVertical());
    }

    /**
     * Test set/get posHorizontalRel
     */
    public function testSetGetPosHorizontalRel()
    {
        $expected = 'margin';
        $object = new TextBox();
        $object->setPosHorizontalRel($expected);
        $this->assertEquals($expected, $object->getPosHorizontalRel());
    }

    /**
     * Test set/get posVerticalRel
     */
    public function testSetGetPosVerticalRel()
    {
        $expected = 'page';
        $object = new TextBox();
        $object->setPosVerticalRel($expected);
        $this->assertEquals($expected, $object->getPosVerticalRel());
    }


    /**
     * Test set/get innerMarginRight
     */
    public function testSetGetInnerMarginRight()
    {
        $expected = 5;
        $object = new TextBox();
        $object->setInnerMarginRight($expected);
        $this->assertEquals($expected, $object->getInnerMarginRight());
    }

    /**
     * Test set/get innerMarginBottom
     */
    public function testSetGetInnerMarginBottom()
    {
        $expected = 5;
        $object = new TextBox();
        $object->setInnerMarginBottom($expected);
        $this->assertEquals($expected, $object->getInnerMarginBottom());
    }

    /**
     * Test set/get innerMarginLeft
     */
    public function testSetGetInnerMarginLeft()
    {
        $expected = 5;
        $object = new TextBox();
        $object->setInnerMarginLeft($expected);
        $this->assertEquals($expected, $object->getInnerMarginLeft());
    }

    /**
     * Test set/get innerMarginLeft
     */
    public function testSetGetInnerMargin()
    {
        $expected = 5;
        $object = new TextBox();
        $object->setInnerMargin($expected);
        $this->assertEquals(array($expected, $expected, $expected, $expected), $object->getInnerMargin());
    }

    /**
     * Test set/get borderSize
     */
    public function testSetGetBorderSize()
    {
        $expected = 2;
        $object = new TextBox();
        $object->setBorderSize($expected);
        $this->assertEquals($expected, $object->getBorderSize());
    }

    /**
     * Test set/get borderColor
     */
    public function testSetGetBorderColor()
    {
        $expected = 'red';
        $object = new TextBox();
        $object->setBorderColor($expected);
        $this->assertEquals($expected, $object->getBorderColor());
    }
}
