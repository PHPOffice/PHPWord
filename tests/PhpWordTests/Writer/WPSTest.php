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

class WPSTest extends TestCase
{
    /** @var string */
    private $file;

    protected function setUp(): void
    {
        $this->file = (string) tempnam(sys_get_temp_dir(), 'PhpWordWps');
    }

    protected function tearDown(): void
    {
        if (is_string($this->file) && file_exists($this->file)) {
            unlink($this->file);
        }
    }

    public function testConstructRequiresPhpWord(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No PhpWord assigned.');
        (new WPS())->getPhpWord();
    }

    public function testFactoryCreatesWpsWriter(): void
    {
        self::assertInstanceOf(WPS::class, IOFactory::createWriter(new PhpWord(), 'WPS'));
    }

    public function testSaveProducesOleMagicAndChnkwksContents(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addSection()->addText('Hello');
        IOFactory::createWriter($phpWord, 'WPS')->save($this->file);

        $bytes = (string) file_get_contents($this->file);
        self::assertStringStartsWith("\xd0\xcf\x11\xe0\xa1\xb1\x1a\xe1", $bytes);

        $contents = $this->contentsStream($this->file);
        self::assertStringStartsWith('CHNKWKS', $contents);
    }

    public function testSavePutsDocumentTextInTheTextChunk(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addTitle('Heading', 1);
        $section->addText('Cafe: café');
        $run = $section->addTextRun();
        $run->addText('joined ');
        $run->addText('run');
        $section->addTextBreak();
        $section->addLink('https://github.com/PHPOffice/PHPWord', 'Docs');
        $section->addListItem('Bullet');
        $section->addPageBreak();
        $table = $section->addTable();
        $table->addRow();
        $table->addCell()->addText('Cell');

        IOFactory::createWriter($phpWord, 'WPS')->save($this->file);

        self::assertSame(
            "Heading\rCafe: café\rjoined run\r\rDocs\rBullet\r\x0cCell\r",
            $this->decodedText($this->file)
        );
    }

    public function testSaveSkipsImagesAndKeepsFollowingText(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addImage(__DIR__ . '/../_files/images/PhpWord.png');
        $section->addText('Kept');

        IOFactory::createWriter($phpWord, 'WPS')->save($this->file);

        self::assertSame("Kept\r", $this->decodedText($this->file));
    }

    public function testSaveEmbedsDefaultFontNameInFontChunk(): void
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Georgia');
        $phpWord->addSection()->addText('Body');

        IOFactory::createWriter($phpWord, 'WPS')->save($this->file);

        self::assertStringContainsString(
            (string) mb_convert_encoding('Georgia', 'UTF-16LE', 'UTF-8'),
            $this->chunk($this->file, 'FONT')
        );
    }

    private function contentsStream(string $filename): string
    {
        $ole = new OLERead();
        $ole->read($filename);
        foreach ($ole->props as $index => $property) {
            if (($property['name'] ?? '') === 'CONTENTS') {
                return substr((string) $ole->getStream($index), 0, (int) $property['size']);
            }
        }

        self::fail('OLERead did not find a CONTENTS stream.');
    }

    private function chunk(string $filename, string $tag): string
    {
        $contents = $this->contentsStream($filename);
        $count = (int) (unpack('v', $contents, 0x0c)[1] ?? 0);
        for ($i = 0; $i < $count; ++$i) {
            $entry = 0x20 + $i * 0x18;
            if (substr($contents, $entry + 2, 4) === $tag) {
                $meta = (array) unpack('Voffset/Vsize', $contents, $entry + 0x10);

                return substr($contents, (int) $meta['offset'], (int) $meta['size']);
            }
        }

        self::fail("CONTENTS has no {$tag} chunk.");
    }

    private function decodedText(string $filename): string
    {
        return (string) mb_convert_encoding($this->chunk($filename, 'TEXT'), 'UTF-8', 'UTF-16LE');
    }
}
