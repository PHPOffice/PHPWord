<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\DocumentProperties;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Style;

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
        $this->assertEquals(new DocumentProperties(), $phpWord->getDocumentProperties());
        $this->assertEquals(PhpWord::DEFAULT_FONT_NAME, $phpWord->getDefaultFontName());
        $this->assertEquals(PhpWord::DEFAULT_FONT_SIZE, $phpWord->getDefaultFontSize());
    }

    /**
     * Test set/get document properties
     */
    public function testSetGetDocumentProperties()
    {
        $phpWord = new PhpWord();
        $creator = 'PhpWord';
        $properties = $phpWord->getDocumentProperties();
        $properties->setCreator($creator);
        $phpWord->setDocumentProperties($properties);
        $this->assertEquals($creator, $phpWord->getDocumentProperties()->getCreator());
    }

    /**
     * Test create/get section
     */
    public function testCreateGetSections()
    {
        $phpWord = new PhpWord();
        $this->assertEquals(new Section(1), $phpWord->addSection());
        $phpWord->addSection();
        $this->assertEquals(2, count($phpWord->getSections()));
    }

    /**
     * Test set/get default font name
     */
    public function testSetGetDefaultFontName()
    {
        $phpWord = new PhpWord();
        $fontName = 'Times New Roman';
        $this->assertEquals(PhpWord::DEFAULT_FONT_NAME, $phpWord->getDefaultFontName());
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
        $this->assertEquals(PhpWord::DEFAULT_FONT_SIZE, $phpWord->getDefaultFontSize());
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
     */
    public function testLoadTemplate()
    {
        $templateFqfn = __DIR__ . "/_files/templates/blank.docx";

        $phpWord = new PhpWord();
        $this->assertInstanceOf(
            'PhpOffice\\PhpWord\\Template',
            $phpWord->loadTemplate($templateFqfn)
        );
    }

    /**
     * Test load template exception
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
}
