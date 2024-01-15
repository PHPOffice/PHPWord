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

namespace PhpOffice\PhpWordTests\Element;

use PhpOffice\Math\Element;
use PhpOffice\Math\Math;
use PhpOffice\PhpWord\Element\Formula;
use PhpOffice\PhpWordTests\AbstractWebServerEmbeddedTest;

/**
 * Test class for PhpOffice\PhpWord\Element\Formula.
 *
 * @runTestsInSeparateProcesses
 */
class FormulaTest extends AbstractWebServerEmbeddedTest
{
    /**
     * @covers \PhpOffice\PhpWord\Element\Formula::__construct
     */
    public function testConstruct(): void
    {
        $element = new Formula(new Math());

        self::assertInstanceOf(Formula::class, $element);
    }

    /**
     * @covers \PhpOffice\PhpWord\Element\Formula::getMath
     * @covers \PhpOffice\PhpWord\Element\Formula::setMath
     */
    public function testMath(): void
    {
        $math = new Math();
        $math->add(new Element\Fraction(
            new Element\Numeric(2),
            new Element\Identifier('π')
        ));

        $element = new Formula(new Math());

        self::assertInstanceOf(Formula::class, $element);
        self::assertEquals(new Math(), $element->getMath());
        self::assertNotEquals($math, $element->getMath());

        self::assertInstanceOf(Formula::class, $element->setMath($math));
        self::assertNotEquals(new Math(), $element->getMath());
        self::assertEquals($math, $element->getMath());
    }
}
