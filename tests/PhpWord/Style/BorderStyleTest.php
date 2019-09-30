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

/**
 * Test class for PhpOffice\PhpWord\Style\Cell
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\BorderStyle
 * @runTestsInSeparateProcesses
 */
class BorderStyleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test setting style with normal value
     */
    public function testSetGet()
    {
        $styles = array(
            'single',
            'dashDotStroked',
            'dashed',
            'dashSmallGap',
            'dotDash',
            'dotDotDash',
            'dotted',
            'double',
            'doubleWave',
            'inset',
            'nil',
            'none',
            'outset',
            'thick',
            'thickThinLargeGap',
            'thickThinMediumGap',
            'thickThinSmallGap',
            'thinThickLargeGap',
            'thinThickMediumGap',
            'thinThickSmallGap',
            'thinThickThinLargeGap',
            'thinThickThinMediumGap',
            'thinThickThinSmallGap',
            'threeDEmboss',
            'threeDEngrave',
            'triple',
            'wave',
        );
        foreach ($styles as $style) {
            $borderStyle = new BorderStyle($style);
            $this->assertEquals($style, $borderStyle->getStyle());

            $borderStyle = BorderStyle::fromMixed($style);
            $this->assertEquals($style, $borderStyle->getStyle());
        }
    }

    /**
     * Test setting style with invalid value
     * @expectedException \Exception
     * @expectedExceptionMessage Provided border style must be valid. 'badstyle' provided. Allowed: 'single', 'dashDotStroked', 'dashed', 'dashSmallGap', 'dotDash', 'dotDotDash', 'dotted', 'double', 'doubleWave', 'inset', 'nil', 'none', 'outset', 'thick', 'thickThinLargeGap', 'thickThinMediumGap', 'thickThinSmallGap', 'thinThickLargeGap', 'thinThickMediumGap', 'thinThickSmallGap', 'thinThickThinLargeGap', 'thinThickThinMediumGap', 'thinThickThinSmallGap', 'threeDEmboss', 'threeDEngrave', 'triple', 'wave'
     */
    public function testSetGetBad()
    {
        new BorderStyle('badstyle');
    }

    /**
     * Test setting style with wrong type
     * @expectedException \TypeError
     * @expectedExceptionMessage Argument 1 passed to PhpOffice\PhpWord\Style\BorderStyle::__construct() must be of the type string
     */
    public function testSetGetWrongType()
    {
        new BorderStyle(54);
    }

    /**
     * Test getting style from bad value
     * @expectedException \PHPUnit\Framework\Error\Warning
     * @expectedExceptionMessage Border style `badstyle` is not a valid option
     */
    public function testFromMixedBadValue()
    {
        BorderStyle::fromMixed('badstyle');
    }

    /**
     * Test getting style from bad value
     */
    public function testValueFromMixedBadValue()
    {
        $borderStyle = @BorderStyle::fromMixed('badstyle');
        $this->assertEquals('single', $borderStyle->getStyle());
    }
}
