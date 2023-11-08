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
class TOCTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed after each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testWriteTitlePageNumber(): void
    {
        $expectedPageNum = mt_rand(1, 1000);

        $phpWord = new PhpWord();

        $section = $phpWord->addSection();
        $section->addTOC();
        $section->addTitle('TestTitle 1', 1, $expectedPageNum);

        $doc = TestHelperDOCX::getDocument($phpWord);

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:hyperlink/w:r[1]/w:t'));
        self::assertEquals('TestTitle 1', $doc->getElement('/w:document/w:body/w:p[1]/w:hyperlink/w:r[1]/w:t')->textContent);
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:hyperlink/w:r[5]/w:fldChar'));
        self::assertEquals('separate', $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:hyperlink/w:r[5]/w:fldChar', 'w:fldCharType'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:hyperlink/w:r[6]/w:t'));
        self::assertEquals($expectedPageNum, $doc->getElement('/w:document/w:body/w:p[1]/w:hyperlink/w:r[6]/w:t')->textContent);
    }
}
