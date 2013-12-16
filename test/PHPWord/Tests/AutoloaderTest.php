<?php
namespace PHPWord\Tests;

use PHPUnit_Framework_TestCase;
use PHPWord_Autoloader;

class AutoloaderTest extends PHPUnit_Framework_TestCase
{
    public function testAutoload()
    {
        $this->assertNull(PHPWord_Autoloader::load('Foo'), 'PHPWord_Autoloader::load() is trying to load classes outside of the PHPWord namespace');
        $this->assertTrue(PHPWord_Autoloader::load('PHPWord'), 'PHPWord_Autoloader::load() failed to autoload the PHPWord class');
    }
}