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
use PhpOffice\PhpWord\Style\Paper;

/**
 * Test class for PhpOffice\PhpWord\Style\Paper.
 */
class PaperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test initiation for paper.
     */
    public function testInitiation(): void
    {
        $object = new Paper();

        self::assertEquals('A4', $object->getSize());
    }

    /**
     * Test paper size for B5 format.
     */
    public function testB5Size(): void
    {
        $object = new Paper('B5');

        self::assertEquals('B5', $object->getSize());
        self::assertEqualsWithDelta(9977.9527559055, $object->getWidth(), 0.000000001);
        self::assertEqualsWithDelta(14173.228346457, $object->getHeight(), 0.000000001);
    }

    /**
     * Test paper size for Folio format.
     */
    public function testFolioSize(): void
    {
        $object = new Paper();
        $object->setSize('Folio');

        self::assertEquals('Folio', $object->getSize());
        self::assertEqualsWithDelta(12240, $object->getWidth(), 0.1);
        self::assertEqualsWithDelta(18720, $object->getHeight(), 0.1);
    }

    /**
     * Test paper size for Folio format invoked with explicit values.
     */
    public function testFolioSizeExplicit(): void
    {
        $object = new Paper();
        $object->setSize('XFolio', 8.5, 13, 'in');

        self::assertEquals('XFolio', $object->getSize());
        self::assertEqualsWithDelta(12240, $object->getWidth(), 0.1);
        self::assertEqualsWithDelta(18720, $object->getHeight(), 0.1);
    }

    public function testBadUnit(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('unit must be mm or in');
        $object = new Paper();
        $object->setSize('XFolio', 8.5, 13, 'inx');
    }

    public function testBadWidthHeight(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('width and height must be positive');
        $object = new Paper();
        $object->setSize('XFolio', 0, 13, 'inx');
    }

    public function testUnknownSize(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid style value');
        $object = new Paper();
        $object->setSize('XFolio');
    }
}
