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

namespace PhpOffice\PhpWordTests\Writer\Word2007\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Frame;
use PhpOffice\PhpWordTests\TestHelperDOCX;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    private const IMAGE = __DIR__ . '/../../../_files/images/earth.jpg';

    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testWriteInline(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addImage(self::IMAGE, ['width' => 72, 'height' => 36], false, 'Earth', 'The Earth seen from space');
        $section->addImage(self::IMAGE, ['width' => 96, 'height' => 48, 'unit' => Frame::UNIT_PX]);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $inline = '/w:document/w:body/w:p[1]/w:r/w:drawing/wp:inline';
        self::assertEquals('914400', $doc->getElementAttribute($inline . '/wp:extent', 'cx'));
        self::assertEquals('457200', $doc->getElementAttribute($inline . '/wp:extent', 'cy'));
        self::assertEquals('Earth', $doc->getElementAttribute($inline . '/wp:docPr', 'name'));
        self::assertEquals('The Earth seen from space', $doc->getElementAttribute($inline . '/wp:docPr', 'descr'));
        self::assertFalse($doc->elementExists('/w:document/w:body/w:p[2]/w:r/w:drawing/wp:inline/wp:docPr/@descr'));
        // Pixels
        self::assertEquals('914400', $doc->getElementAttribute('/w:document/w:body/w:p[2]/w:r/w:drawing/wp:inline/wp:extent', 'cx'));
        self::assertFalse($doc->elementExists('//w:pict'));
    }

    public function testWriteAnchor(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addSection()->addImage(self::IMAGE, [
            'width' => 72,
            'height' => 36,
            'wrappingStyle' => Frame::WRAP_TOPBOTTOM,
            'posHorizontal' => Frame::POS_ABSOLUTE,
            'posHorizontalRel' => Frame::POS_RELTO_MARGIN,
            'marginLeft' => 36,
            'posVertical' => Frame::POS_BOTTOM,
            'posVerticalRel' => Frame::POS_RELTO_BMARGIN,
            'wrapDistanceTop' => 9,
        ]);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $anchor = '/w:document/w:body/w:p/w:r/w:drawing/wp:anchor';
        self::assertEquals('0', $doc->getElementAttribute($anchor, 'behindDoc'));
        self::assertEquals('114300', $doc->getElementAttribute($anchor, 'distT'));
        self::assertEquals('margin', $doc->getElementAttribute($anchor . '/wp:positionH', 'relativeFrom'));
        self::assertEquals('457200', $doc->getElement($anchor . '/wp:positionH/wp:posOffset')->nodeValue);
        self::assertEquals('bottomMargin', $doc->getElementAttribute($anchor . '/wp:positionV', 'relativeFrom'));
        self::assertEquals('bottom', $doc->getElement($anchor . '/wp:positionV/wp:align')->nodeValue);
        self::assertTrue($doc->elementExists($anchor . '/wp:wrapTopAndBottom'));
    }

    public function testDocPrIdsAreUniqueInThePackage(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addHeader()->addImage(self::IMAGE);
        $section->addImage(self::IMAGE);
        $section->addImage(self::IMAGE);
        $section->addChart('pie', ['A', 'B'], [1, 2]);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $ids = [];
        foreach (['word/header1.xml', 'word/document.xml'] as $file) {
            foreach ($doc->getNodeList('//wp:docPr', $file) as $docPr) {
                $ids[] = $docPr->attributes->getNamedItem('id')->nodeValue;
            }
        }
        self::assertCount(4, $ids);
        self::assertCount(4, array_unique($ids));
    }
}
