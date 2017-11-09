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

namespace PhpOffice\PhpWord\Element;

/**
 * Test class for PhpOffice\PhpWord\Element\SDT
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\SDT
 */
class SDTTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Create new instance
     */
    public function testConstruct()
    {
        $types = array('comboBox', 'dropDownList', 'date');
        $type = $types[rand(0, 2)];
        $value = rand(0, 100);
        $alias = 'alias';
        $tag = 'my_tag';
        $object = new SDT($type);
        $object->setValue($value);
        $object->setListItems($types);
        $object->setAlias($alias);
        $object->setTag($tag);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\SDT', $object);
        $this->assertEquals($type, $object->getType());
        $this->assertEquals($types, $object->getListItems());
        $this->assertEquals($value, $object->getValue());
        $this->assertEquals($alias, $object->getAlias());
        $this->assertEquals($tag, $object->getTag());
    }

    /**
     * Test set type exception
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid style value
     */
    public function testSetTypeException()
    {
        $object = new SDT('comboBox');
        $object->setType('foo');
    }

    /**
     * Test set type
     */
    public function testSetTypeNull()
    {
        $object = new SDT('comboBox');
        $object->setType(' ');

        $this->assertEquals('comboBox', $object->getType());
    }
}
