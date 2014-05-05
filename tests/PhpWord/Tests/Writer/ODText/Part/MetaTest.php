<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */
namespace PhpOffice\PhpWord\Tests\Writer\ODText\Part;

use PhpOffice\PhpWord\Writer\ODText\Part\Meta;

/**
 * Test class for PhpOffice\PhpWord\Writer\ODText\Part\Meta
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\ODText\Part\Meta
 * @runTestsInSeparateProcesses
 */
class MetaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test construct with no PhpWord
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage No PhpWord assigned.
     */
    public function testConstructNoPhpWord()
    {
        $object = new Meta();
        $object->writeMeta();
    }
}
