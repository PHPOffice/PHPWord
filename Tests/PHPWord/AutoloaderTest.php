<?php
namespace PHPWord\Tests;

use PHPUnit_Framework_TestCase;
use PHPWord_Autoloader;
use PHPWord_Autoloader as Autoloader;

class AutoloaderTest extends PHPUnit_Framework_TestCase
{
    public function testRegister()
    {
        PHPWord_Autoloader::register();
        $this->assertContains(array('PHPWord_Autoloader', 'load'), spl_autoload_functions());
        $this->assertContains(array('PHPWord_Autoloader', 'autoload'), spl_autoload_functions());
    }

    public function testAutoloadLegacy()
    {
        $this->assertNull(PHPWord_Autoloader::load('Foo'), 'PHPWord_Autoloader::load() is trying to load classes outside of the PHPWord namespace');
        $this->assertTrue(PHPWord_Autoloader::load('PHPWord'), 'PHPWord_Autoloader::load() failed to autoload the PHPWord class');
    }

    public function testAutoload()
    {
        $declared = get_declared_classes();
        $declaredCount = count($declared);
        Autoloader::autoload('Foo');
        $this->assertEquals($declaredCount, count(get_declared_classes()), 'PHPWord\\Autoloader::autoload() is trying to load classes outside of the PHPWord namespace');
        Autoloader::autoload('PHPWord\\Exceptions\\InvalidStyleException'); // TODO change this class to the main PHPWord class when it is namespaced
        $this->assertTrue(in_array('PHPWord\\Exceptions\\InvalidStyleException', get_declared_classes()), 'PHPWord\\Autoloader::autoload() failed to autoload the PHPWord\\Exceptions\\InvalidStyleException class');
    }
}