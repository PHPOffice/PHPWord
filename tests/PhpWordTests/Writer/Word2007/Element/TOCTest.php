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

    public function testWriteTitleWithoutpageNumber(): void
    {
        $phpWord = new PhpWord();

        $section = $phpWord->addSection();
        $section->addTOC();

        $staticHtml = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. 
            Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. 
            Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi.</p>
            <p>Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat. 
            Duis semper. Duis arcu massa, scelerisque vitae, consequat in, pretium a, enim.</p>';

        //more than one title and random text for create more than one page
        for ($i = 1; $i <= 10; ++$i) {
            $section->addTitle('Title ' . $i, 1);
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $staticHtml, false, false);
            $section->addPageBreak();
        }

        $doc = TestHelperDOCX::getDocument($phpWord);

        for ($i = 1; $i <= 10; ++$i) {
            self::assertTrue($doc->elementExists('/w:document/w:body/w:p[' . $i . ']/w:hyperlink/w:r[1]/w:t'));
            self::assertEquals('Title ' . $i, $doc->getElement('/w:document/w:body/w:p[' . $i . ']/w:hyperlink/w:r[1]/w:t')->textContent);
            self::assertTrue($doc->elementExists('/w:document/w:body/w:p[' . $i . ']/w:hyperlink/w:r[4]/w:instrText'));
            self::assertEquals('preserve', $doc->getElementAttribute('/w:document/w:body/w:p[' . $i . ']/w:hyperlink/w:r[4]/w:instrText', 'xml:space'));
            self::assertEquals('PAGEREF ' . ($i - 1) . ' \\h', $doc->getElement('/w:document/w:body/w:p[' . $i . ']/w:hyperlink/w:r[4]/w:instrText')->nodeValue);
        }
    }
}
