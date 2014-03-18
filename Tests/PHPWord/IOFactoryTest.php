<?php
namespace PHPWord\Tests;

use PHPWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Writer\Word2007;
use Exception;

/**
 * @package                     PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class IOFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSearchLocations()
    {
        $this->assertAttributeEquals(
            IOFactory::getSearchLocations(),
            '_searchLocations',
            'PhpOffice\\PhpWord\\IOFactory'
        );
    }

    public function testSetSearchLocationsWithArray()
    {
        IOFactory::setSearchLocations(array());
        $this->assertAttributeEquals(array(), '_searchLocations', 'PhpOffice\\PhpWord\\IOFactory');
    }

    public function testAddSearchLocation()
    {
        IOFactory::setSearchLocations(array());
        IOFactory::addSearchLocation('interface', 'classname');
        $this->assertAttributeEquals(
            array(array('interface' => 'interface', 'class' => 'classname')),
            '_searchLocations',
            'PhpOffice\\PhpWord\\IOFactory'
        );
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage No IWriter found for type
     */
    public function testCreateWriterException()
    {
        $oPHPWord = new PHPWord();

        IOFactory::setSearchLocations(array());
        IOFactory::createWriter($oPHPWord);
    }

    public function testCreateWriter()
    {
        $oPHPWord = new PHPWord();

        $this->assertEquals(IOFactory::createWriter($oPHPWord, 'Word2007'), new Word2007($oPHPWord));
    }
}