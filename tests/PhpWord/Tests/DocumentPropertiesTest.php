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
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\DocumentProperties;

/**
 * Test class for PhpOffice\PhpWord\DocumentProperties
 *
 * @runTestsInSeparateProcesses
 */
class DocumentPropertiesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Creator
     */
    public function testCreator()
    {
        $oProperties = new DocumentProperties();
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
        $oProperties = new DocumentProperties();
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
        $oProperties = new DocumentProperties();
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
        $oProperties = new DocumentProperties();
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
        $oProperties = new DocumentProperties();
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
        $oProperties = new DocumentProperties();
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
        $oProperties = new DocumentProperties();
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
        $oProperties = new DocumentProperties();
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
        $oProperties = new DocumentProperties();
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
        $oProperties = new DocumentProperties();
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
        $oProperties = new DocumentProperties();
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
        $oProperties = new DocumentProperties();
        $oProperties->setCustomProperty('key1', null);
        $oProperties->setCustomProperty('key2', true);
        $oProperties->setCustomProperty('key3', 3);
        $oProperties->setCustomProperty('key4', 4.4);
        $oProperties->setCustomProperty('key5', 'value5');
        $this->assertEquals(
            DocumentProperties::PROPERTY_TYPE_STRING,
            $oProperties->getCustomPropertyType('key1')
        );
        $this->assertEquals(
            DocumentProperties::PROPERTY_TYPE_BOOLEAN,
            $oProperties->getCustomPropertyType('key2')
        );
        $this->assertEquals(
            DocumentProperties::PROPERTY_TYPE_INTEGER,
            $oProperties->getCustomPropertyType('key3')
        );
        $this->assertEquals(
            DocumentProperties::PROPERTY_TYPE_FLOAT,
            $oProperties->getCustomPropertyType('key4')
        );
        $this->assertEquals(
            DocumentProperties::PROPERTY_TYPE_STRING,
            $oProperties->getCustomPropertyType('key5')
        );
        $this->assertEquals(null, $oProperties->getCustomPropertyType('key6'));
        $this->assertEquals(null, $oProperties->getCustomPropertyValue('key1'));
        $this->assertEquals(true, $oProperties->getCustomPropertyValue('key2'));
        $this->assertEquals(3, $oProperties->getCustomPropertyValue('key3'));
        $this->assertEquals(4.4, $oProperties->getCustomPropertyValue('key4'));
        $this->assertEquals('value5', $oProperties->getCustomPropertyValue('key5'));
        $this->assertEquals(null, $oProperties->getCustomPropertyValue('key6'));
        $this->assertEquals(true, $oProperties->isCustomPropertySet('key5'));
        $this->assertEquals(false, $oProperties->isCustomPropertySet('key6'));
        $this->assertEquals(array(
            'key1',
            'key2',
            'key3',
            'key4',
            'key5'
        ), $oProperties->getCustomProperties());
    }

    /**
     * Convert property
     */
    public function testConvertProperty()
    {
        $this->assertEquals('', DocumentProperties::convertProperty('a', 'empty'));
        $this->assertEquals(null, DocumentProperties::convertProperty('a', 'null'));
        $this->assertEquals(8, DocumentProperties::convertProperty('8', 'int'));
        $this->assertEquals(8, DocumentProperties::convertProperty('8.3', 'uint'));
        $this->assertEquals(8.3, DocumentProperties::convertProperty('8.3', 'decimal'));
        $this->assertEquals('8.3', DocumentProperties::convertProperty('8.3', 'lpstr'));
        $this->assertEquals(strtotime('10/11/2013'), DocumentProperties::convertProperty('10/11/2013', 'date'));
        $this->assertEquals(true, DocumentProperties::convertProperty('true', 'bool'));
        $this->assertEquals(false, DocumentProperties::convertProperty('1', 'bool'));
        $this->assertEquals('1', DocumentProperties::convertProperty('1', 'array'));
        $this->assertEquals('1', DocumentProperties::convertProperty('1', ''));


        $this->assertEquals(
            DocumentProperties::PROPERTY_TYPE_INTEGER,
            DocumentProperties::convertPropertyType('int')
        );
        $this->assertEquals(
            DocumentProperties::PROPERTY_TYPE_INTEGER,
            DocumentProperties::convertPropertyType('uint')
        );
        $this->assertEquals(
            DocumentProperties::PROPERTY_TYPE_FLOAT,
            DocumentProperties::convertPropertyType('decimal')
        );
        $this->assertEquals(
            DocumentProperties::PROPERTY_TYPE_STRING,
            DocumentProperties::convertPropertyType('lpstr')
        );
        $this->assertEquals(
            DocumentProperties::PROPERTY_TYPE_DATE,
            DocumentProperties::convertPropertyType('date')
        );
        $this->assertEquals(
            DocumentProperties::PROPERTY_TYPE_BOOLEAN,
            DocumentProperties::convertPropertyType('bool')
        );
        $this->assertEquals(
            DocumentProperties::PROPERTY_TYPE_UNKNOWN,
            DocumentProperties::convertPropertyType('array')
        );
        $this->assertEquals(
            DocumentProperties::PROPERTY_TYPE_UNKNOWN,
            DocumentProperties::convertPropertyType('')
        );
    }
}
