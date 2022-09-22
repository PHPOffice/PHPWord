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
     * Create new instance.
     */
    public function testConstruct(): void
    {
        $oTitle = new Title('text');

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Title', $oTitle);
        self::assertEquals('text', $oTitle->getText());
    }

    /**
     * Get style null.
     */
    public function testStyleNull(): void
    {
        $oTitle = new Title('text');

        self::assertNull($oTitle->getStyle());
    }

    /**
     * Create new instance with TextRun.
     */
    public function testConstructWithTextRun(): void
    {
        $oTextRun = new TextRun();
        $oTextRun->addText('text');
        $oTitle = new Title($oTextRun);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $oTitle->getText());
    }

    public function testConstructWithInvalidArgument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $oPageBreak = new PageBreak();
        new Title($oPageBreak);
    }
}
