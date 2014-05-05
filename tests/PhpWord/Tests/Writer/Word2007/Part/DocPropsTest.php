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
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */
namespace PhpOffice\PhpWord\Tests\Writer\Word2007\Part;

use PhpOffice\PhpWord\Writer\Word2007\Part\DocProps;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Part\DocProps
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Part\DocProps
 * @runTestsInSeparateProcesses
 */
class DocPropsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test write docProps/app.xml with no PhpWord
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage No PhpWord assigned.
     */
    public function testWriteDocPropsAppNoPhpWord()
    {
        $object = new DocProps();
        $object->writeDocPropsApp();
    }

    /**
     * Test write docProps/core.xml with no PhpWord
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage No PhpWord assigned.
     */
    public function testWriteDocPropsCoreNoPhpWord()
    {
        $object = new DocProps();
        $object->writeDocPropsCore();
    }
}
