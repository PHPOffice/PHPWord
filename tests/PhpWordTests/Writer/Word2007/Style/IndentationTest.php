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

namespace PhpOffice\PhpWordTests\Writer\Word2007\Style;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWordTests\TestHelperDOCX;

class IndentationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        Settings::setDefaultRtl(null);
        TestHelperDOCX::clear();
    }

    public function testDefault(): void
    {
        $word = new PhpWord();
        Settings::setDefaultRtl(true);
        $section = $word->addSection();
        $text = $section->addText('AA');
        $paragraphStyle = $text->getParagraphStyle();
        self::assertInstanceOf(Paragraph::class, $paragraphStyle);
        $paragraphStyle->setIndentation([]);
        $doc = TestHelperDOCX::getDocument($word, 'Word2007');

        $path = '/w:document/w:body/w:p[1]/w:pPr/w:ind';
        self::assertTrue($doc->elementExists($path));
        self::assertFalse($doc->hasElementAttribute($path, 'w:firstLineChars'));
    }

    public function testFirstLineChars(): void
    {
        $word = new PhpWord();
        Settings::setDefaultRtl(true);
        $section = $word->addSection();
        $text = $section->addText('AA');
        $paragraphStyle = $text->getParagraphStyle();
        self::assertInstanceOf(Paragraph::class, $paragraphStyle);
        $paragraphStyle->setIndentation([
            'firstLineChars' => 1440,
        ]);
        $doc = TestHelperDOCX::getDocument($word, 'Word2007');

        $path = '/w:document/w:body/w:p[1]/w:pPr/w:ind';
        self::assertTrue($doc->elementExists($path));
        self::assertTrue($doc->hasElementAttribute($path, 'w:firstLineChars'));
        self::assertSame('1440', $doc->getElementAttribute($path, 'w:firstLineChars'));
    }
}
