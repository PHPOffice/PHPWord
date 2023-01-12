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

/**
 * Test class for PhpOffice\PhpWord\Element\AbstractElement.
 */
class AbstractElementTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test set/get element index.
     */
    public function testElementIndex(): void
    {
        $stub = $this->getMockForAbstractClass('\PhpOffice\PhpWord\Element\AbstractElement');
        $ival = mt_rand(0, 100);
        $stub->setElementIndex($ival);
        self::assertEquals($ival, $stub->getElementIndex());
    }

    /**
     * Test set/get element unique Id.
     */
    public function testElementId(): void
    {
        $stub = $this->getMockForAbstractClass('\PhpOffice\PhpWord\Element\AbstractElement');
        $stub->setElementId();
        self::assertEquals(6, strlen($stub->getElementId()));
    }
}
