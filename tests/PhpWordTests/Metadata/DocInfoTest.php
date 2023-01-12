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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\Metadata;

use PhpOffice\PhpWord\Metadata\DocInfo;

/**
 * Test class for PhpOffice\PhpWord\Metadata\DocInfo.
 *
 * @runTestsInSeparateProcesses
 */
class DocInfoTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Creator.
     */
    public function testCreator(): void
    {
        $oProperties = new DocInfo();
        $oProperties->setCreator();
        self::assertEquals('', $oProperties->getCreator());

        $oProperties->setCreator('AAA');
        self::assertEquals('AAA', $oProperties->getCreator());
    }

    /**
     * Last modified by.
     */
    public function testLastModifiedBy(): void
    {
        $oProperties = new DocInfo();
        $oProperties->setLastModifiedBy();
        self::assertEquals('', $oProperties->getLastModifiedBy());

        $oProperties->setLastModifiedBy('AAA');
        self::assertEquals('AAA', $oProperties->getLastModifiedBy());
    }

    /**
     * Created.
     */
    public function testCreated(): void
    {
        $oProperties = new DocInfo();
        $oProperties->setCreated();
        self::assertEquals(time(), $oProperties->getCreated());

        $iTime = time() + 3600;
        $oProperties->setCreated($iTime);
        self::assertEquals($iTime, $oProperties->getCreated());
    }

    /**
     * Modified.
     */
    public function testModified(): void
    {
        $oProperties = new DocInfo();
        $oProperties->setModified();
        self::assertEquals(time(), $oProperties->getModified());

        $iTime = time() + 3600;
        $oProperties->setModified($iTime);
        self::assertEquals($iTime, $oProperties->getModified());
    }

    /**
     * Title.
     */
    public function testTitle(): void
    {
        $oProperties = new DocInfo();
        $oProperties->setTitle();
        self::assertEquals('', $oProperties->getTitle());

        $oProperties->setTitle('AAA');
        self::assertEquals('AAA', $oProperties->getTitle());
    }

    /**
     * Description.
     */
    public function testDescription(): void
    {
        $oProperties = new DocInfo();
        $oProperties->setDescription();
        self::assertEquals('', $oProperties->getDescription());

        $oProperties->setDescription('AAA');
        self::assertEquals('AAA', $oProperties->getDescription());
    }

    /**
     * Subject.
     */
    public function testSubject(): void
    {
        $oProperties = new DocInfo();
        $oProperties->setSubject();
        self::assertEquals('', $oProperties->getSubject());

        $oProperties->setSubject('AAA');
        self::assertEquals('AAA', $oProperties->getSubject());
    }

    /**
     * Keywords.
     */
    public function testKeywords(): void
    {
        $oProperties = new DocInfo();
        $oProperties->setKeywords();
        self::assertEquals('', $oProperties->getKeywords());

        $oProperties->setKeywords('AAA');
        self::assertEquals('AAA', $oProperties->getKeywords());
    }

    /**
     * Category.
     */
    public function testCategory(): void
    {
        $oProperties = new DocInfo();
        $oProperties->setCategory();
        self::assertEquals('', $oProperties->getCategory());

        $oProperties->setCategory('AAA');
        self::assertEquals('AAA', $oProperties->getCategory());
    }

    /**
     * Company.
     */
    public function testCompany(): void
    {
        $oProperties = new DocInfo();
        $oProperties->setCompany();
        self::assertEquals('', $oProperties->getCompany());

        $oProperties->setCompany('AAA');
        self::assertEquals('AAA', $oProperties->getCompany());
    }

    /**
     * Manager.
     */
    public function testManager(): void
    {
        $oProperties = new DocInfo();
        $oProperties->setManager();
        self::assertEquals('', $oProperties->getManager());

        $oProperties->setManager('AAA');
        self::assertEquals('AAA', $oProperties->getManager());
    }

    /**
     * Custom properties.
     */
    public function testCustomProperty(): void
    {
        $oProperties = new DocInfo();
        $oProperties->setCustomProperty('key1', null);
        $oProperties->setCustomProperty('key2', true);
        $oProperties->setCustomProperty('key3', 3);
        $oProperties->setCustomProperty('key4', 4.4);
        $oProperties->setCustomProperty('key5', 'value5');
        self::assertEquals(DocInfo::PROPERTY_TYPE_STRING, $oProperties->getCustomPropertyType('key1'));
        self::assertEquals(DocInfo::PROPERTY_TYPE_BOOLEAN, $oProperties->getCustomPropertyType('key2'));
        self::assertEquals(DocInfo::PROPERTY_TYPE_INTEGER, $oProperties->getCustomPropertyType('key3'));
        self::assertEquals(DocInfo::PROPERTY_TYPE_FLOAT, $oProperties->getCustomPropertyType('key4'));
        self::assertEquals(DocInfo::PROPERTY_TYPE_STRING, $oProperties->getCustomPropertyType('key5'));
        self::assertNull($oProperties->getCustomPropertyType('key6'));
        self::assertNull($oProperties->getCustomPropertyValue('key1'));
        self::assertTrue($oProperties->getCustomPropertyValue('key2'));
        self::assertEquals(3, $oProperties->getCustomPropertyValue('key3'));
        self::assertEquals(4.4, $oProperties->getCustomPropertyValue('key4'));
        self::assertEquals('value5', $oProperties->getCustomPropertyValue('key5'));
        self::assertNull($oProperties->getCustomPropertyValue('key6'));
        self::assertTrue($oProperties->isCustomPropertySet('key5'));
        self::assertNotTrue($oProperties->isCustomPropertySet('key6'));
        self::assertEquals(['key1', 'key2', 'key3', 'key4', 'key5'], $oProperties->getCustomProperties());
    }

    /**
     * Convert property.
     */
    public function testConvertProperty(): void
    {
        self::assertEquals('', DocInfo::convertProperty('a', 'empty'));
        self::assertNull(DocInfo::convertProperty('a', 'null'));
        self::assertEquals(8, DocInfo::convertProperty('8', 'int'));
        self::assertEquals(8, DocInfo::convertProperty('8.3', 'uint'));
        self::assertEquals(8.3, DocInfo::convertProperty('8.3', 'decimal'));
        self::assertEquals('8.3', DocInfo::convertProperty('8.3', 'lpstr'));
        self::assertEquals(strtotime('10/11/2013'), DocInfo::convertProperty('10/11/2013', 'date'));
        self::assertTrue(DocInfo::convertProperty('true', 'bool'));
        self::assertNotTrue(DocInfo::convertProperty('1', 'bool'));
        self::assertEquals('1', DocInfo::convertProperty('1', 'array'));
        self::assertEquals('1', DocInfo::convertProperty('1', ''));

        self::assertEquals(DocInfo::PROPERTY_TYPE_INTEGER, DocInfo::convertPropertyType('int'));
        self::assertEquals(DocInfo::PROPERTY_TYPE_INTEGER, DocInfo::convertPropertyType('uint'));
        self::assertEquals(DocInfo::PROPERTY_TYPE_FLOAT, DocInfo::convertPropertyType('decimal'));
        self::assertEquals(DocInfo::PROPERTY_TYPE_STRING, DocInfo::convertPropertyType('lpstr'));
        self::assertEquals(DocInfo::PROPERTY_TYPE_DATE, DocInfo::convertPropertyType('date'));
        self::assertEquals(DocInfo::PROPERTY_TYPE_BOOLEAN, DocInfo::convertPropertyType('bool'));
        self::assertEquals(DocInfo::PROPERTY_TYPE_UNKNOWN, DocInfo::convertPropertyType('array'));
        self::assertEquals(DocInfo::PROPERTY_TYPE_UNKNOWN, DocInfo::convertPropertyType(''));
    }
}
