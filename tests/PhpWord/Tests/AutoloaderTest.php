<?php
namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\Autoloader;

class AutoloaderTest extends \PHPUnit_Framework_TestCase
{
    public function testRegister()
    {
        Autoloader::register();
        $this->assertContains(
            array('PhpOffice\\PhpWord\\Autoloader', 'autoload'),
            \spl_autoload_functions()
        );
    }

    public function testAutoload()
    {
        $declared = \get_declared_classes();
        $declaredCount = \count($declared);
        Autoloader::autoload('Foo');
        $this->assertEquals(
            $declaredCount,
            \count(get_declared_classes()),
            'PhpOffice\\PhpWord\\Autoloader::autoload() is trying to load ' .
            'classes outside of the PhpOffice\\PhpWord namespace'
        );
        // TODO change this class to the main PhpWord class when it is namespaced
        Autoloader::autoload('PhpOffice\\PhpWord\\Exceptions\\InvalidStyleException');
        $this->assertTrue(
            \in_array('PhpOffice\\PhpWord\\Exceptions\\InvalidStyleException', \get_declared_classes()),
            'PhpOffice\\PhpWord\\Autoloader::autoload() failed to autoload the ' .
            'PhpOffice\\PhpWord\\Exceptions\\InvalidStyleException class'
        );
    }
}
