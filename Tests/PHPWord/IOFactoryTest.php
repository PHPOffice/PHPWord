<?php
namespace PHPWord\Tests;

use PHPWord;
use PHPWord_IOFactory;
use PHPWord_Writer_Word2007;
use Exception;

/**
 * Class IOFactoryTest
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class IOFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSearchLocations()
    {
        $this->assertAttributeEquals(
            PHPWord_IOFactory::getSearchLocations(),
            '_searchLocations',
            'PHPWord_IOFactory'
        );
    }

    public function testSetSearchLocationsWithArray()
    {
        PHPWord_IOFactory::setSearchLocations(array());
        $this->assertAttributeEquals(array(), '_searchLocations', 'PHPWord_IOFactory');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Invalid parameter passed.
     */
    public function testSetSearchLocationsWithNotArray()
    {
        PHPWord_IOFactory::setSearchLocations('String');
    }

    public function testAddSearchLocation()
    {
        PHPWord_IOFactory::setSearchLocations(array());
        PHPWord_IOFactory::addSearchLocation('type', 'location', 'classname');
        $this->assertAttributeEquals(
            array(array('type' => 'type', 'path' => 'location', 'class' => 'classname')),
            '_searchLocations',
            'PHPWord_IOFactory'
        );
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage No IWriter found for type
     */
    public function testCreateWriterException()
    {
        $oPHPWord = new PHPWord();

        PHPWord_IOFactory::setSearchLocations(array());
        PHPWord_IOFactory::createWriter($oPHPWord);
    }

    public function testCreateWriter()
    {
        $oPHPWord = new PHPWord();

        $this->assertEquals(
            PHPWord_IOFactory::createWriter($oPHPWord, 'Word2007'),
            new PHPWord_Writer_Word2007($oPHPWord)
        );
    }
}
