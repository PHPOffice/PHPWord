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
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Exception;

/**
 * Test class for PhpOffice\PhpWord\Exception\UnsupportedImageTypeExceptionTest
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Exception\UnsupportedImageTypeExceptionTest
 * @runTestsInSeparateProcesses
 */
class UnsupportedImageTypeExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Throw new exception
     *
     * @expectedException \PhpOffice\PhpWord\Exception\UnsupportedImageTypeException
     * @covers            \PhpOffice\PhpWord\Exception\UnsupportedImageTypeException
     */
    public function testThrowException()
    {
        throw new UnsupportedImageTypeException();
    }
}
