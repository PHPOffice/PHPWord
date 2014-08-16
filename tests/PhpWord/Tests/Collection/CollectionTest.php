<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Tests\Element;

use PhpOffice\PhpWord\Collection\Footnotes;
use PhpOffice\PhpWord\Element\Footnote;

/**
 * Test class for PhpOffice\PhpWord\Element\Collection subnamespace
 *
 * Using concrete class Footnotes instead of AbstractCollection
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test collection
     */
    public function testCollection()
    {
        $object = new Footnotes();
        $object->addItem(new Footnote()); // addItem #1

        $this->assertEquals(2, $object->addItem(new Footnote())); // addItem #2. Should returns new item index
        $this->assertCount(2, $object->getItems()); // getItems returns array
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Footnote', $object->getItem(1)); // getItem returns object
        $this->assertNull($object->getItem(3)); // getItem returns null when invalid index is referenced

        $object->setItem(2, null); // Set item #2 to null

        $this->assertNull($object->getItem(2)); // Check if it's null
    }
}
