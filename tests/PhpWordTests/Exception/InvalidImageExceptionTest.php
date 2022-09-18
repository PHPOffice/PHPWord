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

namespace PhpOffice\PhpWordTests\Exception;

use PhpOffice\PhpWord\Exception\InvalidImageException;

/**
 * Test class for PhpOffice\PhpWord\Exception\InvalidImageException.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Exception\InvalidImageException
 *
 * @runTestsInSeparateProcesses
 */
class InvalidImageExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Throw new exception.
     *
     * @covers            \PhpOffice\PhpWord\Exception\InvalidImageException
     */
    public function testThrowException(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\InvalidImageException::class);

        throw new InvalidImageException();
    }
}
