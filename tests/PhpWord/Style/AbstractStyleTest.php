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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

/**
 * Test class for PhpOffice\PhpWord\Style\AbstractStyle
 *
 * @runTestsInSeparateProcesses
 */
class AbstractStyleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test set style by array
     */
    public function testSetStyleByArray()
    {
        $stub = $this->getMockForAbstractClass('\PhpOffice\PhpWord\Style\AbstractStyle');
        $stub->setStyleByArray(array('index' => 1));

        $this->assertEquals(1, $stub->getIndex());
    }

    /**
     * Test setBoolVal, setIntVal, setFloatVal, setEnumVal with normal value
     */
    public function testSetValNormal()
    {
        $stub = $this->getMockForAbstractClass('\PhpOffice\PhpWord\Style\AbstractStyle');

        $this->assertTrue(self::callProtectedMethod($stub, 'setBoolVal', array(true, false)));
        $this->assertEquals(12, self::callProtectedMethod($stub, 'setIntVal', array(12, 200)));
        $this->assertEquals(871.1, self::callProtectedMethod($stub, 'setFloatVal', array(871.1, 2.1)));
        $this->assertEquals(871.1, self::callProtectedMethod($stub, 'setFloatVal', array('871.1', 2.1)));
        $this->assertEquals('a', self::callProtectedMethod($stub, 'setEnumVal', array('a', array('a', 'b'), 'b')));
    }

    /**
     * Test setBoolVal, setIntVal, setFloatVal, setEnumVal with default value
     */
    public function testSetValDefault()
    {
        $stub = $this->getMockForAbstractClass('\PhpOffice\PhpWord\Style\AbstractStyle');

        $this->assertNotTrue(self::callProtectedMethod($stub, 'setBoolVal', array('a', false)));
        $this->assertEquals(200, self::callProtectedMethod($stub, 'setIntVal', array('foo', 200)));
        $this->assertEquals(2.1, self::callProtectedMethod($stub, 'setFloatVal', array('foo', 2.1)));
        $this->assertEquals('b', self::callProtectedMethod($stub, 'setEnumVal', array(null, array('a', 'b'), 'b')));
    }

    /**
     * Test setEnumVal exception
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetValEnumException()
    {
        $stub = $this->getMockForAbstractClass('\PhpOffice\PhpWord\Style\AbstractStyle');

        $this->assertEquals('b', self::callProtectedMethod($stub, 'setEnumVal', array('z', array('a', 'b'), 'b')));
    }

    /**
     * Helper function to call protected method
     *
     * @param mixed $object
     * @param string $method
     * @param array $args
     */
    public static function callProtectedMethod($object, $method, array $args = array())
    {
        $class = new \ReflectionClass(get_class($object));
        $method = $class->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $args);
    }
}
