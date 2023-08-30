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

namespace PhpOffice\PhpWordTests\Reader;

use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Reader\Word2007;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Reader\Word2007.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Reader\Word2007
 *
 * @runTestsInSeparateProcesses
 */
class Word2007Test extends \PHPUnit\Framework\TestCase
{
    /**
     * Test canRead() method.
     */
    public function testCanRead(): void
    {
        $object = new Word2007();
        self::assertTrue($object->canRead(dirname(__DIR__, 1) . '/_files/documents/reader.docx'));
    }

    /**
     * Can read exception.
     */
    public function testCanReadFailed(): void
    {
        $object = new Word2007();
        self::assertFalse($object->canRead(dirname(__DIR__, 1) . '/_files/documents/foo.docx'));
    }

    /**
     * Load.
     */
    public function testLoad(): void
    {
        $phpWord = IOFactory::load(dirname(__DIR__, 1) . '/_files/documents/reader.docx', 'Word2007');

        self::assertInstanceOf(PhpWord::class, $phpWord);
        self::assertTrue($phpWord->getSettings()->hasDoNotTrackMoves());
        self::assertFalse($phpWord->getSettings()->hasDoNotTrackFormatting());
        self::assertEquals(100, $phpWord->getSettings()->getZoom());

        $doc = TestHelperDOCX::getDocument($phpWord);
        self::assertEquals('0', $doc->getElementAttribute('/w:document/w:body/w:p/w:r[w:t/node()="italics"]/w:rPr/w:b', 'w:val'));
    }

    /**
     * Load a Word 2011 file.
     */
    public function testLoadWord2011(): void
    {
        $reader = new Word2007();
        $phpWord = $reader->load(dirname(__DIR__, 1) . '/_files/documents/reader-2011.docx');

        self::assertInstanceOf(PhpWord::class, $phpWord);

        $doc = TestHelperDOCX::getDocument($phpWord);
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[3]/w:r/w:pict/v:shape/v:imagedata'));
    }

    /**
     * Load a Word without/withoutImages.
     *
     * @dataProvider providerSettingsImageLoading
     */
    public function testLoadWord2011SettingsImageLoading(bool $hasImageLoading): void
    {
        $reader = new Word2007();
        $reader->setImageLoading($hasImageLoading);
        $phpWord = $reader->load(dirname(__DIR__, 1) . '/_files/documents/reader-2011.docx');

        self::assertInstanceOf(PhpWord::class, $phpWord);

        $sections = $phpWord->getSections();
        self::assertCount(1, $sections);
        $section = $sections[0];
        $elements = $section->getElements();
        self::assertCount(3, $elements);
        $element = $elements[2];
        self::assertInstanceOf(TextRun::class, $element);
        $subElements = $element->getElements();
        if ($hasImageLoading) {
            self::assertCount(1, $subElements);
            $subElement = $subElements[0];
            self::assertInstanceOf(Image::class, $subElement);
        } else {
            self::assertCount(0, $subElements);
        }
    }

    public function providerSettingsImageLoading(): iterable
    {
        return [
            [true],
            [false],
        ];
    }
}
