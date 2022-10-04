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

namespace PhpOffice\PhpWordTests\Writer;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Writer\RTF;

/**
 * Test class for PhpOffice\PhpWord\Writer\RTF.
 *
 * @runTestsInSeparateProcesses
 */
class RTFTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Construct.
     */
    public function testConstruct(): void
    {
        $object = new RTF(new PhpWord());

        self::assertInstanceOf('PhpOffice\\PhpWord\\PhpWord', $object->getPhpWord());
    }

    /**
     * Construct with null.
     */
    public function testConstructWithNull(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\Exception::class);
        $this->expectExceptionMessage('No PhpWord assigned.');
        $object = new RTF();
        $object->getPhpWord();
    }

    /**
     * Save.
     */
    public function testSave(): void
    {
        $imageSrc = __DIR__ . '/../_files/images/PhpWord.png';
        $objectSrc = __DIR__ . '/../_files/documents/sheet.xls';
        $file = __DIR__ . '/../_files/temp.rtf';

        $phpWord = new PhpWord();
        $phpWord->addFontStyle(
            'Font',
            ['name' => 'Verdana', 'size' => 11, 'color' => 'FF0000', 'fgColor' => '00FF00']
        );
        $phpWord->addParagraphStyle('Paragraph', ['alignment' => Jc::CENTER]);
        $section = $phpWord->addSection();
        $section->addText(htmlspecialchars('Test 1', ENT_COMPAT, 'UTF-8'), 'Font', 'Paragraph');
        $section->addTextBreak();
        $section->addText(htmlspecialchars('Test 2', ENT_COMPAT, 'UTF-8'), ['name' => 'Tahoma', 'bold' => true, 'italic' => true]);
        $section->addLink('https://github.com/PHPOffice/PHPWord');
        $section->addTitle(htmlspecialchars('Test', ENT_COMPAT, 'UTF-8'), 1);
        $section->addPageBreak();

        // Rowspan
        $table = $section->addTable();
        $table->addRow()->addCell(null, ['vMerge' => 'restart'])->addText('Test');
        $table->addRow()->addCell(null, ['vMerge' => 'continue'])->addText('Test');

        // Nested table
        $cell = $section->addTable()->addRow()->addCell();
        $cell->addTable()->addRow()->addCell();

        $section->addListItem(htmlspecialchars('Test', ENT_COMPAT, 'UTF-8'));
        $section->addImage($imageSrc);
        $section->addObject($objectSrc);
        $section->addTOC();
        $section = $phpWord->addSection();
        $textrun = $section->addTextRun();
        $textrun->addText(htmlspecialchars('Test 3', ENT_COMPAT, 'UTF-8'));
        $textrun->addTextBreak();
        $writer = new RTF($phpWord);
        $writer->save($file);

        self::assertFileExists($file);

        @unlink($file);
    }

    /**
     * Save.
     *
     * @todo   Haven't got any method to test this
     */
    public function testSavePhpOutput(): void
    {
        $this->setOutputCallback(function (): void {
        });
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText(htmlspecialchars('Test', ENT_COMPAT, 'UTF-8'));
        $writer = new RTF($phpWord);
        $writer->save('php://output');
        self::assertNotNull($this->getActualOutput());
    }
}
