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
use PhpOffice\PhpWord\ComplexType\ProofState;

/**
 * Test class for PhpOffice\PhpWord\ComplexType\ProofState.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\ComplexType\ProofState
 */
class ProofStateTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests the getters and setters.
     */
    public function testGetSet(): void
    {
        $pState = new ProofState();
        $pState->setGrammar(ProofState::CLEAN);
        $pState->setSpelling(ProofState::DIRTY);

        self::assertEquals(ProofState::CLEAN, $pState->getGrammar());
        self::assertEquals(ProofState::DIRTY, $pState->getSpelling());
    }

    /**
     * Test throws exception if wrong grammar proof state value given.
     */
    public function testWrongGrammar(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $pState = new ProofState();
        $pState->setGrammar('Wrong');
    }

    /**
     * Test throws exception if wrong spelling proof state value given.
     */
    public function testWrongSpelling(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $pState = new ProofState();
        $pState->setSpelling('Wrong');
    }
}
