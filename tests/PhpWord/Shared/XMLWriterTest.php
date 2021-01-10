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

namespace PhpOffice\PhpWord\Shared;

/**
 * Test class for XMLWriter
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\XMLWriter
 */
class XMLWriterTest extends \PHPUnit\Framework\TestCase
{
    public function testConstruct()
    {
        // Memory
        $object = new XMLWriter();
        $object->startElement('element');
        $object->text('AAA');
        $object->endElement();
        $this->assertEquals('<element>AAA</element>' . chr(10), $object->getData());

        // Disk
        $object = new XMLWriter(XMLWriter::STORAGE_DISK);
        $object->startElement('element');
        $object->text('BBB');
        $object->endElement();
        $this->assertEquals('<element>BBB</element>' . chr(10), $object->getData());
    }

    public function testWriteAttribute()
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->startElement('element');
        $xmlWriter->writeAttribute('name', 'value');
        $xmlWriter->endElement();

        $this->assertSame('<element name="value"/>' . chr(10), $xmlWriter->getData());
    }

    public function testWriteAttributeShouldWriteFloatValueLocaleIndependent()
    {
        $value = 1.2;

        $xmlWriter = new XMLWriter();
        $xmlWriter->startElement('element');
        $xmlWriter->writeAttribute('name', $value);
        $xmlWriter->endElement();

        $currentLocale = setlocale(LC_NUMERIC, 0);

        setlocale(LC_NUMERIC, 'de_DE.UTF-8', 'de');

        $this->assertSame('<element name="1.2"/>' . chr(10), $xmlWriter->getData());

        setlocale(LC_NUMERIC, $currentLocale);
    }
}
