<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\Writer\ODText\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWordTests\TestHelperDOCX;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PhpOffice\PhpWord\Writer\ODText\Element\TOC.
 */
class TOCTest extends TestCase
{
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testWritesNativeTableOfContent(): void
    {
        $phpWord = new PhpWord();

        $section = $phpWord->addSection();
        $section->addTOC();

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        self::assertTrue($doc->elementExists('/office:document-content/office:body/office:text/text:section/text:table-of-content'));
        self::assertTrue($doc->elementExists('/office:document-content/office:body/office:text/text:section/text:table-of-content/text:table-of-content-source'));
        self::assertTrue($doc->elementExists('/office:document-content/office:body/office:text/text:section/text:table-of-content/text:index-body'));
    }

    public function testWritesTableOfContentDepthAndStyleConfiguration(): void
    {
        $phpWord = new PhpWord();

        $section = $phpWord->addSection();
        $section->addTOC('TOCFont', ['indent' => 300], 2, 4);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $toc = '/office:document-content/office:body/office:text/text:section/text:table-of-content';
        $source = $toc . '/text:table-of-content-source';

        self::assertTrue($doc->elementExists($toc));
        self::assertEquals('TOCFont', $doc->getElementAttribute($toc, 'text:style-name'));
        self::assertEquals('document', $doc->getElementAttribute($source, 'text:index-scope'));
        self::assertEquals('true', $doc->getElementAttribute($source, 'text:use-outline-level'));
        self::assertEquals('4', $doc->getElementAttribute($source, 'text:outline-level'));
        self::assertEquals('false', $doc->getElementAttribute($source, 'text:use-index-marks'));
        self::assertEquals('false', $doc->getElementAttribute($source, 'text:use-index-source-styles'));
        self::assertTrue($doc->elementExists($source . '/text:table-of-content-entry-template[@text:outline-level="2"]'));
        self::assertTrue($doc->elementExists($source . '/text:table-of-content-entry-template[@text:outline-level="4"]'));
        self::assertFalse($doc->elementExists($source . '/text:table-of-content-entry-template[@text:outline-level="1"]'));
        self::assertFalse($doc->elementExists($source . '/text:table-of-content-entry-template[@text:outline-level="5"]'));
    }
}
