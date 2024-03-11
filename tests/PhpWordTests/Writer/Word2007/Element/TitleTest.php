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
declare(strict_types=1);

namespace PhpOffice\PhpWordTests\Writer\Word2007\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Element subnamespace.
 */
class TitleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed after each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testWriteTitleWithStyle(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addTitleStyle(0, ['size' => 14, 'italic' => true]);

        $section = $phpWord->addSection();
        $section->addTitle('Test Title0', 0);

        $doc = TestHelperDOCX::getDocument($phpWord);

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:r/w:t'));
        self::assertEquals('Test Title0', $doc->getElement('/w:document/w:body/w:p[1]/w:r/w:t')->textContent);
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:pPr/w:pStyle'));
        self::assertEquals('Title', $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:pPr/w:pStyle', 'w:val'));
    }

    public function testWriteTitleWithoutStyle(): void
    {
        $phpWord = new PhpWord();

        $section = $phpWord->addSection();
        $section->addTitle('Test Title0', 0);

        $doc = TestHelperDOCX::getDocument($phpWord);

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:r/w:t'));
        self::assertEquals('Test Title0', $doc->getElement('/w:document/w:body/w:p[1]/w:r/w:t')->textContent);
        self::assertFalse($doc->elementExists('/w:document/w:body/w:p[1]/w:pPr'));
    }

    public function testWriteHeadingWithStyle(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addTitleStyle(1, ['bold' => true], ['spaceAfter' => 240]);

        $section = $phpWord->addSection();
        $section->addTitle('TestHeading 1', 1);

        $doc = TestHelperDOCX::getDocument($phpWord);

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:r/w:t'));
        self::assertEquals('TestHeading 1', $doc->getElement('/w:document/w:body/w:p[1]/w:r/w:t')->textContent);
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:pPr/w:pStyle'));
        self::assertEquals('Heading1', $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:pPr/w:pStyle', 'w:val'));
    }

    public function testWriteHeadingWithoutStyle(): void
    {
        $phpWord = new PhpWord();

        $section = $phpWord->addSection();
        $section->addTitle('TestHeading 1', 1);

        $doc = TestHelperDOCX::getDocument($phpWord);

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:r/w:t'));
        self::assertEquals('TestHeading 1', $doc->getElement('/w:document/w:body/w:p[1]/w:r/w:t')->textContent);
        self::assertFalse($doc->elementExists('/w:document/w:body/w:p[1]/w:pPr'));
    }
}
