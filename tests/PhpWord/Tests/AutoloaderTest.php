<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\Autoloader;

/**
 * Test class for PhpOffice\PhpWord\Autoloader
 *
 * @runTestsInSeparateProcesses
 */
class AutoloaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Register
     */
    public function testRegister()
    {
        Autoloader::register();
        $this->assertContains(
            array('PhpOffice\\PhpWord\\Autoloader', 'autoload'),
            spl_autoload_functions()
        );
    }

    /**
     * Autoload
     */
    public function testAutoload()
    {
        $declared = get_declared_classes();
        $declaredCount = count($declared);
        Autoloader::autoload('Foo');
        $this->assertEquals(
            $declaredCount,
            count(get_declared_classes()),
            'PhpOffice\\PhpWord\\Autoloader::autoload() is trying to load ' .
            'classes outside of the PhpOffice\\PhpWord namespace'
        );
        // TODO change this class to the main PhpWord class when it is namespaced
        Autoloader::autoload('PhpOffice\\PhpWord\\Exception\\InvalidStyleException');
        $this->assertTrue(
            in_array('PhpOffice\\PhpWord\\Exception\\InvalidStyleException', get_declared_classes()),
            'PhpOffice\\PhpWord\\Autoloader::autoload() failed to autoload the ' .
            'PhpOffice\\PhpWord\\Exception\\InvalidStyleException class'
        );
    }
}
