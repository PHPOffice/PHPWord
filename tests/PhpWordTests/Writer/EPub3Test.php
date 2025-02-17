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
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Writer\EPub3;

/**
 * Test class for PhpOffice\PhpWord\Writer\Epub3.
 *
 * @runTestsInSeparateProcesses
 */
class EPub3Test extends \PHPUnit\Framework\TestCase
{
    /**
     * Test document construction.
     */
    public function testConstruct(): void
    {
        $object = new EPub3(new PhpWord());
        self::assertInstanceOf(PhpWord::class, $object->getPhpWord());
        self::assertEquals('./', $object->getDiskCachingDirectory());
        foreach (['Content', 'Manifest', 'Mimetype'] as $part) {
            self::assertInstanceOf(
                "PhpOffice\\PhpWord\\Writer\\Epub3\\Part\\{$part}",
                $object->getWriterPart($part)
            );
            self::assertInstanceOf(
                'PhpOffice\\PhpWord\\Writer\\Epub3',
                $object->getWriterPart($part)->getParentWriter()
            );
        }
    }

    /**
     * Test construction with null.
     */
    public function testConstructWithNull(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No PhpWord assigned.');

        $writer = new EPub3();
        $writer->getWriterPart('content')->write();
    }

    /**
     * Test saving document.
     */
    public function testSave(): void
    {
        $imageSrc = __DIR__ . '/../_files/images/PhpWord.png';
        $file = __DIR__ . '/../_files/temp.epub';

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Test 1');
        $section->addTextBreak();
        $section->addText('Test 2', null, ['alignment' => Jc::CENTER]);
        $section->addLink('https://github.com/PHPOffice/PHPWord');
        $section->addTitle('Test', 1);
        $section->addPageBreak();
        $section->addImage($imageSrc);
        $writer = new EPub3($phpWord);
        $writer->save($file);
        self::assertFileExists($file);
        unlink($file);
    }

    /**
     * Test PHP output.
     */
    public function testSavePhpOutput(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Test');
        $writer = new EPub3($phpWord);
        ob_start();
        $writer->save('php://output');
        $contents = ob_get_contents();
        self::assertTrue(ob_end_clean());
        self::assertNotEmpty($contents);
    }

    /**
     * Test disk caching.
     */
    public function testSetGetUseDiskCaching(): void
    {
        $object = new EPub3();
        $object->setUseDiskCaching(true, PHPWORD_TESTS_BASE_DIR);
        self::assertTrue($object->isUseDiskCaching());
        self::assertEquals(PHPWORD_TESTS_BASE_DIR, $object->getDiskCachingDirectory());
    }

    /**
     * Test disk caching exception.
     */
    public function testSetUseDiskCachingException(): void
    {
        $this->expectException(Exception::class);
        $dir = implode(DIRECTORY_SEPARATOR, [PHPWORD_TESTS_BASE_DIR, 'foo']);

        $object = new EPub3();
        $object->setUseDiskCaching(true, $dir);
    }
}
