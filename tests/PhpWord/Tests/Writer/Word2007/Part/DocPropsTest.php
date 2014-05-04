<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
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
