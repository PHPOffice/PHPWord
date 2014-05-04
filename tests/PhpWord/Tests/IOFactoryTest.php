<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

/**
 * Test class for PhpOffice\PhpWord\IOFactory
 *
 * @runTestsInSeparateProcesses
 */
class IOFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Create existing writer
     */
    public function testExistingWriterCanBeCreated()
    {
        $this->assertInstanceOf(
            'PhpOffice\\PhpWord\\Writer\\Word2007',
            IOFactory::createWriter(new PhpWord(), 'Word2007')
        );
    }

    /**
     * Create non-existing writer
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     */
    public function testNonexistentWriterCanNotBeCreated()
    {
        IOFactory::createWriter(new PhpWord(), 'Word2006');
    }

    /**
     * Create existing reader
     */
    public function testExistingReaderCanBeCreated()
    {
        $this->assertInstanceOf(
            'PhpOffice\\PhpWord\\Reader\\Word2007',
            IOFactory::createReader('Word2007')
        );
    }

    /**
     * Create non-existing reader
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     */
    public function testNonexistentReaderCanNotBeCreated()
    {
        IOFactory::createReader('Word2006');
    }

    /**
     * Load document
     */
    public function testLoad()
    {
        $file = __DIR__ . "/_files/templates/blank.docx";
        $this->assertInstanceOf(
            'PhpOffice\\PhpWord\\PhpWord',
            IOFactory::load($file)
        );
    }
}
