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

use InvalidArgumentException;
use PhpOffice\PhpWord\Style\Language;

/**
 * Test class for PhpOffice\PhpWord\Style\Language.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Language
 */
class LanguageTest extends \PHPUnit\Framework\TestCase
{
    public function testGetSetPropertiesInt(): void
    {
        foreach ([
            'LangId' => [0, 1036],
        ] as $property => $value) {
            $object = new Language();
            [$default, $expected] = $value;
            $get = "get{$property}";
            $set = "set{$property}";

            self::assertSame($default, $object->$get()); // Default value

            $object->$set($expected);

            self::assertSame($expected, $object->$get()); // New value
        }
    }

    public function testGetSetPropertiesString(): void
    {
        foreach ([
            'Latin' => [null, 'fr-BE'],
            'EastAsia' => [null, 'ja-JP'],
            'Bidirectional' => [null, 'ar-SA'],
        ] as $property => $value) {
            $object = new Language();
            [$default, $expected] = $value;
            $get = "get{$property}";
            $set = "set{$property}";

            self::assertSame($default, $object->$get()); // Default value

            $object->$set($expected);

            self::assertSame($expected, $object->$get()); // New value
        }
    }

    /**
     * Test throws exception if wrong locale is given.
     */
    public function testWrongLanguage(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('not a valid language code');
        $language = new Language();
        $language->setLatin('fra');
    }

    /**
     * Tests that a language can be set with just a 2 char code.
     */
    public function testShortLanguage(): void
    {
        //when
        $language = new Language('fr');

        //then
        self::assertSame('fr-FR', $language->getLatin());
        self::assertSame(1036, $language->getLangId());
    }

    public function testUndefined(): void
    {
        //when
        $language = new Language('und');

        //then
        self::assertSame('en-GB', $language->getLatin());
        self::assertSame(2057, $language->getLangId());
    }

    public function testLangId(): void
    {
        $language = new Language('it-IT');
        self::assertSame(1040, $language->getLangId());
        $language = new Language('xt-IT');
        self::assertSame(0, $language->getLangId());
        $language = new Language('xt-IT', '', '', 1234);
        self::assertSame(1234, $language->getLangId());
        $language = new Language('', 'hi-IN');
        self::assertSame(1081, $language->getLangId());
        $language = new Language('', '', 'he-IL');
        self::assertSame(1037, $language->getLangId());
    }
}
