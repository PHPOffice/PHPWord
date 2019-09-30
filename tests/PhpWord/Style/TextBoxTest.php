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

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Colors\HighlightColor;
use PhpOffice\PhpWord\Style\Lengths\Absolute;
use Throwable;

/**
 * Test class for PhpOffice\PhpWord\Style\Image
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Image
 * @runTestsInSeparateProcesses
 */
class TextBoxTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test setting style with normal value
     */
    public function testSetGetNormal()
    {
        $object = new TextBox();

        $properties = array(
            // 'width'             => Absolute::from("twip", 200),
            // 'height'            => Absolute::from("twip", 200),
            'alignment'         => Jc::START,
            'marginTop'         => Absolute::from('twip', 240),
            'marginLeft'        => Absolute::from('twip', 240),
            'wrappingStyle'     => 'inline',
            'positioning'       => 'absolute',
            'posHorizontal'     => 'center',
            'posVertical'       => 'top',
            'posHorizontalRel'  => 'margin',
            'posVerticalRel'    => 'page',
            'innerMarginTop'    => Absolute::from('twip', 5),
            'innerMarginRight'  => Absolute::from('twip', 5),
            'innerMarginBottom' => Absolute::from('twip', 5),
            'innerMarginLeft'   => Absolute::from('twip', 5),
            'borderSize'        => Absolute::from('twip', 2),
            'borderColor'       => new HighlightColor('red'),
        );
        foreach ($properties as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            $result = $object->$get();
            if ($value instanceof Absolute) {
                try {
                    $value = $value->toInt('twip');
                    $result = $result->toInt('twip');
                } catch (Throwable $ex) {
                    throw new Exception("Failed to convert values for property `$key`", 1, $ex);
                }
            }
            $this->assertEquals($value, $result);
        }
    }

    /**
     * Test setStyleValue method
     */
    public function testSetStyleValue()
    {
        $object = new TextBox();

        $properties = array(
            'width'             => Absolute::from('twip', 200),
            'height'            => Absolute::from('twip', 200),
            'alignment'         => Jc::START,
            'marginTop'         => Absolute::from('twip', 240),
            'marginLeft'        => Absolute::from('twip', 240),
            'wrappingStyle'     => 'inline',
            'positioning'       => 'absolute',
            'posHorizontal'     => 'center',
            'posVertical'       => 'top',
            'posHorizontalRel'  => 'margin',
            'posVerticalRel'    => 'page',
            'innerMarginTop'    => Absolute::from('twip', 5),
            'innerMarginRight'  => Absolute::from('twip', 5),
            'innerMarginBottom' => Absolute::from('twip', 5),
            'innerMarginLeft'   => Absolute::from('twip', 5),
            'borderSize'        => Absolute::from('twip', 2),
            'borderColor'       => new HighlightColor('red'),
        );
        foreach ($properties as $key => $value) {
            $get = "get{$key}";
            $object->setStyleValue("{$key}", $value);
            $result = $object->$get();
            if ($value instanceof Absolute) {
                $result = $result->toInt('twip');
                $value = $value->toInt('twip');
            }
            $this->assertEquals($value, $result);
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
        $object->setWidth(Absolute::from('twip', $expected));
        $this->assertEquals($expected, $object->getWidth()->toInt('twip'));
    }

    /**
     * Test set/get height
     */
    public function testSetGetHeight()
    {
        $expected = 200;
        $object = new TextBox();
        $object->setHeight(Absolute::from('twip', $expected));
        $this->assertEquals($expected, $object->getHeight()->toInt('twip'));
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
        $object->setMarginTop(Absolute::from('twip', $expected));
        $this->assertEquals($expected, $object->getMarginTop()->toInt('twip'));
    }

    /**
     * Test set/get marginLeft
     */
    public function testSetGetMarginLeft()
    {
        $expected = 5;
        $object = new TextBox();
        $object->setMarginLeft(Absolute::from('twip', $expected));
        $this->assertEquals($expected, $object->getMarginLeft()->toInt('twip'));
    }

    /**
     * Test set/get innerMarginTop
     */
    public function testSetGetInnerMarginTop()
    {
        $expected = 5;
        $object = new TextBox();
        $object->setInnerMarginTop(Absolute::from('twip', $expected));
        $this->assertEquals($expected, $object->getInnerMarginTop()->toInt('twip'));
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
        $object->setInnerMarginRight(Absolute::from('twip', $expected));
        $this->assertEquals($expected, $object->getInnerMarginRight()->toInt('twip'));
    }

    /**
     * Test set/get innerMarginBottom
     */
    public function testSetGetInnerMarginBottom()
    {
        $expected = 5;
        $object = new TextBox();
        $object->setInnerMarginBottom(Absolute::from('twip', $expected));
        $this->assertEquals($expected, $object->getInnerMarginBottom()->toInt('twip'));
    }

    /**
     * Test set/get innerMarginLeft
     */
    public function testSetGetInnerMarginLeft()
    {
        $expected = 5;
        $object = new TextBox();
        $object->setInnerMarginLeft(Absolute::from('twip', $expected));
        $this->assertEquals($expected, $object->getInnerMarginLeft()->toInt('twip'));
    }

    /**
     * Test set/get innerMarginLeft
     */
    public function testSetGetInnerMargin()
    {
        $expected = 5;
        $object = new TextBox();
        $object->setInnerMargin(Absolute::from('twip', $expected));
        $this->assertEquals(array($expected, $expected, $expected, $expected), array_map(function ($value) {
            return $value->toInt('twip');
        }, $object->getInnerMargin()));
    }

    /**
     * Test set/get borderSize
     */
    public function testSetGetBorderSize()
    {
        $expected = 2;
        $object = new TextBox();
        $object->setBorderSize(Absolute::from('twip', $expected));
        $this->assertEquals($expected, $object->getBorderSize()->toInt('twip'));
    }

    /**
     * Test set/get borderColor
     */
    public function testSetGetBorderColor()
    {
        $expected = new HighlightColor('red');
        $object = new TextBox();
        $object->setBorderColor($expected);
        $this->assertEquals($expected, $object->getBorderColor());
    }
}
