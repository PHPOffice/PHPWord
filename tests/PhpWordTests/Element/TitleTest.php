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
declare(strict_types=1);

namespace PhpOffice\PhpWordTests\Element;

use InvalidArgumentException;
use PhpOffice\PhpWord\Element\PageBreak;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Title;

/**
 * Test class for PhpOffice\PhpWord\Element\Title.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\Title
 *
 * @runTestsInSeparateProcesses
 */
class TitleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Create new instance with string.
     */
    public function testConstruct(): void
    {
        $title = new Title('text');

        self::assertInstanceOf(Title::class, $title);
        self::assertEquals('text', $title->getText());
        self::assertEquals(1, $title->getDepth());
        self::assertNull($title->getPageNumber());
        self::assertNull($title->getStyle());
    }

    /**
     * Create new instance with TextRun.
     */
    public function testConstructWithTextRun(): void
    {
        $textRun = new TextRun();
        $textRun->addText('text');
        $title = new Title($textRun);

        self::assertInstanceOf(TextRun::class, $title->getText());
        self::assertEquals(1, $title->getDepth());
        self::assertNull($title->getPageNumber());
        self::assertNull($title->getStyle());
    }

    public function testConstructWithInvalidArgument(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Title(new PageBreak());
    }

    public function testConstructWithPageNumber(): void
    {
        $title = new Title('text', 1, 0);

        self::assertInstanceOf(Title::class, $title);
        self::assertEquals('text', $title->getText());
        self::assertEquals(0, $title->getPageNumber());
        self::assertNull($title->getStyle());
    }
}
