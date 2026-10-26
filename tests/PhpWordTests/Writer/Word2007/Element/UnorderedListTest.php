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

use PhpOffice\PhpWord\Reader\HTML;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Element subnamespace.
 */
class UnorderedListTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed after each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public static function testUnorderedList(): void
    {
        $infile = 'tests/PhpWordTests/_files/html/bullets.html';
        $reader = new HTML();
        $phpWord = $reader->load($infile);

        $doc = TestHelperDOCX::getDocument($phpWord);
        $loc = '/w:document/w:body/w:p[2]';
        self::assertTrue($doc->elementExists($loc));
        $next = "$loc/w:pPr/w:numPr";
        self::assertTrue($doc->elementExists($next));
        $ilvl = "$next/w:ilvl";
        self::assertTrue($doc->elementExists($ilvl));
        self::assertSame('0', $doc->getElementAttribute($ilvl, 'w:val'));
        $next = "$loc/w:r/w:t";
        self::assertTrue($doc->elementExists($next));
        self::assertSame('First level.', $doc->getElement($next)->textContent);

        $loc = '/w:document/w:body/w:p[3]';
        self::assertTrue($doc->elementExists($loc));
        $next = "$loc/w:pPr/w:numPr";
        self::assertTrue($doc->elementExists($next));
        $ilvl = "$next/w:ilvl";
        self::assertTrue($doc->elementExists($ilvl));
        self::assertSame('1', $doc->getElementAttribute($ilvl, 'w:val'));
        $next = "$loc/w:r/w:t";
        self::assertTrue($doc->elementExists($next));
        self::assertSame('Second level.', $doc->getElement($next)->textContent);

        $loc = '/w:document/w:body/w:p[4]';
        self::assertTrue($doc->elementExists($loc));
        $next = "$loc/w:pPr/w:numPr";
        self::assertTrue($doc->elementExists($next));
        $ilvl = "$next/w:ilvl";
        self::assertTrue($doc->elementExists($ilvl));
        self::assertSame('2', $doc->getElementAttribute($ilvl, 'w:val'));
        $next = "$loc/w:r/w:t";
        self::assertTrue($doc->elementExists($next));
        self::assertSame('Third level.', $doc->getElement($next)->textContent);

        $doc->setDefaultFile('word/numbering.xml');
        $loc = '/w:numbering/w:abstractNum/w:lvl[1]';
        self::assertTrue($doc->elementExists($loc));
        self::assertSame('0', $doc->getElementAttribute($loc, 'w:ilvl'));
        $lvlText = "$loc/w:lvlText";
        self::assertTrue($doc->elementExists($lvlText));
        self::assertSame("\u{f0b7}", $doc->getElementAttribute($lvlText, 'w:val'));
        $ind = "$loc/w:pPr/w:ind";
        self::assertTrue($doc->elementExists($ind));
        self::assertSame('720', $doc->getElementAttribute($ind, 'w:left'));
        self::assertSame('360', $doc->getElementAttribute($ind, 'w:hanging'));
        $fonts = "$loc/w:rPr/w:rFonts";
        self::assertTrue($doc->elementExists($fonts));
        self::assertSame('Symbol', $doc->getElementAttribute($fonts, 'w:ascii'));

        $loc = '/w:numbering/w:abstractNum/w:lvl[2]';
        self::assertTrue($doc->elementExists($loc));
        self::assertSame('1', $doc->getElementAttribute($loc, 'w:ilvl'));
        $lvlText = "$loc/w:lvlText";
        self::assertTrue($doc->elementExists($lvlText));
        self::assertSame('o', $doc->getElementAttribute($lvlText, 'w:val'));
        $ind = "$loc/w:pPr/w:ind";
        self::assertTrue($doc->elementExists($ind));
        self::assertSame('1440', $doc->getElementAttribute($ind, 'w:left'));
        self::assertSame('360', $doc->getElementAttribute($ind, 'w:hanging'));
        $fonts = "$loc/w:rPr/w:rFonts";
        self::assertTrue($doc->elementExists($fonts));
        self::assertSame('Courier New', $doc->getElementAttribute($fonts, 'w:ascii'));

        $loc = '/w:numbering/w:abstractNum/w:lvl[3]';
        self::assertTrue($doc->elementExists($loc));
        self::assertSame('2', $doc->getElementAttribute($loc, 'w:ilvl'));
        $lvlText = "$loc/w:lvlText";
        self::assertTrue($doc->elementExists($lvlText));
        self::assertSame("\u{f0a7}", $doc->getElementAttribute($lvlText, 'w:val'));
        $ind = "$loc/w:pPr/w:ind";
        self::assertTrue($doc->elementExists($ind));
        self::assertSame('2160', $doc->getElementAttribute($ind, 'w:left'));
        self::assertSame('360', $doc->getElementAttribute($ind, 'w:hanging'));
        $fonts = "$loc/w:rPr/w:rFonts";
        self::assertTrue($doc->elementExists($fonts));
        self::assertSame('Wingdings', $doc->getElementAttribute($fonts, 'w:ascii'));
    }
}
