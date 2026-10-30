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

use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Style\Section.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Style\Section
 *
 * @runTestsInSeparateProcesses
 */
class SectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testMarginInInches(): void
    {
        $unit = Settings::getMeasurementUnit();
        Settings::setMeasurementUnit(Settings::UNIT_INCH);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->getStyle()->setMarginTop(0.1)->setMarginBottom(0.4)->setMarginLeft(0.2)->setMarginRight(0.3);
        $section->addText('test');
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        Settings::setMeasurementUnit($unit);

        $path = '/w:document/w:body/w:sectPr/w:pgMar';
        self::assertEquals('144', $doc->getElementAttribute($path, 'w:top'));
        self::assertEquals('432', $doc->getElementAttribute($path, 'w:right'));
        self::assertEquals('576', $doc->getElementAttribute($path, 'w:bottom'));
        self::assertEquals('288', $doc->getElementAttribute($path, 'w:left'));
    }

    /**
     * The size of a page is written in whole twips.
     *
     * The schema's `ST_TwipsMeasure` holds no fraction, so a page whose size is a
     * fraction of a twip is a page whose size the format cannot write.
     */
    public function testPageSizeIsWrittenInWholeTwips(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->addSection();
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:sectPr/w:pgSz';
        $width = $doc->getElementAttribute($path, 'w:w');
        $height = $doc->getElementAttribute($path, 'w:h');

        self::assertSame((string) (int) $width, $width);
        self::assertSame((string) (int) $height, $height);
    }

    /**
     * A page size that is a whole number of twips is written as it is, and a page size
     * that is a fraction of a twip is written as the nearest whole number of twips.
     *
     * A page of 210 mm by 297 mm is 11905.511811 twips by 16837.795276 twips, and the
     * nearest whole number of twips is 11906 by 16838.
     */
    public function testPageSizeIsRoundedToTheNearestTwip(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->getStyle()->setPageSizeW(11906);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:sectPr/w:pgSz';

        self::assertSame('11906', $doc->getElementAttribute($path, 'w:w'));
        self::assertSame('16838', $doc->getElementAttribute($path, 'w:h'));
    }
}
