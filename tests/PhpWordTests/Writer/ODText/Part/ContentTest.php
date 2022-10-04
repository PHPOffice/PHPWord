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

namespace PhpOffice\PhpWordTests\Writer\ODText\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\ODText\Part\Content.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\ODText\Part\Content
 */
class ContentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test write content.
     */
    public function testWriteContent(): void
    {
        $imageSrc = __DIR__ . '/../../../_files/images/PhpWord.png';
        $objectSrc = __DIR__ . '/../../../_files/documents/sheet.xls';
        $expected = 'Expected';

        $phpWord = new PhpWord();

        $docProps = $phpWord->getDocInfo();
        $docProps->setCustomProperty('Company', 'PHPWord');

        $phpWord->setDefaultFontName('Verdana');
        $phpWord->addFontStyle('Font', ['size' => 11]);
        $phpWord->addParagraphStyle('Paragraph', ['alignment' => Jc::CENTER]);
        $phpWord->addTableStyle('tblStyle', ['width' => 100]);

        $section = $phpWord->addSection(['colsNum' => 2]);
        $section->addText($expected);
        $section->addText('Test font style', 'Font');
        $section->addText('Test paragraph style', null, 'Paragraph');
        $section->addLink('https://github.com/PHPOffice/PHPWord', 'PHPWord on GitHub');
        $section->addTitle('Test title', 1);
        $section->addTextBreak();
        $section->addPageBreak();
        $section->addListItem('Test list item');
        $section->addImage($imageSrc, ['width' => 50]);
        $section->addObject($objectSrc);
        $section->addTOC();

        $textrun = $section->addTextRun();
        $textrun->addText('Test text run');

        $table = $section->addTable(['width' => 50]);
        $cell = $table->addRow()->addCell();
        $cell = $table->addRow()->addCell();
        $cell->addText('Test');
        $cell->addLink('https://github.com/PHPOffice/PHPWord', 'PHPWord on GitHub');
        $cell->addTextBreak();
        $cell->addListItem('Test list item');
        $cell->addImage($imageSrc);
        $cell->addObject($objectSrc);
        $textrun = $cell->addTextRun();
        $textrun->addText('Test text run');
        $section->addPageBreak();

        $footer = $section->addFooter();
        $footer->addPreserveText('{PAGE}');

        $table = $section->addTable('tblStyle')->addRow()->addCell();

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $element = '/office:document-content/office:body/office:text/text:section/text:p[2]';
        self::assertEquals($expected, $doc->getElement($element, 'content.xml')->nodeValue);
    }

    /**
     * Test no paragraph style.
     */
    public function testWriteNoStyle(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addFontStyle('Font', ['size' => 11]);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $element = '/office:document-content/office:automatic-styles/style:style';
        self::assertTrue($doc->elementExists($element, 'content.xml'));
    }
}
