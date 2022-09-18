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

namespace PhpOffice\PhpWordTests\Element;

use InvalidArgumentException;
use PhpOffice\PhpWord\Element\SDT;

/**
 * Test class for PhpOffice\PhpWord\Element\SDT.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\SDT
 */
class SDTTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Create new instance.
     */
    public function testConstruct(): void
    {
        $types = ['plainText', 'comboBox', 'dropDownList', 'date'];
        $type = $types[mt_rand(0, 3)];
        $value = mt_rand(0, 100);
        $alias = 'alias';
        $tag = 'my_tag';
        $object = new SDT($type);
        $object->setValue($value);
        $object->setListItems($types);
        $object->setAlias($alias);
        $object->setTag($tag);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\SDT', $object);
        self::assertEquals($type, $object->getType());
        self::assertEquals($types, $object->getListItems());
        self::assertEquals($value, $object->getValue());
        self::assertEquals($alias, $object->getAlias());
        self::assertEquals($tag, $object->getTag());
    }

    /**
     * Test set type exception.
     */
    public function testSetTypeException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid style value');
        $object = new SDT('comboBox');
        $object->setType('foo');
    }

    /**
     * Test set type.
     */
    public function testSetTypeNull(): void
    {
        $object = new SDT('comboBox');
        $object->setType(' ');

        self::assertEquals('comboBox', $object->getType());
    }
}
