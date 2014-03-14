<?php
namespace PHPWord\Tests;

use PHPWord_DocumentProperties;

class DocumentPropertiesTest extends \PHPUnit_Framework_TestCase
{
    public function testCreator()
    {
      $oProperties = new PHPWord_DocumentProperties();
      $oProperties->setCreator();
      $this->assertEquals('', $oProperties->getCreator());

      $oProperties->setCreator('AAA');
      $this->assertEquals('AAA', $oProperties->getCreator());
    }

    public function testLastModifiedBy()
    {
      $oProperties = new PHPWord_DocumentProperties();
      $oProperties->setLastModifiedBy();
      $this->assertEquals('', $oProperties->getLastModifiedBy());

      $oProperties->setLastModifiedBy('AAA');
      $this->assertEquals('AAA', $oProperties->getLastModifiedBy());
    }

    public function testCreated()
    {
      $oProperties = new PHPWord_DocumentProperties();
      $oProperties->setCreated();
      $this->assertEquals(time(), $oProperties->getCreated());

      $iTime = time() + 3600;
      $oProperties->setCreated($iTime);
      $this->assertEquals($iTime, $oProperties->getCreated());
    }

    public function testModified()
    {
      $oProperties = new PHPWord_DocumentProperties();
      $oProperties->setModified();
      $this->assertEquals(time(), $oProperties->getModified());

      $iTime = time() + 3600;
      $oProperties->setModified($iTime);
      $this->assertEquals($iTime, $oProperties->getModified());
    }

    public function testTitle()
    {
      $oProperties = new PHPWord_DocumentProperties();
      $oProperties->setTitle();
      $this->assertEquals('', $oProperties->getTitle());

      $oProperties->setTitle('AAA');
      $this->assertEquals('AAA', $oProperties->getTitle());
    }

    public function testDescription()
    {
      $oProperties = new PHPWord_DocumentProperties();
      $oProperties->setDescription();
      $this->assertEquals('', $oProperties->getDescription());

      $oProperties->setDescription('AAA');
      $this->assertEquals('AAA', $oProperties->getDescription());
    }

    public function testSubject()
    {
      $oProperties = new PHPWord_DocumentProperties();
      $oProperties->setSubject();
      $this->assertEquals('', $oProperties->getSubject());

      $oProperties->setSubject('AAA');
      $this->assertEquals('AAA', $oProperties->getSubject());
    }

    public function testKeywords()
    {
      $oProperties = new PHPWord_DocumentProperties();
      $oProperties->setKeywords();
      $this->assertEquals('', $oProperties->getKeywords());

      $oProperties->setKeywords('AAA');
      $this->assertEquals('AAA', $oProperties->getKeywords());
    }

    public function testCategory()
    {
      $oProperties = new PHPWord_DocumentProperties();
      $oProperties->setCategory();
      $this->assertEquals('', $oProperties->getCategory());

      $oProperties->setCategory('AAA');
      $this->assertEquals('AAA', $oProperties->getCategory());
    }

    public function testCompany()
    {
      $oProperties = new PHPWord_DocumentProperties();
      $oProperties->setCompany();
      $this->assertEquals('', $oProperties->getCompany());

      $oProperties->setCompany('AAA');
      $this->assertEquals('AAA', $oProperties->getCompany());
    }

    public function testManager()
    {
      $oProperties = new PHPWord_DocumentProperties();
      $oProperties->setManager();
      $this->assertEquals('', $oProperties->getManager());

      $oProperties->setManager('AAA');
      $this->assertEquals('AAA', $oProperties->getManager());
    }

    public function testCustomProperty()
    {
      $oProperties = new PHPWord_DocumentProperties();
      $oProperties->setCustomProperty('key1', null);
      $oProperties->setCustomProperty('key2', true);
      $oProperties->setCustomProperty('key3', 3);
      $oProperties->setCustomProperty('key4', 4.4);
      $oProperties->setCustomProperty('key5', 'value5');
      $this->assertEquals(PHPWord_DocumentProperties::PROPERTY_TYPE_STRING, $oProperties->getCustomPropertyType('key1'));
      $this->assertEquals(PHPWord_DocumentProperties::PROPERTY_TYPE_BOOLEAN, $oProperties->getCustomPropertyType('key2'));
      $this->assertEquals(PHPWord_DocumentProperties::PROPERTY_TYPE_INTEGER, $oProperties->getCustomPropertyType('key3'));
      $this->assertEquals(PHPWord_DocumentProperties::PROPERTY_TYPE_FLOAT, $oProperties->getCustomPropertyType('key4'));
      $this->assertEquals(PHPWord_DocumentProperties::PROPERTY_TYPE_STRING, $oProperties->getCustomPropertyType('key5'));
      $this->assertEquals(null, $oProperties->getCustomPropertyType('key6'));
      $this->assertEquals(null, $oProperties->getCustomPropertyValue('key1'));
      $this->assertEquals(true, $oProperties->getCustomPropertyValue('key2'));
      $this->assertEquals(3, $oProperties->getCustomPropertyValue('key3'));
      $this->assertEquals(4.4, $oProperties->getCustomPropertyValue('key4'));
      $this->assertEquals('value5', $oProperties->getCustomPropertyValue('key5'));
      $this->assertEquals(null, $oProperties->getCustomPropertyValue('key6'));
      $this->assertEquals(true, $oProperties->isCustomPropertySet('key5'));
      $this->assertEquals(false, $oProperties->isCustomPropertySet('key6'));
      $this->assertEquals(array('key1', 'key2', 'key3', 'key4', 'key5'), $oProperties->getCustomProperties());
    }

    public function testConvertProperty()
    {
      $this->assertEquals('', PHPWord_DocumentProperties::convertProperty('a', 'empty'));
      $this->assertEquals(null, PHPWord_DocumentProperties::convertProperty('a', 'null'));
      $this->assertEquals(8, PHPWord_DocumentProperties::convertProperty('8', 'int'));
      $this->assertEquals(8, PHPWord_DocumentProperties::convertProperty('8.3', 'uint'));
      $this->assertEquals(8.3, PHPWord_DocumentProperties::convertProperty('8.3', 'decimal'));
      $this->assertEquals('8.3', PHPWord_DocumentProperties::convertProperty('8.3', 'lpstr'));
      $this->assertEquals(strtotime('10/11/2013'), PHPWord_DocumentProperties::convertProperty('10/11/2013', 'date'));
      $this->assertEquals(true, PHPWord_DocumentProperties::convertProperty('true', 'bool'));
      $this->assertEquals(false, PHPWord_DocumentProperties::convertProperty('1', 'bool'));
      $this->assertEquals('1', PHPWord_DocumentProperties::convertProperty('1', 'array'));
      $this->assertEquals('1', PHPWord_DocumentProperties::convertProperty('1', ''));


      $this->assertEquals(PHPWord_DocumentProperties::PROPERTY_TYPE_INTEGER, PHPWord_DocumentProperties::convertPropertyType('int'));
      $this->assertEquals(PHPWord_DocumentProperties::PROPERTY_TYPE_INTEGER, PHPWord_DocumentProperties::convertPropertyType('uint'));
      $this->assertEquals(PHPWord_DocumentProperties::PROPERTY_TYPE_FLOAT, PHPWord_DocumentProperties::convertPropertyType('decimal'));
      $this->assertEquals(PHPWord_DocumentProperties::PROPERTY_TYPE_STRING, PHPWord_DocumentProperties::convertPropertyType('lpstr'));
      $this->assertEquals(PHPWord_DocumentProperties::PROPERTY_TYPE_DATE, PHPWord_DocumentProperties::convertPropertyType('date'));
      $this->assertEquals(PHPWord_DocumentProperties::PROPERTY_TYPE_BOOLEAN, PHPWord_DocumentProperties::convertPropertyType('bool'));
      $this->assertEquals(PHPWord_DocumentProperties::PROPERTY_TYPE_UNKNOWN, PHPWord_DocumentProperties::convertPropertyType('array'));
      $this->assertEquals(PHPWord_DocumentProperties::PROPERTY_TYPE_UNKNOWN, PHPWord_DocumentProperties::convertPropertyType(''));
    }
}
