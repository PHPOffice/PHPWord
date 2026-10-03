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

namespace PhpOffice\PhpWordTests\Reader\Html;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWordTests\AbstractWebServerEmbedded;
use PhpOffice\PhpWordTests\TestHelperDOCX;

class BreakTest extends AbstractWebServerEmbedded
{
    /**
     * Test parsing paragraph with `break-inside: avoid` style.
     */
    public function testParseParagraphWithBreakInsideAvoid(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<p style="break-inside: avoid;"></p>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:keepLines'));
    }

    /**
     * Test parsing paragraph with `break-after: avoid` style.
     */
    public function testParseParagraphWithBreakAfterAvoid(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<p style="break-after: avoid;"></p>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:keepNext'));
    }

    /**
     * Test parsing paragraph with `break-after: always` style.
     */
    public function testParseParagraphWithBreakAfterAlways(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<p style="break-after: always;"></p>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:br'));
        self::assertSame('page', $doc->getElementAttribute('/w:document/w:body/w:p/w:r/w:br', 'w:type'));
    }
}
