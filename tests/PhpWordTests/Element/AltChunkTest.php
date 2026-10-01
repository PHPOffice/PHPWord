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

namespace PhpOffice\PhpWordTests\Element;

use BadMethodCallException;
use PhpOffice\PhpWord\Element\AltChunk;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Exception\InvalidObjectException;
use PhpOffice\PhpWord\PhpWord;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PhpOffice\PhpWord\Element\AltChunk.
 */
class AltChunkTest extends TestCase
{
    public function testConstructWithDocx(): void
    {
        $src = __DIR__ . '/../_files/documents/reader.docx';
        $altChunk = new AltChunk($src);

        self::assertEquals($src, $altChunk->getSource());
    }

    public function testConstructWithUppercaseExtension(): void
    {
        $src = tempnam(sys_get_temp_dir(), 'PhpWord') . '.DOCX';
        copy(__DIR__ . '/../_files/documents/reader.docx', $src);

        try {
            $altChunk = new AltChunk($src);
            self::assertEquals($src, $altChunk->getSource());
        } finally {
            unlink($src);
        }
    }

    public function testConstructWithMissingFile(): void
    {
        $this->expectException(InvalidObjectException::class);
        new AltChunk(__DIR__ . '/../_files/documents/missing.docx');
    }

    public function testConstructWithOtherFormat(): void
    {
        $this->expectException(InvalidObjectException::class);
        new AltChunk(__DIR__ . '/../_files/documents/reader.odt');
    }

    public function testRelationId(): void
    {
        $altChunk = new AltChunk(__DIR__ . '/../_files/documents/reader.docx');
        $altChunk->setRelationId(7);

        self::assertEquals(7, $altChunk->getRelationId());
    }

    public function testAddToSection(): void
    {
        $section = new Section(1);
        $section->setPhpWord(new PhpWord());
        $altChunk = $section->addAltChunk(__DIR__ . '/../_files/documents/reader.docx');

        self::assertInstanceOf(AltChunk::class, $altChunk);
        self::assertSame([$altChunk], $section->getElements());
        self::assertTrue($altChunk->isInSection());
    }

    public function testAddToTextRunIsNotAllowed(): void
    {
        $this->expectException(BadMethodCallException::class);
        $section = new Section(1);
        $section->setPhpWord(new PhpWord());
        $section->addTextRun()->addAltChunk(__DIR__ . '/../_files/documents/reader.docx');
    }

    public function testAddToCellIsNotAllowed(): void
    {
        $this->expectException(BadMethodCallException::class);
        $section = new Section(1);
        $section->setPhpWord(new PhpWord());
        $section->addTable()->addRow()->addCell()->addAltChunk(__DIR__ . '/../_files/documents/reader.docx');
    }

    public function testAddToHeaderIsNotAllowed(): void
    {
        $this->expectException(BadMethodCallException::class);
        $section = new Section(1);
        $section->setPhpWord(new PhpWord());
        $section->addHeader()->addAltChunk(__DIR__ . '/../_files/documents/reader.docx');
    }
}
