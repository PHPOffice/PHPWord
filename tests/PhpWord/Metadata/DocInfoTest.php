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
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Metadata;

/**
 * Test class for PhpOffice\PhpWord\Metadata\DocInfo
 *
 * @runTestsInSeparateProcesses
 */
class DocInfoTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Creator
     */
    public function testCreator()
    {
        $oProperties = new DocInfo();
        $oProperties->setCreator();
        $this->assertEquals('', $oProperties->getCreator());

        $oProperties->setCreator('AAA');
        $this->assertEquals('AAA', $oProperties->getCreator());
    }

    /**
     * Last modified by
     */
    public function testLastModifiedBy()
    {
        $oProperties = new DocInfo();
        $oProperties->setLastModifiedBy();
        $this->assertEquals('', $oProperties->getLastModifiedBy());

        $oProperties->setLastModifiedBy('AAA');
        $this->assertEquals('AAA', $oProperties->getLastModifiedBy());
    }

    /**
     * Created
     */
    public function testCreated()
    {
        $oProperties = new DocInfo();
        $oProperties->setCreated();
        $this->assertEquals(time(), $oProperties->getCreated());

        $iTime = time() + 3600;
        $oProperties->setCreated($iTime);
        $this->assertEquals($iTime, $oProperties->getCreated());
    }

    /**
     * Modified
     */
    public function testModified()
    {
        $oProperties = new DocInfo();
        $oProperties->setModified();
        $this->assertEquals(time(), $oProperties->getModified());

        $iTime = time() + 3600;
        $oProperties->setModified($iTime);
        $this->assertEquals($iTime, $oProperties->getModified());
    }

    /**
     * Title
     */
    public function testTitle()
    {
        $oProperties = new DocInfo();
        $oProperties->setTitle();
        $this->assertEquals('', $oProperties->getTitle());

        $oProperties->setTitle('AAA');
        $this->assertEquals('AAA', $oProperties->getTitle());
    }

    /**
     * Description
     */
    public function testDescription()
    {
        $oProperties = new DocInfo();
        $oProperties->setDescription();
        $this->assertEquals('', $oProperties->getDescription());

        $oProperties->setDescription('AAA');
        $this->assertEquals('AAA', $oProperties->getDescription());
    }

    /**
     * Subject
     */
    public function testSubject()
    {
        $oProperties = new DocInfo();
        $oProperties->setSubject();
        $this->assertEquals('', $oProperties->getSubject());

        $oProperties->setSubject('AAA');
        $this->assertEquals('AAA', $oProperties->getSubject());
    }

    /**
     * Keywords
     */
    public function testKeywords()
    {
        $oProperties = new DocInfo();
        $oProperties->setKeywords();
        $this->assertEquals('', $oProperties->getKeywords());

        $oProperties->setKeywords('AAA');
        $this->assertEquals('AAA', $oProperties->getKeywords());
    }

    /**
     * Category
     */
    public function testCategory()
    {
        $oProperties = new DocInfo();
        $oProperties->setCategory();
        $this->assertEquals('', $oProperties->getCategory());

        $oProperties->setCategory('AAA');
        $this->assertEquals('AAA', $oProperties->getCategory());
    }

    /**
     * Company
     */
    public function testCompany()
    {
        $oProperties = new DocInfo();
        $oProperties->setCompany();
        $this->assertEquals('', $oProperties->getCompany());

        $oProperties->setCompany('AAA');
        $this->assertEquals('AAA', $oProperties->getCompany());
    }

    /**
     * Manager
     */
    public function testManager()
    {
        $oProperties = new DocInfo();
        $oProperties->setManager();
        $this->assertEquals('', $oProperties->getManager());

        $oProperties->setManager('AAA');
        $this->assertEquals('AAA', $oProperties->getManager());
    }

    /**
     * Custom properties
     */
    public function testCustomProperty()
    {
        $oProperties = new DocInfo();
        $oProperties->setCustomProperty('key1', null);
        $oProperties->setCustomProperty('key2', true);
        $oProperties->setCustomProperty('key3', 3);
        $oProperties->setCustomProperty('key4', 4.4);
        $oProperties->setCustomProperty('key5', 'value5');
        $this->assertEquals(DocInfo::PROPERTY_TYPE_STRING, $oProperties->getCustomPropertyType('key1'));
        $this->assertEquals(DocInfo::PROPERTY_TYPE_BOOLEAN, $oProperties->getCustomPropertyType('key2'));
        $this->assertEquals(DocInfo::PROPERTY_TYPE_INTEGER, $oProperties->getCustomPropertyType('key3'));
        $this->assertEquals(DocInfo::PROPERTY_TYPE_FLOAT, $oProperties->getCustomPropertyType('key4'));
        $this->assertEquals(DocInfo::PROPERTY_TYPE_STRING, $oProperties->getCustomPropertyType('key5'));
        $this->assertNull($oProperties->getCustomPropertyType('key6'));
        $this->assertNull($oProperties->getCustomPropertyValue('key1'));
        $this->assertTrue($oProperties->getCustomPropertyValue('key2'));
        $this->assertEquals(3, $oProperties->getCustomPropertyValue('key3'));
        $this->assertEquals(4.4, $oProperties->getCustomPropertyValue('key4'));
        $this->assertEquals('value5', $oProperties->getCustomPropertyValue('key5'));
        $this->assertNull($oProperties->getCustomPropertyValue('key6'));
        $this->assertTrue($oProperties->isCustomPropertySet('key5'));
        $this->assertNotTrue($oProperties->isCustomPropertySet('key6'));
        $this->assertEquals(array('key1', 'key2', 'key3', 'key4', 'key5'), $oProperties->getCustomProperties());
    }

    /**
     * Convert property
     */
    public function testConvertProperty()
    {
        $this->assertEquals('', DocInfo::convertProperty('a', 'empty'));
        $this->assertNull(DocInfo::convertProperty('a', 'null'));
        $this->assertEquals(8, DocInfo::convertProperty('8', 'int'));
        $this->assertEquals(8, DocInfo::convertProperty('8.3', 'uint'));
        $this->assertEquals(8.3, DocInfo::convertProperty('8.3', 'decimal'));
        $this->assertEquals('8.3', DocInfo::convertProperty('8.3', 'lpstr'));
        $this->assertEquals(strtotime('10/11/2013'), DocInfo::convertProperty('10/11/2013', 'date'));
        $this->assertTrue(DocInfo::convertProperty('true', 'bool'));
        $this->assertNotTrue(DocInfo::convertProperty('1', 'bool'));
        $this->assertEquals('1', DocInfo::convertProperty('1', 'array'));
        $this->assertEquals('1', DocInfo::convertProperty('1', ''));

        $this->assertEquals(DocInfo::PROPERTY_TYPE_INTEGER, DocInfo::convertPropertyType('int'));
        $this->assertEquals(DocInfo::PROPERTY_TYPE_INTEGER, DocInfo::convertPropertyType('uint'));
        $this->assertEquals(DocInfo::PROPERTY_TYPE_FLOAT, DocInfo::convertPropertyType('decimal'));
        $this->assertEquals(DocInfo::PROPERTY_TYPE_STRING, DocInfo::convertPropertyType('lpstr'));
        $this->assertEquals(DocInfo::PROPERTY_TYPE_DATE, DocInfo::convertPropertyType('date'));
        $this->assertEquals(DocInfo::PROPERTY_TYPE_BOOLEAN, DocInfo::convertPropertyType('bool'));
        $this->assertEquals(DocInfo::PROPERTY_TYPE_UNKNOWN, DocInfo::convertPropertyType('array'));
        $this->assertEquals(DocInfo::PROPERTY_TYPE_UNKNOWN, DocInfo::convertPropertyType(''));
    }
}
