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
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use ZipArchive;

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

    public function testTableInTheFormOfLibreOffice(): void
    {
        $cell = function (string $text, string $attributes = ''): string {
            return '<table:table-cell office:value-type="string"' . $attributes . '><text:p>' . $text . '</text:p></table:table-cell>';
        };
        $styles = '<style:style style:name="T.A" style:family="table-column"><style:table-column-properties style:column-width="1.0417in"/></style:style>'
            . '<style:style style:name="T.C" style:family="table-column"><style:table-column-properties style:column-width=".5in"/></style:style>';
        $body = '<table:table table:name="T"><table:table-column table:style-name="T.A" table:number-columns-repeated="2"/><table:table-column table:style-name="T.C"/>'
            . '<table:table-header-rows><table:table-row>' . $cell('H1') . $cell('H2') . $cell('H3') . '</table:table-row></table:table-header-rows>'
            . '<table:table-row>' . $cell('A+B', ' table:number-columns-spanned="2"') . '<table:covered-table-cell/>' . $cell('C down', ' table:number-rows-spanned="2"') . '</table:table-row>'
            . '<table:table-row-group><table:table-row>' . $cell('A2')
            . '<table:table-cell><text:p>B2</text:p><table:table><table:table-column table:number-columns-repeated="2"/><table:table-row>' . $cell('x') . $cell('y') . '</table:table-row></table:table></table:table-cell>'
            . '<table:covered-table-cell/></table:table-row></table:table-row-group>'
            . '<table:table-row>' . $cell('Wide', ' table:number-columns-spanned="2" table:number-rows-spanned="2"') . '<table:covered-table-cell/>' . $cell('C3') . '</table:table-row>'
            . '<table:table-row><table:covered-table-cell table:number-columns-repeated="2"/>' . $cell('C4') . '</table:table-row>'
            . '</table:table><text:p>After</text:p>';
        $file = (string) tempnam(sys_get_temp_dir(), 'PhpWord');
        $zip = new ZipArchive();
        $zip->open($file, ZipArchive::OVERWRITE);
        $zip->addFromString('mimetype', 'application/vnd.oasis.opendocument.text');
        $zip->addFromString('content.xml', '<office:document-content xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0"><office:automatic-styles>' . $styles . '</office:automatic-styles><office:body><office:text>' . $body . '</office:text></office:body></office:document-content>');
        $zip->close();

        $phpWord = IOFactory::load($file, 'ODText');
        unlink($file);

        $elements = $phpWord->getSections()[0]->getElements();
        self::assertCount(2, $elements);
        self::assertInstanceOf(Table::class, $elements[0]);
        self::assertEquals([
            [true, [1500, null, null, 'H1'], [1500, null, null, 'H2'], [720, null, null, 'H3']],
            [false, [3000, 2, null, 'A+B'], [720, null, 'restart', 'C down']],
            [false, [1500, null, null, 'A2'], [1500, null, null, 'B2', [[false, [null, null, null, 'x'], [null, null, null, 'y']]]], [720, null, 'continue']],
            [false, [3000, 2, 'restart', 'Wide'], [720, null, null, 'C3']],
            [false, [3000, 2, 'continue'], [720, null, null, 'C4']],
        ], $this->readTable($elements[0]));
    }

    public function testTableCellsFallOnTheGridAsLibreOfficeLaysThemOut(): void
    {
        $cell = function (string $text, string $attributes = ''): string {
            return '<table:table-cell' . $attributes . '><text:p>' . $text . '</text:p></table:table-cell>';
        };
        $table = function (string $rows): string {
            return '<table:table><table:table-column table:style-name="Bad" table:number-columns-repeated="3"/>' . $rows . '</table:table>';
        };
        $body = $table(
            // Cells spanning rows, and none of the covered cells below them
            '<table:table-row>' . $cell('A', ' table:number-rows-spanned="2"') . $cell('B') . $cell('C', ' table:number-rows-spanned="3"') . '</table:table-row>'
            . '<table:table-row>' . $cell('B2') . '</table:table-row>'
            . '<table:table-row>' . $cell('A3') . $cell('B3') . '<table:covered-table-cell/></table:table-row>'
        ) . $table(
            // A cell spanning columns without its covered cell, then the covered cell of a cell spanning rows
            '<table:table-row>' . $cell('A') . $cell('B') . $cell('C', ' table:number-rows-spanned="2"') . '</table:table-row>'
            . '<table:table-row>' . $cell('AB', ' table:number-columns-spanned="2"') . '<table:covered-table-cell/></table:table-row>'
        ) . str_replace(
            '<table:table-column table:style-name="Bad" table:number-columns-repeated="3"/>',
            '<table:table-header-columns><table:table-column/></table:table-header-columns><table:table-column-group><table:table-columns><table:table-column/></table:table-columns></table:table-column-group>',
            $table(
                // Two columns in their wrappers; a cell spanning past the last one, and counts LibreOffice takes as 1
                '<table:table-row>' . $cell('B') . $cell('C+', ' table:number-columns-spanned="3"') . '</table:table-row>'
                . '<table:table-row table:number-rows-repeated="100000">' . $cell('Once', ' table:number-columns-repeated="1000"') . '</table:table-row>'
            )
        );
        $file = (string) tempnam(sys_get_temp_dir(), 'PhpWord');
        $zip = new ZipArchive();
        $zip->open($file, ZipArchive::OVERWRITE);
        $zip->addFromString('mimetype', 'application/vnd.oasis.opendocument.text');
        $zip->addFromString('content.xml', '<office:document-content xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0"><office:automatic-styles><style:style style:name="Bad" style:family="table-column"><style:table-column-properties style:column-width="in"/></style:style></office:automatic-styles><office:body><office:text>' . $body . '</office:text></office:body></office:document-content>');
        $zip->close();

        $phpWord = IOFactory::load($file, 'ODText');
        unlink($file);

        $tables = array_map([$this, 'readTable'], $phpWord->getSections()[0]->getElements());
        self::assertEquals([
            [
                [false, [null, null, 'restart', 'A'], [null, null, null, 'B'], [null, null, 'restart', 'C']],
                [false, [null, null, 'continue'], [null, null, null, 'B2'], [null, null, 'continue']],
                [false, [null, null, null, 'A3'], [null, null, null, 'B3'], [null, null, 'continue']],
            ],
            [
                [false, [null, null, null, 'A'], [null, null, null, 'B'], [null, null, 'restart', 'C']],
                [false, [null, 2, null, 'AB'], [null, null, 'continue']],
            ],
            [
                [false, [null, null, null, 'B'], [null, null, null, 'C+']],
                [false, [null, null, null, 'Once']],
            ],
        ], $tables);
    }

    public function testTableSurvivesTheRoundTrip(): void
    {
        $phpWord = new PhpWord();
        $table = $phpWord->addSection()->addTable();
        $table->addRow();
        // Widths of whole inches, which the Writer's centimetres keep
        $table->addCell(2880, ['gridSpan' => 2])->addText('A+B');
        $table->addCell(1440)->addText('C');
        $table->addRow();
        foreach (['A2', 'B2', 'C2'] as $text) {
            $table->addCell(1440)->addText($text);
        }
        $file = (string) tempnam(sys_get_temp_dir(), 'PhpWord');
        IOFactory::createWriter($phpWord, 'ODText')->save($file);

        $read = IOFactory::load($file, 'ODText');
        unlink($file);

        $elements = $read->getSections()[0]->getElements();
        self::assertInstanceOf(Table::class, $elements[0]);
        self::assertEquals([
            [false, [2880, 2, null, 'A+B'], [1440, null, null, 'C']],
            [false, [1440, null, null, 'A2'], [1440, null, null, 'B2'], [1440, null, null, 'C2']],
        ], $this->readTable($elements[0]));
    }

    /**
     * The rows of a table: whether it is a header row, and each cell's width, span, merge and content.
     *
     * @return array<int, array<int, mixed>>
     */
    private function readTable(Table $table): array
    {
        $rows = [];
        foreach ($table->getRows() as $row) {
            $read = [(bool) $row->getStyle()->isTblHeader()];
            foreach ($row->getCells() as $cell) {
                $content = [$cell->getWidth(), $cell->getStyle()->getGridSpan(), $cell->getStyle()->getVMerge()];
                foreach ($cell->getElements() as $element) {
                    $content[] = $element instanceof Table ? $this->readTable($element) : ($element instanceof Text || $element instanceof TextRun ? $element->getText() : get_class($element));
                }
                $read[] = $content;
            }
            $rows[] = $read;
        }

        return $rows;
    }
}
