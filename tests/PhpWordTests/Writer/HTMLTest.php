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
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Writer\HTML;
use PhpOffice\PhpWordTests\AbstractWebServerEmbeddedTest;

/**
 * Test class for PhpOffice\PhpWord\Writer\HTML.
 *
 * @runTestsInSeparateProcesses
 */
class HTMLTest extends AbstractWebServerEmbeddedTest
{
    /**
     * Construct.
     */
    public function testConstruct(): void
    {
        $object = new HTML(new PhpWord());

        self::assertInstanceOf('PhpOffice\\PhpWord\\PhpWord', $object->getPhpWord());
    }

    /**
     * Construct with null.
     */
    public function testConstructWithNull(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\Exception::class);
        $this->expectExceptionMessage('No PhpWord assigned.');
        $object = new HTML();
        $object->getPhpWord();
    }

    /**
     * Save.
     */
    public function testSave(): void
    {
        $localImage = __DIR__ . '/../_files/images/PhpWord.png';
        $archiveImage = 'zip://' . __DIR__ . '/../_files/documents/reader.docx#word/media/image1.jpeg';
        $gdImage = self::getRemoteGifImageUrl();
        $objectSrc = __DIR__ . '/../_files/documents/sheet.xls';
        $file = __DIR__ . '/../_files/temp.html';

        $phpWord = new PhpWord();

        $docProps = $phpWord->getDocInfo();
        $docProps->setTitle(htmlspecialchars('HTML Test', ENT_COMPAT, 'UTF-8'));

        $phpWord->addTitleStyle(1, ['bold' => true]);
        $phpWord->addFontStyle(
            'Font',
            ['name' => 'Verdana', 'size' => 11, 'color' => 'FF0000', 'fgColor' => 'FF0000']
        );
        $phpWord->addParagraphStyle('Paragraph', ['alignment' => Jc::CENTER, 'spaceAfter' => 20, 'spaceBefore' => 20]);
        $section = $phpWord->addSection();
        $section->addBookmark('top');
        $section->addText(htmlspecialchars('Test 1', ENT_COMPAT, 'UTF-8'), 'Font', 'Paragraph');
        $section->addTextBreak();
        $section->addText(
            htmlspecialchars('Test 2', ENT_COMPAT, 'UTF-8'),
            ['name' => 'Tahoma', 'bold' => true, 'italic' => true, 'subscript' => true]
        );
        $section->addLink('https://github.com/PHPOffice/PHPWord');
        $section->addTitle(htmlspecialchars('Test', ENT_COMPAT, 'UTF-8'), 1);
        $section->addPageBreak();
        $section->addListItem(htmlspecialchars('Test', ENT_COMPAT, 'UTF-8'));
        $section->addImage($localImage);
        $section->addImage($archiveImage);
        $section->addImage($gdImage);
        $section->addObject($objectSrc);
        $section->addFootnote();
        $section->addEndnote();

        $section = $phpWord->addSection();

        $textrun = $section->addTextRun(['alignment' => Jc::CENTER]);
        $textrun->addText(htmlspecialchars('Test 3', ENT_COMPAT, 'UTF-8'));
        $textrun->addTextBreak();

        $textrun = $section->addTextRun(['alignment' => Jc::START]);
        $textrun->addText(htmlspecialchars('Text left aligned', ENT_COMPAT, 'UTF-8'));

        $textrun = $section->addTextRun(['alignment' => Jc::BOTH]);
        $textrun->addText(htmlspecialchars('Text justified', ENT_COMPAT, 'UTF-8'));

        $textrun = $section->addTextRun(['alignment' => Jc::END]);
        $textrun->addText(htmlspecialchars('Text right aligned', ENT_COMPAT, 'UTF-8'));

        $textrun = $section->addTextRun('Paragraph');
        $textrun->addLink('https://github.com/PHPOffice/PHPWord');
        $textrun->addImage($localImage);
        $textrun->addFootnote()->addText(htmlspecialchars('Footnote', ENT_COMPAT, 'UTF-8'));
        $textrun->addEndnote()->addText(htmlspecialchars('Endnote', ENT_COMPAT, 'UTF-8'));

        $section = $phpWord->addSection();

        $table = $section->addTable();
        $cell = $table->addRow()->addCell();
        $cell->addText(
            htmlspecialchars('Test 1', ENT_COMPAT, 'UTF-8'),
            ['superscript' => true, 'underline' => 'dash', 'strikethrough' => true]
        );
        $cell->addTextRun();
        $cell->addLink('https://github.com/PHPOffice/PHPWord');
        $cell->addTextBreak();
        $cell->addListItem(htmlspecialchars('Test', ENT_COMPAT, 'UTF-8'));
        $cell->addImage($localImage);
        $cell->addObject($objectSrc);
        $cell->addFootnote();
        $cell->addEndnote();
        $cell = $table->addRow()->addCell();
        $section->addLink('top', 'back to top', null, null, true);

        $writer = new HTML($phpWord);

        $writer->save($file);
        self::assertFileExists($file);
        unlink($file);

        Settings::setOutputEscapingEnabled(true);
        $writer->save($file);
        self::assertFileExists($file);
        unlink($file);
    }
}
