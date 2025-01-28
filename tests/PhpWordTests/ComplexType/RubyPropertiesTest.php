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

namespace PhpOffice\PhpWordTests\ComplexType;

use InvalidArgumentException;
use PhpOffice\PhpWord\ComplexType\RubyProperties;

/**
 * Test class for PhpOffice\PhpWord\ComplexType\RubyProperties.
 *
 * @runTestsInSeparateProcesses
 */
class RubyPropertiesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test new instance.
     */
    public function testConstruct(): void
    {
        $properties = new RubyProperties();
        self::assertInstanceOf('PhpOffice\\PhpWord\\ComplexType\\RubyProperties', $properties);

        self::assertIsString($properties->getAlignment());
        self::assertTrue($properties->getAlignment() !== '' && $properties->getAlignment() !== null);
        self::assertIsFloat($properties->getFontFaceSize());
        self::assertIsFloat($properties->getFontPointsAboveBaseText());
        self::assertIsFloat($properties->getFontSizeForBaseText());
        self::assertIsString($properties->getLanguageId());
        self::assertTrue($properties->getLanguageId() !== '' && $properties->getLanguageId() !== null);
    }

    /**
     * Get/set alignment.
     */
    public function testAlignment(): void
    {
        $properties = new RubyProperties();
        self::assertIsString($properties->getAlignment());
        self::assertTrue($properties->getAlignment() !== '' && $properties->getAlignment() !== null);
        $properties->setAlignment(RubyProperties::ALIGNMENT_RIGHT_VERTICAL);
        self::assertEquals(RubyProperties::ALIGNMENT_RIGHT_VERTICAL, $properties->getAlignment());
    }

    /**
     * Set valid alignments. Make sure we can set all valid types - should not throw exception.
     */
    public function testValidAlignments(): void
    {
        $properties = new RubyProperties();
        $types = [
            RubyProperties::ALIGNMENT_CENTER,
            RubyProperties::ALIGNMENT_DISTRIBUTE_LETTER,
            RubyProperties::ALIGNMENT_DISTRIBUTE_SPACE,
            RubyProperties::ALIGNMENT_LEFT,
            RubyProperties::ALIGNMENT_RIGHT,
            RubyProperties::ALIGNMENT_RIGHT_VERTICAL,
        ];
        foreach ($types as $type) {
            $properties->setAlignment($type);
            self::assertEquals($type, $properties->getAlignment());
        }
    }

    /**
     * Test throws exception on invalid alignment type.
     */
    public function testInvalidAlignment(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $properties = new RubyProperties();
        $properties->setAlignment('invalid alignment type');
    }

    /**
     * Get/set font face size.
     */
    public function testFontFaceSize(): void
    {
        $properties = new RubyProperties();

        self::assertTrue($properties->getFontFaceSize() > 0);
        $properties->setFontFaceSize(42.42);
        self::assertEqualsWithDelta(42.42, $properties->getFontFaceSize(), 0.00001); // use delta as it is a float compare
        self::assertIsFloat($properties->getFontFaceSize());
    }

    /**
     * Get/set font points above base text.
     */
    public function testFontPointsAboveBaseText(): void
    {
        $properties = new RubyProperties();

        self::assertTrue($properties->getFontPointsAboveBaseText() > 0);
        $properties->setFontPointsAboveBaseText(43.42);
        self::assertEqualsWithDelta(43.42, $properties->getFontPointsAboveBaseText(), 0.00001); // use delta as it is a float compare
        self::assertIsFloat($properties->getFontPointsAboveBaseText());
    }

    /**
     * Get/set font size for base text.
     */
    public function testFontSizeForBaseText(): void
    {
        $properties = new RubyProperties();

        self::assertTrue($properties->getFontSizeForBaseText() > 0);
        $properties->setFontSizeForBaseText(45.42);
        self::assertEqualsWithDelta(45.42, $properties->getFontSizeForBaseText(), 0.00001); // use delta as it is a float compare
        self::assertIsFloat($properties->getFontSizeForBaseText());
    }

    /**
     * Get/set language id.
     */
    public function testLanguageId(): void
    {
        $properties = new RubyProperties();

        self::assertTrue($properties->getLanguageId() !== '' && $properties->getLanguageId() !== null);
        $properties->setLanguageId('en-US');
        self::assertIsString($properties->getLanguageId());
        self::assertEquals('en-US', $properties->getLanguageId());
    }
}
