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

use PhpOffice\PhpWord\SimpleType\VerticalJc;
use PhpOffice\PhpWord\Style\Colors\BasicColor;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Colors\Rgb;
use PhpOffice\PhpWord\Style\Lengths\Absolute;
use PhpOffice\PhpWord\Style\Lengths\Auto;
use PhpOffice\PhpWord\Style\Lengths\Length;
use PhpOffice\PhpWord\Style\Lengths\Percent;

/**
 * Test class for PhpOffice\PhpWord\Style\Cell
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Cell
 * @runTestsInSeparateProcesses
 */
class CellTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test setting style with normal value
     */
    public function testSetGetNormal()
    {
        $cell = new Cell();

        $attributes = array(
            'valign'            => VerticalJc::TOP,
            'textDirection'     => Cell::TEXT_DIR_BTLR,
            'bgColor'           => new Hex('FFFF00'),
            'gridSpan'          => 2,
            'vMerge'            => Cell::VMERGE_RESTART,
        );
        foreach ($attributes as $key => $value) {
            $get = "get$key";
            $result = $cell->$get();
            if ($result instanceof BasicColor) {
                $result = $result->toHex();
            } elseif ($result instanceof Absolute) {
                $result = $result->toInt('eop');
            }

            $this->assertNull($result);

            $set = "set{$key}";
            $cell->$set($value);

            $get = "get$key";
            $result = $cell->$get();
            if ($result instanceof BasicColor) {
                $result = $result->toHex();
                $value = $value->toHex();
            }

            $this->assertEquals($value, $result);
        }
    }

    /**
     * Test borders
     */
    public function testBorders()
    {
        $cell = new Cell();

        $this->assertFalse($cell->hasBorder());
        $borders = array('top', 'end', 'bottom', 'start');
        $borderSides = array(
            array(Absolute::from('pt', rand(1, 20)), new Hex('f93de1'), new BorderStyle('double'), Absolute::from('pt', rand(1, 20)), true),
            array(Absolute::from('twip', rand(1, 400)), new Hex('000000'), new BorderStyle('outset'), Absolute::from('twip', rand(1, 400)), false),
            array(Absolute::from('eop', rand(1, 160)), new Rgb(255, 0, 100), new BorderStyle('dotted'), Absolute::from('eop', rand(1, 160)), true),
        );
        $lastBorderSide = array(new Absolute(0), new Hex(null), new BorderStyle('single'), new Absolute(0), false);
        foreach ($borderSides as $key => $borderSide) {
            $newBorder = new BorderSide(...$borderSide);

            foreach ($borders as $side) {
                $currentBorder = $cell->getBorder($side);
                $this->assertEquals($lastBorderSide[0], $currentBorder->getSize(), "Size for border side #$key for side $side should match last border side still");
                $this->assertEquals($lastBorderSide[1], $currentBorder->getColor(), "Color for border side #$key for side $side should match last border side still");
                $this->assertEquals($lastBorderSide[2], $currentBorder->getStyle(), "Style for border side #$key for side $side should match last border side still");
                $this->assertEquals($lastBorderSide[3], $currentBorder->getSpace(), "Space for border side #$key for side $side should match last border side still");
                $this->assertEquals($lastBorderSide[4], $currentBorder->getShadow(), "Shadow for border side #$key for side $side should match last border side still");

                $cell->setBorder($side, $newBorder);
                $updatedBorder = $cell->getBorder($side);
                $this->assertEquals($borderSide[0], $updatedBorder->getSize(), "Size for border side #$key for side $side should match new border");
                $this->assertEquals($borderSide[1], $updatedBorder->getColor(), "Color for border side #$key for side $side should match new border");
                $this->assertEquals($borderSide[2], $updatedBorder->getStyle(), "Style for border side #$key for side $side should match new border");
                $this->assertEquals($borderSide[3], $updatedBorder->getSpace(), "Space for border side #$key for side $side should match new border");
                $this->assertEquals($borderSide[4], $updatedBorder->getShadow(), "Shadow for border side #$key for side $side should match new border");
            }

            $lastBorderSide = $borderSide;
        }
    }

    /**
     * Test width
     */
    public function testWidth()
    {
        $cell = new Cell();

        // Undefined
        $width = $cell->getWidth();
        $this->assertInstanceOf(Length::class, $width);
        $this->assertNull($width->toInt('twip'));

        // Null
        $cell->setWidth(new Absolute(null));
        $width = $cell->getWidth();
        $this->assertInstanceOf(Absolute::class, $width);
        $this->assertNull($width->toInt('twip'));

        // Absolute
        $cell->setWidth(Absolute::from('twip', 204));
        $width = $cell->getWidth();
        $this->assertInstanceOf(Length::class, $width);
        $this->assertEquals(204, $width->toInt('twip'));

        // Percent
        $cell->setWidth(new Percent(50));
        $width = $cell->getWidth();
        $this->assertInstanceOf(Percent::class, $width);
        $this->assertEquals(50, $width->toInt());

        // Auto
        $cell->setWidth(new Auto());
        $width = $cell->getWidth();
        $this->assertInstanceOf(Auto::class, $width);
    }
}
