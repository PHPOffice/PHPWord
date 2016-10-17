<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

/**
 * Test class for PhpOffice\PhpWord\Element\Field
 *
 * @runTestsInSeparateProcesses
 */
class FieldTest extends \PHPUnit_Framework_TestCase
{
    /**
     * New instance
     */
    public function testConstructNull()
    {
        $oField = new Field();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Field', $oField);
    }

    /**
     * New instance with type
     */
    public function testConstructWithType()
    {
        $oField = new Field('DATE');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Field', $oField);
        $this->assertEquals('DATE', $oField->getType());
    }

    /**
     * New instance with type and properties
     */
    public function testConstructWithTypeProperties()
    {
        $oField = new Field('DATE', array('dateformat' => 'd-M-yyyy'));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Field', $oField);
        $this->assertEquals('DATE', $oField->getType());
        $this->assertEquals(array('dateformat' => 'd-M-yyyy'), $oField->getProperties());
    }

    /**
     * New instance with type and properties and options
     */
    public function testConstructWithTypePropertiesOptions()
    {
        $oField = new Field('DATE', array('dateformat' => 'd-M-yyyy'), array('SakaEraCalendar', 'PreserveFormat'));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Field', $oField);
        $this->assertEquals('DATE', $oField->getType());
        $this->assertEquals(array('dateformat' => 'd-M-yyyy'), $oField->getProperties());
        $this->assertEquals(array('SakaEraCalendar', 'PreserveFormat'), $oField->getOptions());
    }

    /**
     * Test setType exception
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid type
     */
    public function testSetTypeException()
    {
        $object = new Field();
        $object->setType('foo');
    }

    /**
     * Test setProperties exception
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid property
     */
    public function testSetPropertiesException()
    {
        $object = new Field('PAGE');
        $object->setProperties(array('foo' => 'bar'));
    }

    /**
     * Test setOptions exception
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid option
     */
    public function testSetOptionsException()
    {
        $object = new Field('PAGE');
        $object->setOptions(array('foo' => 'bar'));
    }
}
