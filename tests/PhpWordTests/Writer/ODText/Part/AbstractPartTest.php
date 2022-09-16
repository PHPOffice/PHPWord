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

namespace PhpOffice\PhpWordTests\Writer\ODText\Part;

use Exception;
use PhpOffice\PhpWord\Writer\ODText;

/**
 * Test class for PhpOffice\PhpWord\Writer\ODText\Part\AbstractPart.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\ODText\Part\AbstractPart
 */
class AbstractPartTest extends \PHPUnit\Framework\TestCase
{
    /**
     * covers   ::setParentWriter
     * covers   ::getParentWriter.
     */
    public function testSetGetParentWriter(): void
    {
        $object = $this->getMockForAbstractClass(\PhpOffice\PhpWord\Writer\ODText\Part\AbstractPart::class);
        $object->setParentWriter(new ODText());
        self::assertEquals(new ODText(), $object->getParentWriter());
    }

    /**
     * covers   ::getParentWriter.
     */
    public function testSetGetParentWriterNull(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No parent WriterInterface assigned.');
        $object = $this->getMockForAbstractClass(\PhpOffice\PhpWord\Writer\ODText\Part\AbstractPart::class);
        $object->getParentWriter();
    }
}
