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

use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

/**
 * Test class for PhpOffice\PhpWord\Style\Image
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Image
 * @runTestsInSeparateProcesses
 */
class ImageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test setting style with normal value
     */
    public function testSetGetNormal()
    {
        $object = new Image();

        $properties = array(
            'width'              => Absolute::from('twip', 200),
            'height'             => Absolute::from('twip', 200),
            'alignment'          => Jc::START,
            'marginTop'          => Absolute::from('twip', 240),
            'marginLeft'         => Absolute::from('twip', 240),
            'wrappingStyle'      => 'inline',
            'wrapDistanceLeft'   => Absolute::from('twip', 10),
            'wrapDistanceRight'  => Absolute::from('twip', 20),
            'wrapDistanceTop'    => Absolute::from('twip', 30),
            'wrapDistanceBottom' => Absolute::from('twip', 40),
        );
        foreach ($properties as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            $result = $object->$get();
            if ($result instanceof Absolute) {
                $result = $result->toInt('twip');
                $value = $value->toInt('twip');
            }
            $this->assertEquals($value, $result);
        }
    }

    /**
     * Test setStyleValue method
     */
    public function testSetStyleValue()
    {
        $object = new Image();

        $properties = array(
            'width'              => Absolute::from('twip', 200),
            'height'             => Absolute::from('twip', 200),
            'alignment'          => Jc::START,
            'marginTop'          => Absolute::from('twip', 240),
            'marginLeft'         => Absolute::from('twip', 240),
            'position'           => Absolute::from('twip', 10),
            'positioning'        => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'posHorizontal'      => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
            'posVertical'        => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_TOP,
            'posHorizontalRel'   => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_COLUMN,
            'posVerticalRel'     => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_IMARGIN,
            'wrapDistanceLeft'   => Absolute::from('twip', 10),
            'wrapDistanceRight'  => Absolute::from('twip', 20),
            'wrapDistanceTop'    => Absolute::from('twip', 30),
            'wrapDistanceBottom' => Absolute::from('twip', 40),
        );
        foreach ($properties as $key => $value) {
            $get = "get{$key}";
            $object->setStyleValue("{$key}", $value);
            $result = $object->$get();
            if ($result instanceof Absolute) {
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
        $object = new Image();
        $object->setWrappingStyle('foo');
    }
}
