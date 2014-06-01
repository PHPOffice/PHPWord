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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */
namespace PhpOffice\PhpWord\Tests\Writer;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\ODText;

/**
 * Test class for PhpOffice\PhpWord\Writer\ODText
 *
 * @runTestsInSeparateProcesses
 */
class ODTextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Construct
     */
    public function testConstruct()
    {
        $object = new ODText(new PhpWord());

        $this->assertInstanceOf('PhpOffice\\PhpWord\\PhpWord', $object->getPhpWord());

        $this->assertEquals('./', $object->getDiskCachingDirectory());
        foreach (array('Content', 'Manifest', 'Meta', 'Mimetype', 'Styles') as $part) {
            $this->assertInstanceOf(
                "PhpOffice\\PhpWord\\Writer\\ODText\\Part\\{$part}",
                $object->getWriterPart($part)
            );
            $this->assertInstanceOf(
                'PhpOffice\\PhpWord\\Writer\\ODText',
                $object->getWriterPart($part)->getParentWriter()
            );
        }
    }

    /**
     * Construct with null
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage No PhpWord assigned.
     */
    public function testConstructWithNull()
    {
        $object = new ODText();
        $object->getPhpWord();
    }

    /**
     * Save
     */
    public function testSave()
    {
        $imageSrc = __DIR__ . "/../_files/images/PhpWord.png";
        $objectSrc = __DIR__ . "/../_files/documents/sheet.xls";
        $file = __DIR__ . "/../_files/temp.odt";

        $phpWord = new PhpWord();
        $phpWord->addFontStyle('Font', array('size' => 11));
        $phpWord->addParagraphStyle('Paragraph', array('align' => 'center'));
        $section = $phpWord->addSection();
        $section->addText('Test 1', 'Font');
        $section->addTextBreak();
        $section->addText('Test 2', null, 'Paragraph');
        $section->addLink('http://test.com');
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

        $this->assertTrue(file_exists($file));

        unlink($file);
    }

    /**
     * Save php output
     *
     * @todo   Haven't got any method to test this
     */
    public function testSavePhpOutput()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Test');
        $writer = new ODText($phpWord);
        $writer->save('php://output');
    }

    /**
     * Get writer part return null value
     */
    public function testGetWriterPartNull()
    {
        $object = new ODText();
        $this->assertNull($object->getWriterPart('foo'));
    }

    /**
     * Set/get use disk caching
     */
    public function testSetGetUseDiskCaching()
    {
        $object = new ODText();
        $object->setUseDiskCaching(true, PHPWORD_TESTS_BASE_DIR);
        $this->assertTrue($object->isUseDiskCaching());
        $this->assertEquals(PHPWORD_TESTS_BASE_DIR, $object->getDiskCachingDirectory());
    }

    /**
     * Use disk caching exception
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     */
    public function testSetUseDiskCachingException()
    {
        $dir = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_BASE_DIR, 'foo')
        );

        $object = new ODText();
        $object->setUseDiskCaching(true, $dir);
    }
}
