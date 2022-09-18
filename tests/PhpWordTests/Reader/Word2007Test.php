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

use PhpOffice\PhpWord\IOFactory;
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
        $filename = __DIR__ . '/../_files/documents/reader.docx';
        self::assertTrue($object->canRead($filename));
    }

    /**
     * Can read exception.
     */
    public function testCanReadFailed(): void
    {
        $object = new Word2007();
        $filename = __DIR__ . '/../_files/documents/foo.docx';
        self::assertFalse($object->canRead($filename));
    }

    /**
     * Load.
     */
    public function testLoad(): void
    {
        $filename = __DIR__ . '/../_files/documents/reader.docx';
        $phpWord = IOFactory::load($filename);

        self::assertInstanceOf('PhpOffice\\PhpWord\\PhpWord', $phpWord);
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
        $filename = __DIR__ . '/../_files/documents/reader-2011.docx';
        $phpWord = IOFactory::load($filename);

        self::assertInstanceOf('PhpOffice\\PhpWord\\PhpWord', $phpWord);

        $doc = TestHelperDOCX::getDocument($phpWord);
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[3]/w:r/w:pict/v:shape/v:imagedata'));
    }
}
