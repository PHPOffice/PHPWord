<?php

/**
 * This file is part of PHPWord - A pure PHP library to read and write documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser General Public License.
 */

namespace PhpOffice\PhpWordTests\Writer\ODText\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWordTests\TestHelperDOCX;
use PHPUnit\Framework\TestCase;

/**
 * Test class for the ODText PreserveText element writer.
 */
class PreserveTextTest extends TestCase
{
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testPreserveTextWritesNativeFieldsAndLiteralText(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addPreserveText('Page {PAGE} of {NUMPAGES} on {DATE}: {FILENAME} {UNKNOWN}');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $path = '/office:document-content/office:body/office:text/text:section/text:p[2]';

        self::assertTrue($doc->elementExists($path));
        self::assertTrue($doc->elementExists($path . '/text:span/text:page-number'));
        self::assertTrue($doc->elementExists($path . '/text:span/text:page-count'));
        self::assertTrue($doc->elementExists($path . '/text:span/text:date'));
        self::assertTrue($doc->elementExists($path . '/text:span/text:file-name'));
        self::assertStringContainsString('{UNKNOWN}', $doc->getElement($path)->textContent);
    }

    public function testPreserveTextAppliesFontAndParagraphStyles(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addPreserveText('Page {PAGE}', ['bold' => true], ['alignment' => Jc::CENTER]);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $path = '/office:document-content/office:body/office:text/text:section/text:p[2]';

        self::assertSame('P1', $doc->getElementAttribute($path, 'text:style-name'));
        self::assertSame('T1', $doc->getElementAttribute($path . '/text:span[1]', 'text:style-name'));
        self::assertSame('T1', $doc->getElementAttribute($path . '/text:span[2]', 'text:style-name'));
    }
}
