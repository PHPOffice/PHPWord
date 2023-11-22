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

namespace PhpOffice\PhpWordTests\Collection;

use PhpOffice\PhpWord\Collection\Footnotes;
use PhpOffice\PhpWord\Element\Footnote;

/**
 * Test class for PhpOffice\PhpWord\Collection subnamespace.
 *
 * Using concrete class Footnotes instead of AbstractCollection
 */
class CollectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test collection.
     */
    public function testCollection(): void
    {
        $object = new Footnotes();
        $object->addItem(new Footnote()); // addItem #1

        self::assertEquals(1, $object->addItem(new Footnote())); // addItem #2. Should returns new item index
        self::assertCount(2, $object->getItems()); // getItems returns array
        self::assertInstanceOf(Footnote::class, $object->getItem(1)); // getItem returns object
        self::assertNull($object->getItem(3)); // getItem returns null when invalid index is referenced

        $object->setItem(2, null); // Set item #2 to null

        self::assertNull($object->getItem(2)); // Check if it's null
    }

    public function testCollectionSetItem(): void
    {
        $object = new Footnotes();
        $object->addItem(new Footnote());
        self::assertCount(1, $object->getItems());

        $object->setItem(0, new Footnote());
        self::assertCount(1, $object->getItems());
    }
}
