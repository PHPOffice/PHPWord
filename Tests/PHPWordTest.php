<?php
namespace PHPWord\Tests;

use PhpOffice\PHPWord;
use PhpOffice\PhpWord\DocumentProperties;
use PhpOffice\PhpWord\Section;
use PhpOffice\PhpWord\Style;

/**
 * @package                     PHPWord\Tests
 * @coversDefaultClass          PhpOffice\PHPWord
 * @runTestsInSeparateProcesses
 */
class PHPWordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PhpOffice\PHPWord
     */
    protected $object;

    /**
     * @covers ::__construct
     * @covers ::getProperties
     * @covers ::getDefaultFontName
     * @covers ::getDefaultFontSize
     */
    public function testConstruct()
    {
        $object = new PHPWord();
        $this->assertEquals(new DocumentProperties(), $object->getProperties());
        $this->assertEquals(
            PHPWord::DEFAULT_FONT_NAME,
            $object->getDefaultFontName()
        );
        $this->assertEquals(
            PHPWord::DEFAULT_FONT_SIZE,
            $object->getDefaultFontSize()
        );
    }

    /**
     * @covers ::setProperties
     * @covers ::getProperties
     */
    public function testSetGetProperties()
    {
        $object = new PHPWord();
        $creator = 'PHPWord';
        $properties = $object->getProperties();
        $properties->setCreator($creator);
        $object->setProperties($properties);
        $this->assertEquals($creator, $object->getProperties()->getCreator());
    }

    /**
     * @covers ::createSection
     * @covers ::getSections
     */
    public function testCreateGetSections()
    {
        $object = new PHPWord();
        $this->assertEquals(new Section(1), $object->createSection());
        $object->createSection();
        $this->assertEquals(2, count($object->getSections()));
    }

    /**
     * @covers ::setDefaultFontName
     * @covers ::getDefaultFontName
     */
    public function testSetGetDefaultFontName()
    {
        $object = new PHPWord();
        $fontName = 'Times New Roman';
        $this->assertEquals(
            PHPWord::DEFAULT_FONT_NAME,
            $object->getDefaultFontName()
        );
        $object->setDefaultFontName($fontName);
        $this->assertEquals($fontName, $object->getDefaultFontName());
    }

    /**
     * @covers ::setDefaultFontSize
     * @covers ::getDefaultFontSize
     */
    public function testSetGetDefaultFontSize()
    {
        $object = new PHPWord();
        $fontSize = 16;
        $this->assertEquals(
            PHPWord::DEFAULT_FONT_SIZE,
            $object->getDefaultFontSize()
        );
        $object->setDefaultFontSize($fontSize);
        $this->assertEquals($fontSize, $object->getDefaultFontSize());
    }

    /**
     * @covers ::setDefaultParagraphStyle
     * @covers ::loadTemplate
     */
    public function testSetDefaultParagraphStyle()
    {
        $object = new PHPWord();
        $object->setDefaultParagraphStyle(array());
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
        $object = new PHPWord();
        $styles = array(
            'Paragraph' => 'Paragraph',
            'Font' => 'Font',
            'Table' => 'TableFull',
            'Link' => 'Font',
        );
        foreach ($styles as $key => $value) {
            $method = "add{$key}Style";
            $styleId = "{$key} Style";
            $object->$method($styleId, array());
            $this->assertInstanceOf("PhpOffice\\PhpWord\\Style\\{$value}", Style::getStyle($styleId));
        }

    }

    /**
     * @covers ::addTitleStyle
     */
    public function testAddTitleStyle()
    {
        $object = new PHPWord();
        $titleLevel = 1;
        $titleName = "Heading_{$titleLevel}";
        $object->addTitleStyle($titleLevel, array());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', Style::getStyle($titleName));
    }

    /**
     * @covers ::loadTemplate
     */
    public function testLoadTemplate()
    {
        $file = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_DIR_ROOT, '_files', 'templates', 'blank.docx')
        );
        $object = new PHPWord();
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Template', $object->loadTemplate($file));
    }

    /**
     * @covers            ::loadTemplate
     * @expectedException PhpOffice\PhpWord\Exceptions\Exception
     */
    public function testLoadTemplateException()
    {
        $file = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_DIR_ROOT, '_files', 'templates', 'blanks.docx')
        );
        $object = new PHPWord();
        $object->loadTemplate($file);
    }
}