<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Reader;

use PhpOffice\PhpWord\IOFactory;

/**
 * Test class for PhpOffice\PhpWord\Reader\ODText
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Reader\ODText
 * @runTestsInSeparateProcesses
 */
class ODTextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Load
     */
    public function testLoad()
    {
        $filename = __DIR__ . '/../_files/documents/reader.odt';
        $object = IOFactory::load($filename, 'ODText');
        $this->assertInstanceOf('PhpOffice\\PhpWord\\PhpWord', $object);
    }
}
