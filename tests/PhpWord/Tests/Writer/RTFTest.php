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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */
namespace PhpOffice\PhpWord\Tests\Writer;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\RTF;

/**
 * Test class for PhpOffice\PhpWord\Writer\RTF
 *
 * @runTestsInSeparateProcesses
 */
class RTFTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Construct
     */
    public function testConstruct()
    {
        $object = new RTF(new PhpWord);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\PhpWord', $object->getPhpWord());
    }

    /**
     * Construct with null
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage No PhpWord assigned.
     */
    public function testConstructWithNull()
    {
        $object = new RTF();
        $object->getPhpWord();
    }

    /**
     * Save
     */
    public function testSave()
    {
        $imageSrc = __DIR__ . "/../_files/images/PhpWord.png";
        $objectSrc = __DIR__ . "/../_files/documents/sheet.xls";
        $file = __DIR__ . "/../_files/temp.rtf";

        $phpWord = new PhpWord();
        $phpWord->addFontStyle('Font', array('name' => 'Verdana', 'size' => 11,
            'color' => 'FF0000', 'fgColor' => '00FF00'));
        $phpWord->addParagraphStyle('Paragraph', array('align' => 'center'));
        $section = $phpWord->addSection();
        $section->addText('Test 1', 'Font', 'Paragraph');
        $section->addTextBreak();
        $section->addText('Test 2', array('name' => 'Tahoma', 'bold' => true, 'italic' => true));
        $section->addLink('http://test.com');
        $section->addTitle('Test', 1);
        $section->addPageBreak();

        // Rowspan
        $table = $section->addTable();
        $table->addRow()->addCell(null, array('vMerge' => 'restart'))->addText('Test');
        $table->addRow()->addCell(null, array('vMerge' => 'continue'))->addText('Test');

        // Nested table
        $cell = $section->addTable()->addRow()->addCell();
        $cell->addTable()->addRow()->addCell();

        $section->addListItem('Test');
        $section->addImage($imageSrc);
        $section->addObject($objectSrc);
        $section->addTOC();
        $section = $phpWord->addSection();
        $textrun = $section->addTextRun();
        $textrun->addText('Test 3');
        $textrun->addTextBreak();
        $writer = new RTF($phpWord);
        $writer->save($file);

        $this->assertTrue(file_exists($file));

        @unlink($file);
    }

    /**
     * Save
     *
     * @todo   Haven't got any method to test this
     */
    public function testSavePhpOutput()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Test');
        $writer = new RTF($phpWord);
        $writer->save('php://output');
    }
}
