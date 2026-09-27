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

namespace PhpOffice\PhpWordTests\Reader\ODText;

use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class TitleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * LibreOffice writes a title as a paragraph in its Title style, or in an automatic style based on it.
     */
    public function testReadTitleWrittenByLibreOffice(): void
    {
        $phpWord = IOFactory::load('tests/PhpWordTests/_files/documents/reader-title.odt', 'ODText');

        $elements = $phpWord->getSection(0)->getElements();
        self::assertInstanceOf(Title::class, $elements[0]);
        self::assertSame('Plain title', $elements[0]->getText());
        self::assertSame(0, (int) $elements[0]->getDepth());
        self::assertInstanceOf(Title::class, $elements[1]);
        self::assertSame(1, (int) $elements[1]->getDepth());
        self::assertInstanceOf(Title::class, $elements[2]);
        self::assertSame('Red title', $elements[2]->getText());
        self::assertSame(0, (int) $elements[2]->getDepth());
        self::assertNotInstanceOf(Title::class, $elements[3]);
    }

    public function testWriteThenReadTitle(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addTitle('The title', 0);
        $section->addTitle('A heading', 1);
        $file = tempnam(sys_get_temp_dir(), 'PhpWord');
        IOFactory::createWriter($phpWord, 'ODText')->save($file);

        $phpWord2 = IOFactory::load($file, 'ODText');
        unlink($file);

        $elements = $phpWord2->getSection(0)->getElements();
        self::assertCount(2, $elements);
        self::assertInstanceOf(Title::class, $elements[0]);
        self::assertSame('The title', $elements[0]->getText());
        self::assertSame(0, (int) $elements[0]->getDepth());
        self::assertInstanceOf(Title::class, $elements[1]);
        self::assertSame('A heading', $elements[1]->getText());
        self::assertSame(1, (int) $elements[1]->getDepth());
    }
}
