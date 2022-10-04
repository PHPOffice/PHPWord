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
use PHPUnit\Framework\Assert;

/**
 * Test class for PhpOffice\PhpWord\Style\Language.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Language
 */
class LanguageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test get/set.
     */
    public function testGetSetProperties(): void
    {
        $object = new Language();
        $properties = [
            'latin' => [null, 'fr-BE'],
            'eastAsia' => [null, 'ja-JP'],
            'bidirectional' => [null, 'ar-SA'],
            'langId' => [null, 1036],
        ];
        foreach ($properties as $property => $value) {
            [$default, $expected] = $value;
            $get = "get{$property}";
            $set = "set{$property}";

            self::assertEquals($default, $object->$get()); // Default value

            $object->$set($expected);

            self::assertEquals($expected, $object->$get()); // New value
        }
    }

    /**
     * Test throws exception if wrong locale is given.
     */
    public function testWrongLanguage(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $language = new Language();
        $language->setLatin('fra');
    }

    /**
     * Tests that a language can be set with just a 2 char code.
     */
    public function testShortLanguage(): void
    {
        //when
        $language = new Language();
        $language->setLatin('fr');

        //then
        Assert::assertEquals('fr-FR', $language->getLatin());
    }
}
