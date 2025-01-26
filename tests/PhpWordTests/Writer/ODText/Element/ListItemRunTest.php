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

namespace PhpOffice\PhpWordTests\Writer\ODText\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWordTests\TestHelperDOCX;
use PHPUnit\Framework\TestCase;

class ListItemRunTest extends TestCase
{
    /**
     * Executed after each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testAddListItemRun(): void
    {
        $expected = 'List item run 1';

        $phpWord = new PhpWord();
        $phpWord
            ->addSection()
            ->addListItemRun()
            ->addText($expected);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $xPath = '/office:document-content/office:body/office:text/text:section/text:list';

        self::assertTrue($doc->elementExists($xPath));
        self::assertTrue($doc->hasElementAttribute($xPath, 'text:style-name'));
        self::assertEquals('PHPWordListType3', $doc->getElementAttribute($xPath, 'text:style-name'));
        self::assertTrue($doc->elementExists($xPath . '/text:list-item'));
        self::assertTrue($doc->elementExists($xPath . '/text:list-item/text:p'));
        self::assertTrue($doc->elementExists($xPath . '/text:list-item/text:p/text:span'));
        self::assertEquals($expected, $doc->getElement($xPath . '/text:list-item/text:p/text:span')->nodeValue);
    }

    public function testAddListItemRunLevels(): void
    {
        $expected = 'List item run : ';

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addListItemRun(0)->addText($expected . '1');
        $section->addListItemRun(1)->addText($expected . '2');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $xPath = '/office:document-content/office:body/office:text/text:section/text:list';

        self::assertTrue($doc->elementExists($xPath));
        self::assertTrue($doc->hasElementAttribute($xPath, 'text:style-name'));
        self::assertEquals('PHPWordListType3', $doc->getElementAttribute($xPath, 'text:style-name'));
        self::assertTrue($doc->elementExists($xPath . '/text:list-item'));
        self::assertTrue($doc->elementExists($xPath . '/text:list-item/text:p'));
        self::assertTrue($doc->elementExists($xPath . '/text:list-item/text:p/text:span'));
        self::assertEquals($expected . '1', $doc->getElement($xPath . '/text:list-item/text:p/text:span')->nodeValue);
        self::assertTrue($doc->elementExists($xPath . '/text:list-item/text:list/text:list-item'));
        self::assertTrue($doc->elementExists($xPath . '/text:list-item/text:list/text:list-item/text:p'));
        self::assertTrue($doc->elementExists($xPath . '/text:list-item/text:list/text:list-item/text:p/text:span'));
        self::assertEquals($expected . '2', $doc->getElement($xPath . '/text:list-item/text:list/text:list-item/text:p/text:span')->nodeValue);
    }
}
