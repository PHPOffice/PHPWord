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

namespace PhpOffice\PhpWordTests\Writer\Word2007\Style;

use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Style\Row.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Style\Row
 *
 * @runTestsInSeparateProcesses
 */
class RowTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * A row that repeats on every page says so in words.
     *
     * The SDK reads the value of `w:tblHeader` as an enumeration of `on` and
     * `off` and refuses to read `1`, which the schema's `ST_OnOff` allows.
     */
    public function testTableHeaderIsWrittenAsOn(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable();
        $row = $table->addRow();
        $row->getStyle()->setTblHeader(true);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:tbl/w:tr/w:trPr/w:tblHeader';
        self::assertTrue($doc->elementExists($path));
        self::assertSame('on', $doc->getElementAttribute($path, 'w:val'));
    }

    /**
     * A row that cannot be split says so in words.
     *
     * The SDK reads the value of `w:cantSplit` as an enumeration of `on` and
     * `off` and refuses to read `1`, which the schema's `ST_OnOff` allows.
     */
    public function testCantSplitIsWrittenAsOn(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable();
        $row = $table->addRow();
        $row->getStyle()->setCantSplit(true);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:tbl/w:tr/w:trPr/w:cantSplit';
        self::assertTrue($doc->elementExists($path));
        self::assertSame('on', $doc->getElementAttribute($path, 'w:val'));
    }
}
