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

/**
 * Test class for PhpOffice\PhpWord\Writer\ODText\Element subnamespace.
 */
class FieldTest extends TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testFieldFilename(): void
    {
        $phpWord = new PhpWord();

        $section = $phpWord->addSection();
        $section->addField('FILENAME');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        self::assertTrue($doc->elementExists('/office:document-content/office:body/office:text/text:section/text:span/text:file-name'));
        self::assertEquals('false', $doc->getElementAttribute('/office:document-content/office:body/office:text/text:section/text:span/text:file-name', 'text:fixed'));
        self::assertEquals('name', $doc->getElementAttribute('/office:document-content/office:body/office:text/text:section/text:span/text:file-name', 'text:display'));
    }

    public function testFieldFilenameOptionPath(): void
    {
        $phpWord = new PhpWord();

        $section = $phpWord->addSection();
        $section->addField('FILENAME', [], ['Path']);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        self::assertTrue($doc->elementExists('/office:document-content/office:body/office:text/text:section/text:span/text:file-name'));
        self::assertEquals('false', $doc->getElementAttribute('/office:document-content/office:body/office:text/text:section/text:span/text:file-name', 'text:fixed'));
        self::assertEquals('full', $doc->getElementAttribute('/office:document-content/office:body/office:text/text:section/text:span/text:file-name', 'text:display'));
    }

    public function testFieldReference(): void
    {
        $phpWord = new PhpWord();

        $section = $phpWord->addSection();
        $field = $section->addField('REF', ['name' => 'bookmark']);
        $field->setFontStyle('ReferenceStyle');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        self::assertTrue($doc->elementExists('/office:document-content/office:body/office:text/text:section/text:span/text:reference-ref'));
        self::assertEquals('bookmark', $doc->getElementAttribute('/office:document-content/office:body/office:text/text:section/text:span/text:reference-ref', 'text:ref-name'));
        self::assertEquals('text', $doc->getElementAttribute('/office:document-content/office:body/office:text/text:section/text:span/text:reference-ref', 'text:reference-format'));
        self::assertEquals('ReferenceStyle', $doc->getElementAttribute('/office:document-content/office:body/office:text/text:section/text:span', 'text:style-name'));
    }

    public function testFieldIndexEntry(): void
    {
        $phpWord = new PhpWord();

        $section = $phpWord->addSection();
        $section->addField('XE', [], [], 'Index entry');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        self::assertTrue($doc->elementExists('/office:document-content/office:body/office:text/text:section/text:span/text:alphabetical-index-mark'));
        self::assertEquals('Index entry', $doc->getElementAttribute('/office:document-content/office:body/office:text/text:section/text:span/text:alphabetical-index-mark', 'text:string-value'));
    }

    public function testFieldIndex(): void
    {
        $phpWord = new PhpWord();

        $section = $phpWord->addSection();
        $section->addField('INDEX');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        self::assertTrue($doc->elementExists('/office:document-content/office:body/office:text/text:section/text:alphabetical-index'));
        self::assertTrue($doc->elementExists('/office:document-content/office:body/office:text/text:section/text:alphabetical-index/text:index-body'));
    }
}
