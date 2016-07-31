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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

/**
 * Test class for PhpOffice\PhpWord\Element\Title
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\Title
 * @runTestsInSeparateProcesses
 */
class TitleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Create new instance
     */
    public function testConstruct()
    {
        $oTitle = new Title('text');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Title', $oTitle);
        $this->assertEquals('text', $oTitle->getText());
    }

    /**
     * Get style null
     */
    public function testStyleNull()
    {
        $oTitle = new Title('text');

        $this->assertNull($oTitle->getStyle());
    }
}
