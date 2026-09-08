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

namespace PhpOffice\PhpWordTests\Reader;

use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Reader\WPS;

/**
 * Test class for PhpOffice\PhpWord\Reader\WPS.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Reader\WPS
 */
class WPSTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test canRead() method.
     */
    public function testCanRead(): void
    {
        $object = new WPS();
        $filename = __DIR__ . '/../_files/documents/reader.wps';
        self::assertTrue($object->canRead($filename));
    }

    /**
     * Can read exception.
     */
    public function testCanReadFailed(): void
    {
        $object = new WPS();
        $filename = __DIR__ . '/../_files/documents/foo.doc';
        self::assertFalse($object->canRead($filename));
    }

    public function testLoad(): void
    {
        $filename = __DIR__ . '/../_files/documents/reader.wps';
        $phpWord = IOFactory::load($filename, 'WPS');
        self::assertInstanceOf(PhpWord::class, $phpWord);

        $sections = $phpWord->getSections();
        self::assertCount(1, $sections);
        $elements = $sections[0]->getElements();
        self::assertCount(3, $elements);

        $expected = [
            'Hello from WPS!',
            'This is a test document generated for the PHPWord reader test suite.',
            'Third paragraph with a few more words.',
        ];
        foreach ($elements as $i => $element) {
            /** @var Text $element */
            self::assertInstanceOf(Text::class, $element);
            self::assertEquals($expected[$i], $element->getText());
        }
    }
}
