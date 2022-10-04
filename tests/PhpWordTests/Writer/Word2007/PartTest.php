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

namespace PhpOffice\PhpWordTests\Writer\Word2007;

use PhpOffice\PhpWord\Writer\Word2007\Part\RelsPart;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Part subnamespace.
 *
 * Covers miscellaneous tests
 */
class PartTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test exception when no type or target assigned to a relation.
     *
     * @covers \PhpOffice\PhpWord\Writer\Word2007\Part\Rels::writeRel
     */
    public function testRelsWriteRelException(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\Exception::class);
        $this->expectExceptionMessage('Invalid parameters passed.');
        $object = new RelsPart();
        $object->setMedia([['type' => '', 'target' => '']]);
        $object->write();
    }
}
