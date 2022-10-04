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

namespace PhpOffice\PhpWordTests\Style;

use InvalidArgumentException;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Paragraph;
use ReflectionClass;

/**
 * Test class for PhpOffice\PhpWord\Style\AbstractStyle.
 *
 * @runTestsInSeparateProcesses
 */
class AbstractStyleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test set style by array.
     */
    public function testSetStyleByArray(): void
    {
        $stub = $this->getMockForAbstractClass('\PhpOffice\PhpWord\Style\AbstractStyle');
        $stub->setStyleByArray(['index' => 1]);

        self::assertEquals(1, $stub->getIndex());
    }

    public function testSetStyleByArrayWithAlign(): void
    {
        $stub = new Paragraph();
        $stub->setStyleByArray(['align' => Jc::CENTER]);

        self::assertEquals(Jc::CENTER, $stub->getAlignment());
    }

    public function testSetStyleByArrayWithAlignment(): void
    {
        $stub = new Paragraph();
        $stub->setStyleByArray(['alignment' => Jc::CENTER]);

        self::assertEquals(Jc::CENTER, $stub->getAlignment());
    }

    /**
     * Test setBoolVal, setIntVal, setFloatVal, setEnumVal with normal value.
     */
    public function testSetValNormal(): void
    {
        $stub = $this->getMockForAbstractClass('\PhpOffice\PhpWord\Style\AbstractStyle');

        self::assertTrue(self::callProtectedMethod($stub, 'setBoolVal', [true, false]));
        self::assertEquals(12, self::callProtectedMethod($stub, 'setIntVal', [12, 200]));
        self::assertEquals(871.1, self::callProtectedMethod($stub, 'setFloatVal', [871.1, 2.1]));
        self::assertEquals(871.1, self::callProtectedMethod($stub, 'setFloatVal', ['871.1', 2.1]));
        self::assertEquals('a', self::callProtectedMethod($stub, 'setEnumVal', ['a', ['a', 'b'], 'b']));
    }

    /**
     * Test setBoolVal, setIntVal, setFloatVal, setEnumVal with default value.
     */
    public function testSetValDefault(): void
    {
        $stub = $this->getMockForAbstractClass('\PhpOffice\PhpWord\Style\AbstractStyle');

        self::assertNotTrue(self::callProtectedMethod($stub, 'setBoolVal', ['a', false]));
        self::assertEquals(200, self::callProtectedMethod($stub, 'setIntVal', ['foo', 200]));
        self::assertEquals(2.1, self::callProtectedMethod($stub, 'setFloatVal', ['foo', 2.1]));
        self::assertEquals('b', self::callProtectedMethod($stub, 'setEnumVal', [null, ['a', 'b'], 'b']));
    }

    /**
     * Test setEnumVal exception.
     */
    public function testSetValEnumException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $stub = $this->getMockForAbstractClass('\PhpOffice\PhpWord\Style\AbstractStyle');

        self::assertEquals('b', self::callProtectedMethod($stub, 'setEnumVal', ['z', ['a', 'b'], 'b']));
    }

    /**
     * Helper function to call protected method.
     *
     * @param mixed $object
     * @param string $method
     */
    public static function callProtectedMethod($object, $method, array $args = [])
    {
        $class = new ReflectionClass(get_class($object));
        $method = $class->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $args);
    }
}
