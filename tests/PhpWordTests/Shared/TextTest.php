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

namespace PhpOffice\PhpWordTests\Shared;

use PhpOffice\PhpWord\Exception\Exception as WordException;
use PhpOffice\PhpWord\Shared\Text;

/**
 * Test class for Text.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\Text
 */
class TextTest extends \PHPUnit\Framework\TestCase
{
    public function testControlCharacters(): void
    {
        self::assertEquals('', Text::controlCharacterPHP2OOXML());
        self::assertEquals('aeiou', Text::controlCharacterPHP2OOXML('aeiou'));
        self::assertEquals('àéîöù', Text::controlCharacterPHP2OOXML('àéîöù'));

        $value = mt_rand(0, 8);
        self::assertEquals('_x' . sprintf('%04s', strtoupper(dechex($value))) . '_', Text::controlCharacterPHP2OOXML(chr($value)));

        self::assertEquals('', Text::controlCharacterOOXML2PHP(''));
        self::assertEquals(chr(0x08), Text::controlCharacterOOXML2PHP('_x0008_'));
    }

    public function testNumberFormat(): void
    {
        self::assertEquals('2.1', Text::numberFormat('2.06', 1));
        self::assertEquals('2.1', Text::numberFormat('2.12', 1));
        self::assertEquals('1234.0', Text::numberFormat(1234, 1));
    }

    /** @dataProvider providerTestChr */
    public function testChr(string $expectedResult, int $ord): void
    {
        self::assertSame($expectedResult, Text::chr($ord)); // @phpstan-ignore argument.type (we test with some nominally illegal negative numbers to verify run-time works with them)
        if (PHP_VERSION_ID >= 70300) { // flaky results for mb_chr in 7.1/7.2
            if ($expectedResult === '') {
                self::assertFalse(mb_chr($ord));
            } else {
                self::assertSame($expectedResult, mb_chr($ord));
            }
        }
    }

    public static function providerTestChr(): array
    {
        return [
            'ascii range' => ['A', 65],
            'latin-1 supplement' => ['é', 233],
            'Kangxi Radicals' => ['⼳', 12083],
            'miscellaneous symbols and pictographs' => ['🌃', 0x1F303],
            'too high' => ['', 0x200000],
            'negative' => ['', -1],
            'null character' => ["\0", 0],
        ];
    }

    /**
     * Is UTF8.
     */
    public function testIsUTF8(): void
    {
        self::assertTrue(Text::isUTF8(''));
        self::assertTrue(Text::isUTF8('éééé'));
        self::assertFalse(Text::isUTF8(utf8decode('éééé')));
    }

    /**
     * Test unicode conversion.
     */
    public function testToUnicode(): void
    {
        self::assertEquals('a', Text::toUnicode('a'));
        self::assertEquals('\uc0{\u8364}', Text::toUnicode('€'));
        self::assertEquals('\uc0{\u233}', Text::toUnicode('é'));
    }

    /**
     * Test remove underscore prefix.
     */
    public function testRemoveUnderscorePrefix(): void
    {
        self::assertEquals('item', Text::removeUnderscorePrefix('_item'));
    }

    public function testFailToUtf8(): void
    {
        $this->expectException(WordException::class);
        $this->expectExceptionMessage('Unable to convert text to UTF-8');
        Text2::toUTF8("\x80");
    }
}
