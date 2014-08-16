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
use PhpOffice\PhpWord\Writer\HTML;

/**
 * Test class for PhpOffice\PhpWord\Writer\HTML
 *
 * @runTestsInSeparateProcesses
 */
class HTMLTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Construct
     */
    public function testConstruct()
    {
        $object = new HTML(new PhpWord);

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
        $object = new HTML();
        $object->getPhpWord();
    }

    /**
     * Save
     */
    public function testSave()
    {
        $localImage = __DIR__ . "/../_files/images/PhpWord.png";
        $archiveImage = 'zip://' . __DIR__ . '/../_files/documents/reader.docx#word/media/image1.jpeg';
        $gdImage = 'http://php.net/images/logos/php-med-trans-light.gif';
        $objectSrc = __DIR__ . "/../_files/documents/sheet.xls";
        $file = __DIR__ . "/../_files/temp.html";

        $phpWord = new PhpWord();

        $docProps = $phpWord->getDocInfo();
        $docProps->setTitle('HTML Test');

        $phpWord->addTitleStyle(1, array('bold' => true));
        $phpWord->addFontStyle('Font', array('name' => 'Verdana', 'size' => 11,
            'color' => 'FF0000', 'fgColor' => 'FF0000'));
        $phpWord->addParagraphStyle('Paragraph', array('align' => 'center', 'spaceAfter' => 20, 'spaceBefore' => 20));
        $section = $phpWord->addSection();
        $section->addText('Test 1', 'Font', 'Paragraph');
        $section->addTextBreak();
        $section->addText('Test 2', array('name' => 'Tahoma', 'bold' => true, 'italic' => true, 'subscript' => true));
        $section->addLink('http://test.com');
        $section->addTitle('Test', 1);
        $section->addPageBreak();
        $section->addListItem('Test');
        $section->addImage($localImage);
        $section->addImage($archiveImage);
        $section->addImage($gdImage);
        $section->addObject($objectSrc);
        $section->addFootnote();
        $section->addEndnote();

        $section = $phpWord->addSection();

        $textrun = $section->addTextRun(array('align' => 'center'));
        $textrun->addText('Test 3');
        $textrun->addTextBreak();

        $textrun = $section->addTextRun('Paragraph');
        $textrun->addLink('http://test.com');
        $textrun->addImage($localImage);
        $textrun->addFootnote()->addText('Footnote');
        $textrun->addEndnote()->addText('Endnote');

        $section = $phpWord->addSection();

        $table = $section->addTable();
        $cell = $table->addRow()->addCell();
        $cell->addText('Test 1', array('superscript' => true, 'underline' => 'dash', 'strikethrough' => true));
        $cell->addTextRun();
        $cell->addLink('http://test.com');
        $cell->addTextBreak();
        $cell->addListItem('Test');
        $cell->addImage($localImage);
        $cell->addObject($objectSrc);
        $cell->addFootnote();
        $cell->addEndnote();
        $cell = $table->addRow()->addCell();

        $writer = new HTML($phpWord);
        $writer->save($file);

        $this->assertTrue(file_exists($file));

        unlink($file);
    }
}
