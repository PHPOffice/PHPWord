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

namespace PhpOffice\PhpWordTests\Reader\Word2007;

use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Reader\Word2007 as DocxReader;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Table as TableStyle;

/**
 * Test class for PhpOffice\PhpWord\Reader\Word2007\Styles.
 * Run in separate process because doc changes default font.
 *
 * @runTestsInSeparateProcesses
 */
class StyleTableTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test reading of table layout.
     */
    public function testReadTableWithStyleOverrides(): void
    {
        $file = 'tests/PhpWordTests/_files/documents/word.2474.docx';
        $reader = new DocxReader();
        $phpWord = $reader->load($file);
        self::assertSame('Times New Roman', $phpWord->getDefaultFontName());

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf(Table::class, $elements[2]);
        $style = $elements[2]->getStyle();
        self::assertIsObject($style);
        self::assertSame('Tablaconcuadrcula', $style->getTblStyle());
        self::assertSame('none', $style->getBorderTopStyle());
        $baseStyle = Style::getStyle('Tablaconcuadrcula');
        self::assertInstanceOf(TableStyle::class, $baseStyle);
        self::assertSame('Table Grid', $baseStyle->getStyleName());
        self::assertSame('Tablanormal', $baseStyle->getBasedOn());
        self::assertSame('single', $baseStyle->getBorderTopStyle());
    }
}
