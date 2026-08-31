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

namespace PhpOffice\PhpWordTests\Writer;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\OLERead;
use PhpOffice\PhpWord\Writer\WPS;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PhpOffice\PhpWord\Writer\WPS.
 */
class WPSTest extends TestCase
{
    /** @var string */
    private $file;

    protected function setUp(): void
    {
        $this->file = (string) tempnam(sys_get_temp_dir(), 'PhpWord');
    }

    protected function tearDown(): void
    {
        if (file_exists($this->file)) {
            unlink($this->file);
        }
    }

    public function testConstruct(): void
    {
        $object = new WPS(new PhpWord());

        self::assertInstanceOf(PhpWord::class, $object->getPhpWord());
    }

    public function testConstructWithNull(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No PhpWord assigned.');
        $object = new WPS();
        $object->getPhpWord();
    }

    public function testCreateWriter(): void
    {
        self::assertInstanceOf(WPS::class, IOFactory::createWriter(new PhpWord(), 'WPS'));
    }

    public function testSaveWritesAnOleDocument(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addSection()->addText('Hello');

        (new WPS($phpWord))->save($this->file);

        self::assertStringStartsWith("\xd0\xcf\x11\xe0\xa1\xb1\x1a\xe1", (string) file_get_contents($this->file));
        self::assertStringStartsWith('CHNKWKS', $this->getContentsStream($this->file));
    }

    public function testSaveWritesTheTextOfTheElements(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addTitle('Title', 1);
        $section->addText('Text with accents : éàü');
        $textRun = $section->addTextRun();
        $textRun->addText('Text ');
        $textRun->addText('run');
        $section->addTextBreak();
        $section->addLink('https://github.com/PHPOffice/PHPWord', 'Link');
        $section->addListItem('List item');
        $section->addPageBreak();
        $table = $section->addTable();
        $table->addRow();
        $table->addCell()->addText('Cell');

        (new WPS($phpWord))->save($this->file);

        self::assertSame(
            "Title\rText with accents : éàü\rText run\r\rLink\rList item\r\x0cCell\r",
            $this->getText($this->file)
        );
    }

    public function testSaveIgnoresTheUnsupportedElements(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addImage(__DIR__ . '/../_files/images/PhpWord.png');
        $section->addText('Text');

        (new WPS($phpWord))->save($this->file);

        self::assertSame("Text\r", $this->getText($this->file));
    }

    public function testSaveWritesTheDefaultFontName(): void
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Verdana');
        $phpWord->addSection()->addText('Text');

        (new WPS($phpWord))->save($this->file);

        self::assertStringContainsString(
            (string) mb_convert_encoding('Verdana', 'UTF-16LE', 'UTF-8'),
            $this->getChunk($this->file, 'FONT')
        );
    }

    /**
     * Get the CONTENTS stream of a WPS file.
     */
    private function getContentsStream(string $filename): string
    {
        $ole = new OLERead();
        $ole->read($filename);

        foreach ($ole->props as $index => $property) {
            if ($property['name'] === 'CONTENTS') {
                return substr((string) $ole->getStream($index), 0, $property['size']);
            }
        }

        self::fail('The file does not contain a CONTENTS stream.');
    }

    /**
     * Get the content of a chunk of the CONTENTS stream of a WPS file.
     */
    private function getChunk(string $filename, string $name): string
    {
        $contents = $this->getContentsStream($filename);

        $numChunks = (int) (unpack('v', $contents, 0x0c)[1] ?? 0);
        for ($index = 0; $index < $numChunks; ++$index) {
            $entry = 0x20 + $index * 0x18;
            if (substr($contents, $entry + 2, 4) === $name) {
                $chunk = (array) unpack('Voffset/Vsize', $contents, $entry + 0x10);

                return substr($contents, (int) $chunk['offset'], (int) $chunk['size']);
            }
        }

        self::fail("The CONTENTS stream does not contain a {$name} chunk.");
    }

    /**
     * Get the text of a WPS file.
     */
    private function getText(string $filename): string
    {
        return (string) mb_convert_encoding($this->getChunk($filename, 'TEXT'), 'UTF-8', 'UTF-16LE');
    }
}
