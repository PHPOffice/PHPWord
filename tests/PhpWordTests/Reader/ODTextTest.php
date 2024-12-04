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

namespace PhpOffice\PhpWordTests\Reader;

use PhpOffice\Math\Element;
use PhpOffice\PhpWord\Element\Formula;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

/**
 * Test class for PhpOffice\PhpWord\Reader\ODText.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Reader\ODText
 *
 * @runTestsInSeparateProcesses
 */
class ODTextTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Load.
     */
    public function testLoad(): void
    {
        $phpWord = IOFactory::load(dirname(__DIR__, 1) . '/_files/documents/reader.odt', 'ODText');
        self::assertInstanceOf(PhpWord::class, $phpWord);
    }

    public function testLoadFormula(): void
    {
        $phpWord = IOFactory::load(dirname(__DIR__, 1) . '/_files/documents/reader-formula.odt', 'ODText');

        self::assertInstanceOf(PhpWord::class, $phpWord);

        $sections = $phpWord->getSections();
        self::assertCount(1, $sections);

        $section = $sections[0];
        self::assertInstanceOf(Section::class, $section);

        $elements = $section->getElements();
        self::assertCount(1, $elements);

        $element = $elements[0];
        self::assertInstanceOf(Formula::class, $element);

        $elements = $element->getMath()->getElements();
        self::assertCount(1, $elements);

        self::assertInstanceOf(Element\Semantics::class, $elements[0]);
    }
}
