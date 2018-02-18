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

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use PhpOffice\PhpWord\Style\Table;
use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Style\Table
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Style\Table
 * @runTestsInSeparateProcesses
 */
class TableTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test write styles
     */
    public function testTableLayout()
    {
        $tableStyle = new Table();
        $tableStyle->setLayout(Table::LAYOUT_FIXED);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable($tableStyle);
        $table->addRow();

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:tbl/w:tblPr/w:tblLayout';
        $this->assertTrue($doc->elementExists($path));
        $this->assertEquals(Table::LAYOUT_FIXED, $doc->getElementAttribute($path, 'w:type'));
    }

    /**
     * Test write styles
     */
    public function testCellSpacing()
    {
        $tableStyle = new Table();
        $tableStyle->setCellSpacing(10.3);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable($tableStyle);
        $table->addRow();

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:tbl/w:tblPr/w:tblCellSpacing';
        $this->assertTrue($doc->elementExists($path));
        $this->assertEquals(10.3, $doc->getElementAttribute($path, 'w:w'));
        $this->assertEquals(\PhpOffice\PhpWord\SimpleType\TblWidth::TWIP, $doc->getElementAttribute($path, 'w:type'));
    }
}
