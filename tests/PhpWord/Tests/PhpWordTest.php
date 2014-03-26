<?php
namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\DocumentProperties;
use PhpOffice\PhpWord\Section;
use PhpOffice\PhpWord\Style;

/**
 * @coversDefaultClass          \PhpOffice\PhpWord\PhpWord
 * @runTestsInSeparateProcesses
 */
class PhpWordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getDocumentProperties
     * @covers ::getDefaultFontName
     * @covers ::getDefaultFontSize
     */
    public function testConstruct()
    {
        $phpWord = new PhpWord();
        $this->assertEquals(new DocumentProperties(), $phpWord->getDocumentProperties());
        $this->assertEquals(PhpWord::DEFAULT_FONT_NAME, $phpWord->getDefaultFontName());
        $this->assertEquals(PhpWord::DEFAULT_FONT_SIZE, $phpWord->getDefaultFontSize());
    }

    /**
     * @covers ::setDocumentProperties
     * @covers ::getDocumentProperties
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
     * @covers ::createSection
     * @covers ::getSections
     */
    public function testCreateGetSections()
    {
        $phpWord = new PhpWord();
        $this->assertEquals(new Section(1), $phpWord->createSection());
        $phpWord->createSection();
        $this->assertEquals(2, \count($phpWord->getSections()));
    }

    /**
     * @covers ::setDefaultFontName
     * @covers ::getDefaultFontName
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
     * @covers ::setDefaultFontSize
     * @covers ::getDefaultFontSize
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
     * @covers ::setDefaultParagraphStyle
     * @covers ::loadTemplate
     */
    public function testSetDefaultParagraphStyle()
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultParagraphStyle(array());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', Style::getStyle('Normal'));
    }

    /**
     * @covers ::addParagraphStyle
     * @covers ::addFontStyle
     * @covers ::addTableStyle
     * @covers ::addLinkStyle
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
     * @covers ::addTitleStyle
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
     * @covers ::loadTemplate
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
     * @covers            ::loadTemplate
     * @expectedException \PhpOffice\PhpWord\Exceptions\Exception
     */
    public function testLoadTemplateException()
    {
        $templateFqfn = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_BASE_DIR, 'PhpWord', 'Tests', 'data', 'templates', 'blanks.docx')
        );
        $phpWord = new PhpWord();
        $phpWord->loadTemplate($templateFqfn);
    }
}
