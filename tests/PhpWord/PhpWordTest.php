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
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\Metadata\DocInfo;

/**
 * Test class for PhpOffice\PhpWord\PhpWord
 *
 * @runTestsInSeparateProcesses
 */
class PhpWordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test object creation
     */
    public function testConstruct()
    {
        $phpWord = new PhpWord();
        $this->assertEquals(new DocInfo(), $phpWord->getDocInfo());
        $this->assertEquals(Settings::DEFAULT_FONT_NAME, $phpWord->getDefaultFontName());
        $this->assertEquals(Settings::DEFAULT_FONT_SIZE, $phpWord->getDefaultFontSize());
    }

    /**
     * Test create/get section
     */
    public function testCreateGetSections()
    {
        $phpWord = new PhpWord();
        $phpWord->addSection();
        $this->assertCount(1, $phpWord->getSections());
    }

    /**
     * Test set/get default font name
     */
    public function testSetGetDefaultFontName()
    {
        $phpWord = new PhpWord();
        $fontName = 'Times New Roman';
        $this->assertEquals(Settings::DEFAULT_FONT_NAME, $phpWord->getDefaultFontName());
        $phpWord->setDefaultFontName($fontName);
        $this->assertEquals($fontName, $phpWord->getDefaultFontName());
    }

    /**
     * Test set/get default font size
     */
    public function testSetGetDefaultFontSize()
    {
        $phpWord = new PhpWord();
        $fontSize = 16;
        $this->assertEquals(Settings::DEFAULT_FONT_SIZE, $phpWord->getDefaultFontSize());
        $phpWord->setDefaultFontSize($fontSize);
        $this->assertEquals($fontSize, $phpWord->getDefaultFontSize());
    }

    /**
     * Test set default paragraph style
     */
    public function testSetDefaultParagraphStyle()
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultParagraphStyle(array());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', Style::getStyle('Normal'));
    }

    /**
     * Test add styles
     */
    public function testAddStyles()
    {
        $phpWord = new PhpWord();
        $styles = array(
            'Paragraph' => 'Paragraph',
            'Font'      => 'Font',
            'Table'     => 'Table',
            'Link'      => 'Font',
        );
        foreach ($styles as $key => $value) {
            $method = "add{$key}Style";
            $styleId = "{$key} Style";
            $phpWord->$method($styleId, array());
            $this->assertInstanceOf("PhpOffice\\PhpWord\\Style\\{$value}", Style::getStyle($styleId));
        }

    }

    /**
     * Test add title style
     */
    public function testAddTitleStyle()
    {
        $phpWord = new PhpWord();
        $titleLevel = 1;
        $titleName = "Heading_{$titleLevel}";
        $phpWord->addTitleStyle($titleLevel, array());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', Style::getStyle($titleName));
    }

    /**
     * Test load template
     *
     * @deprecated 0.12.0
     */
    public function testLoadTemplate()
    {
        $templateFqfn = __DIR__ . '/_files/templates/blank.docx';

        $phpWord = new PhpWord();
        $this->assertInstanceOf(
            'PhpOffice\\PhpWord\\TemplateProcessor',
            $phpWord->loadTemplate($templateFqfn)
        );
    }

    /**
     * Test load template exception
     *
     * @deprecated 0.12.0
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     */
    public function testLoadTemplateException()
    {
        $templateFqfn = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_BASE_DIR, 'PhpWord', 'Tests', '_files', 'templates', 'blanks.docx')
        );
        $phpWord = new PhpWord();
        $phpWord->loadTemplate($templateFqfn);
    }

    /**
     * Test save
     */
    public function testSave()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Hello world!');

        $this->assertTrue($phpWord->save('test.docx', 'Word2007', true));
    }

    /**
     * Test calling undefined method
     *
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage is not defined
     */
    public function testCallUndefinedMethod()
    {
        $phpWord = new PhpWord();
        $phpWord->undefinedMethod();
    }
}
