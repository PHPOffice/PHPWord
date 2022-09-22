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

namespace PhpOffice\PhpWordTests\ComplexType;

use InvalidArgumentException;
use PhpOffice\PhpWord\ComplexType\FootnoteProperties;
use PhpOffice\PhpWord\SimpleType\NumberFormat;

/**
 * Test class for PhpOffice\PhpWord\ComplexType\FootnoteProperties.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\ComplexType\FootnoteProperties
 *
 * @runTestsInSeparateProcesses
 */
class FootnotePropertiesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test setting style with normal value.
     */
    public function testSetGetNormal(): void
    {
        $footnoteProp = new FootnoteProperties();
        $footnoteProp->setPos(FootnoteProperties::POSITION_DOC_END);
        $footnoteProp->setNumFmt(NumberFormat::LOWER_ROMAN);
        $footnoteProp->setNumStart(2);
        $footnoteProp->setNumRestart(FootnoteProperties::RESTART_NUMBER_EACH_PAGE);

        self::assertEquals(FootnoteProperties::POSITION_DOC_END, $footnoteProp->getPos());
        self::assertEquals(NumberFormat::LOWER_ROMAN, $footnoteProp->getNumFmt());
        self::assertEquals(2, $footnoteProp->getNumStart());
        self::assertEquals(FootnoteProperties::RESTART_NUMBER_EACH_PAGE, $footnoteProp->getNumRestart());
    }

    /**
     * Test throws exception if wrong position given.
     */
    public function testWrongPos(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $footnoteProp = new FootnoteProperties();
        $footnoteProp->setPos(NumberFormat::LOWER_ROMAN);
    }

    /**
     * Test throws exception if wrong number format given.
     */
    public function testWrongNumFmt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $footnoteProp = new FootnoteProperties();
        $footnoteProp->setNumFmt(FootnoteProperties::POSITION_DOC_END);
    }

    /**
     * Test throws exception if wrong number restart given.
     */
    public function testWrongNumRestart(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $footnoteProp = new FootnoteProperties();
        $footnoteProp->setNumRestart(NumberFormat::LOWER_ROMAN);
    }
}
