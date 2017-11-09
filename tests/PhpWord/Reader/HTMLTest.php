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

namespace PhpOffice\PhpWord\Reader;

use PhpOffice\PhpWord\IOFactory;

/**
 * Test class for PhpOffice\PhpWord\Reader\HTML
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Reader\HTML
 * @runTestsInSeparateProcesses
 */
class HTMLTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test load
     */
    public function testLoad()
    {
        $filename = __DIR__ . '/../_files/documents/reader.html';
        $phpWord = IOFactory::load($filename, 'HTML');
        $this->assertInstanceOf('PhpOffice\\PhpWord\\PhpWord', $phpWord);
    }

    /**
     * Test load exception
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Cannot read
     */
    public function testLoadException()
    {
        $filename = __DIR__ . '/../_files/documents/foo.html';
        IOFactory::load($filename, 'HTML');
    }
}
