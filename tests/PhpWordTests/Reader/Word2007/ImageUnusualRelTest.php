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

namespace PhpOffice\PhpWordTests\Reader\Word2007;

use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PHPUnit\Framework\TestCase;

class ImageUnusualRelTest extends TestCase
{
    private const INFILE = 'tests/PhpWordTests/_files/documents/word.2880.docx';

    public function testPreliminaries(): void
    {
        $file = 'zip://';
        $file .= self::INFILE;
        $file .= '#word/_rels/document.xml.rels';
        $data = file_get_contents($file);
        self::assertNotFalse($data);
        self::assertStringContainsString(
            'Target="/word/media/mId1.jpg"',
            $data,
            'Target uses absolute, not relative, address'
        );
    }

    public function testUnexpectedRel(): void
    {
        $phpWord = IOFactory::load(self::INFILE);
        $sections = $phpWord->getSections();
        self::assertCount(1, $sections);
        $section = $sections[0];
        self::assertNotNull($section);
        $imageElement = null;
        foreach ($section->getElements() as $element) {
            if (get_class($element) === TextRun::class) {
                foreach ($element->getElements() as $textRunElement) {
                    if (get_class($textRunElement) === Image::class) {
                        $imageElement = $textRunElement;

                        break;
                    }
                }
            }
        }
        self::assertInstanceOf(Image::class, $imageElement);
        $expectedSource = 'zip://' . self::INFILE . '#word/media/mId1.jpg';
        self::assertSame($expectedSource, $imageElement->getSource());
    }
}
