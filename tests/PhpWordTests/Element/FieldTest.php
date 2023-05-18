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
use PhpOffice\PhpWord\Element\Field;
use PhpOffice\PhpWord\Element\TextRun;

/**
 * Test class for PhpOffice\PhpWord\Element\Field.
 *
 * @runTestsInSeparateProcesses
 */
class FieldTest extends \PHPUnit\Framework\TestCase
{
    /**
     * New instance.
     */
    public function testConstructNull(): void
    {
        $oField = new Field();

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Field', $oField);
    }

    /**
     * New instance with type.
     */
    public function testConstructWithType(): void
    {
        $oField = new Field('DATE');

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Field', $oField);
        self::assertEquals('DATE', $oField->getType());
    }

    /**
     * New instance with type and properties.
     */
    public function testConstructWithTypeProperties(): void
    {
        $oField = new Field('DATE', ['dateformat' => 'd-M-yyyy']);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Field', $oField);
        self::assertEquals('DATE', $oField->getType());
        self::assertEquals(['dateformat' => 'd-M-yyyy'], $oField->getProperties());
    }

    /**
     * New instance with type and properties and options.
     */
    public function testConstructWithTypePropertiesOptions(): void
    {
        $oField = new Field('DATE', ['dateformat' => 'd-M-yyyy'], ['SakaEraCalendar', 'PreserveFormat']);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Field', $oField);
        self::assertEquals('DATE', $oField->getType());
        self::assertEquals(['dateformat' => 'd-M-yyyy'], $oField->getProperties());
        self::assertEquals(['SakaEraCalendar', 'PreserveFormat'], $oField->getOptions());
    }

    /**
     * New instance with type and properties and options and text.
     */
    public function testConstructWithTypePropertiesOptionsText(): void
    {
        $oField = new Field('XE', [], ['Bold', 'Italic'], 'FieldValue');

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Field', $oField);
        self::assertEquals('XE', $oField->getType());
        self::assertEquals([], $oField->getProperties());
        self::assertEquals(['Bold', 'Italic'], $oField->getOptions());
        self::assertEquals('FieldValue', $oField->getText());
    }

    /**
     * New instance with type and properties and options and text as TextRun.
     */
    public function testConstructWithTypePropertiesOptionsTextAsTextRun(): void
    {
        $textRun = new TextRun();
        $textRun->addText('test string');

        $oField = new Field('XE', [], ['Bold', 'Italic'], $textRun);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Field', $oField);
        self::assertEquals('XE', $oField->getType());
        self::assertEquals([], $oField->getProperties());
        self::assertEquals(['Bold', 'Italic'], $oField->getOptions());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $oField->getText());
    }

    public function testConstructWithOptionValue(): void
    {
        $oField = new Field('INDEX', [], ['\\c "3" \\h "A"']);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Field', $oField);
        self::assertEquals('INDEX', $oField->getType());
        self::assertEquals([], $oField->getProperties());
        self::assertEquals(['\\c "3" \\h "A"'], $oField->getOptions());
    }

    /**
     * Test setType exception.
     */
    public function testSetTypeException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type');
        $object = new Field();
        $object->setType('foo');
    }

    /**
     * Test setProperties exception.
     */
    public function testSetPropertiesException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid property');
        $object = new Field('PAGE');
        $object->setProperties(['foo' => 'bar']);
    }

    /**
     * Test setOptions exception.
     */
    public function testSetOptionsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid option');
        $object = new Field('PAGE');
        $object->setOptions(['foo' => 'bar']);
    }

    /**
     * Test setText exception.
     */
    public function testSetTextException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid text');
        $object = new Field('XE');
        $object->setText([]);
    }
}
