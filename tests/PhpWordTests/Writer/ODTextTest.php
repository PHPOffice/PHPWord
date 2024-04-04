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

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Writer\ODText;

/**
 * Test class for PhpOffice\PhpWord\Writer\ODText.
 *
 * @runTestsInSeparateProcesses
 */
class ODTextTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Construct.
     */
    public function testConstruct(): void
    {
        $object = new ODText(new PhpWord());

        self::assertInstanceOf('PhpOffice\\PhpWord\\PhpWord', $object->getPhpWord());

        self::assertEquals('./', $object->getDiskCachingDirectory());
        foreach (['Content', 'Manifest', 'Meta', 'Mimetype', 'Styles'] as $part) {
            self::assertInstanceOf(
                "PhpOffice\\PhpWord\\Writer\\ODText\\Part\\{$part}",
                $object->getWriterPart($part)
            );
            self::assertInstanceOf(
                'PhpOffice\\PhpWord\\Writer\\ODText',
                $object->getWriterPart($part)->getParentWriter()
            );
        }
    }

    /**
     * Construct with null.
     */
    public function testConstructWithNull(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\Exception::class);
        $this->expectExceptionMessage('No PhpWord assigned.');
        $object = new ODText();
        $object->getPhpWord();
    }

    /**
     * Save.
     */
    public function testSave(): void
    {
        $imageSrc = __DIR__ . '/../_files/images/PhpWord.png';
        $objectSrc = __DIR__ . '/../_files/documents/sheet.xls';
        $file = __DIR__ . '/../_files/temp.odt';

        $phpWord = new PhpWord();
        $phpWord->addFontStyle('Font', ['size' => 11]);
        $phpWord->addParagraphStyle('Paragraph', ['alignment' => Jc::CENTER]);
        $section = $phpWord->addSection();
        $section->addText('Test 1', 'Font');
        $section->addTextBreak();
        $section->addText('Test 2', null, 'Paragraph');
        $section->addLink('https://github.com/PHPOffice/PHPWord');
        $section->addTitle('Test', 1);
        $section->addPageBreak();
        $section->addTable()->addRow()->addCell()->addText('Test');
        $section->addListItem('Test');
        $section->addImage($imageSrc);
        $section->addObject($objectSrc);
        $section->addTOC();
        $section = $phpWord->addSection();
        $textrun = $section->addTextRun();
        $textrun->addText('Test 3');
        $writer = new ODText($phpWord);
        $writer->save($file);

        self::assertFileExists($file);

        unlink($file);
    }

    /**
     * Save php output.
     *
     * @todo   Haven't got any method to test this
     */
    public function testSavePhpOutput(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Test');
        $writer = new ODText($phpWord);
        ob_start();
        $writer->save('php://output');
        $contents = ob_get_contents();
        self::assertTrue(ob_end_clean());
        self::assertNotEmpty($contents);
    }

    /**
     * Get writer part return null value.
     */
    public function testGetWriterPartNull(): void
    {
        $object = new ODText();
        self::assertNull($object->getWriterPart('foo'));
    }

    /**
     * Set/get use disk caching.
     */
    public function testSetGetUseDiskCaching(): void
    {
        $object = new ODText();
        $object->setUseDiskCaching(true, PHPWORD_TESTS_BASE_DIR);
        self::assertTrue($object->isUseDiskCaching());
        self::assertEquals(PHPWORD_TESTS_BASE_DIR, $object->getDiskCachingDirectory());
    }

    /**
     * Use disk caching exception.
     */
    public function testSetUseDiskCachingException(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\Exception::class);
        $dir = implode(DIRECTORY_SEPARATOR, [PHPWORD_TESTS_BASE_DIR, 'foo']);

        $object = new ODText();
        $object->setUseDiskCaching(true, $dir);
    }
}
