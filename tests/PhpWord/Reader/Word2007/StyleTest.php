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

namespace PhpOffice\PhpWord\Reader\Word2007;

use PhpOffice\PhpWord\AbstractTestReader;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Style\Table;

/**
 * Test class for PhpOffice\PhpWord\Reader\Word2007\Styles
 */
class StyleTest extends AbstractTestReader
{
    /**
     * Test reading of table layout
     */
    public function testReadTableLayout()
    {
        $documentXml = '<w:tbl>
            <w:tblPr>
                <w:tblLayout w:type="fixed"/>
            </w:tblPr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString($documentXml);

        $elements = $this->get($phpWord->getSections(), 0)->getElements();
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        $this->assertInstanceOf('PhpOffice\PhpWord\Style\Table', $elements[0]->getStyle());
        $this->assertEquals(Table::LAYOUT_FIXED, $elements[0]->getStyle()->getLayout());
    }

    /**
     * Test reading of cell spacing
     */
    public function testReadCellSpacing()
    {
        $documentXml = '<w:tbl>
            <w:tblPr>
                <w:tblCellSpacing w:w="10.5" w:type="dxa"/>
            </w:tblPr>
        </w:tbl>';

        $phpWord = $this->getDocumentFromString($documentXml);

        $elements = $this->get($phpWord->getSections(), 0)->getElements();
        $this->assertInstanceOf('PhpOffice\PhpWord\Element\Table', $elements[0]);
        $this->assertInstanceOf('PhpOffice\PhpWord\Style\Table', $elements[0]->getStyle());
        $this->assertEquals(TblWidth::AUTO, $elements[0]->getStyle()->getUnit());
        /** @var \PhpOffice\PhpWord\Style\Table $tableStyle */
        $tableStyle = $elements[0]->getStyle();
        $this->assertEquals(10.5, $tableStyle->getCellSpacing());
    }
}
