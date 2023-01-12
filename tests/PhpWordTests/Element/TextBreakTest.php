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

namespace PhpOffice\PhpWordTests\Element;

use PhpOffice\PhpWord\Element\TextBreak;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Test class for PhpOffice\PhpWord\Element\TextBreak.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\TextBreak
 *
 * @runTestsInSeparateProcesses
 */
class TextBreakTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Construct with empty value.
     */
    public function testConstruct(): void
    {
        $object = new TextBreak();
        self::assertNull($object->getFontStyle());
        self::assertNull($object->getParagraphStyle());
    }

    /**
     * Construct with style object.
     */
    public function testConstructWithStyleObject(): void
    {
        $fStyle = new Font();
        $pStyle = new Paragraph();
        $object = new TextBreak($fStyle, $pStyle);
        self::assertEquals($fStyle, $object->getFontStyle());
        self::assertEquals($pStyle, $object->getParagraphStyle());
    }

    /**
     * Construct with style array.
     */
    public function testConstructWithStyleArray(): void
    {
        $fStyle = ['size' => 12];
        $pStyle = ['spacing' => 240];
        $object = new TextBreak($fStyle, $pStyle);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $object->getFontStyle());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $object->getParagraphStyle());
    }

    /**
     * Construct with style name.
     */
    public function testConstructWithStyleName(): void
    {
        $fStyle = 'fStyle';
        $pStyle = 'pStyle';
        $object = new TextBreak($fStyle, $pStyle);
        self::assertEquals($fStyle, $object->getFontStyle());
        self::assertEquals($pStyle, $object->getParagraphStyle());
    }
}
