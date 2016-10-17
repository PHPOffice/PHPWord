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
namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\Writer\Word2007\Part\RelsPart;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Part subnamespace
 *
 * Covers miscellaneous tests
 */
class PartTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test exception when no type or target assigned to a relation
     *
     * @covers \PhpOffice\PhpWord\Writer\Word2007\Part\Rels::writeRel
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage Invalid parameters passed.
     */
    public function testRelsWriteRelException()
    {
        $object = new RelsPart();
        $object->setMedia(array(array('type' => '', 'target' => '')));
        $object->write();
    }
}
