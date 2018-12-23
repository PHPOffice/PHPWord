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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Style\Paper
 *
 * @runTestsInSeparateProcesses
 */
class PaperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tear down after each test
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test initiation for paper
     */
    public function testInitiation()
    {
        $object = new Paper();

        $this->assertEquals('A4', $object->getSize());
    }

    /**
     * Test paper size for B5 format
     */
    public function testB5Size()
    {
        $object = new Paper('B5');

        $this->assertEquals('B5', $object->getSize());
        $this->assertEquals(9977.9527559055, $object->getWidth(), '', 0.000000001);
        $this->assertEquals(14173.228346457, $object->getHeight(), '', 0.000000001);
    }

    /**
     * Test paper size for Folio format
     */
    public function testFolioSize()
    {
        $object = new Paper();
        $object->setSize('Folio');

        $this->assertEquals('Folio', $object->getSize());
        $this->assertEquals(12240, $object->getWidth(), '', 0.1);
        $this->assertEquals(18720, $object->getHeight(), '', 0.1);
    }
}
