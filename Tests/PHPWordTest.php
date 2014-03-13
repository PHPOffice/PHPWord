<?php
namespace PHPWord\Tests;

use PHPWord;
use PHPWord_DocumentProperties;
use PHPWord_Section;
use PHPWord_Style;

/**
 * Class PHPWordTest
 *
 * @package PHPWord\Tests
 * @covers  PHPWord
 * @runTestsInSeparateProcesses
 */
class PHPWordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PHPWord
     */
    protected $object;

    /**
     * @covers PHPWord::__construct
     * @covers PHPWord::getProperties
     * @covers PHPWord::getDefaultFontName
     * @covers PHPWord::getDefaultFontSize
     */
    public function testConstruct()
    {
        $object = new PHPWord();
        $this->assertEquals(
            new PHPWord_DocumentProperties(),
            $object->getProperties()
        );
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
     * @covers PHPWord::setProperties
     * @covers PHPWord::getProperties
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
     * @covers PHPWord::createSection
     * @covers PHPWord::getSections
     */
    public function testCreateGetSections()
    {
        $object = new PHPWord();
        $this->assertEquals(new PHPWord_Section(1), $object->createSection());
        $object->createSection();
        $this->assertEquals(2, count($object->getSections()));
    }

    /**
     * @covers PHPWord::setDefaultFontName
     * @covers PHPWord::getDefaultFontName
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
     * @covers PHPWord::setDefaultFontSize
     * @covers PHPWord::getDefaultFontSize
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
     * @covers PHPWord::setDefaultParagraphStyle
     * @covers PHPWord::loadTemplate
     */
    public function testSetDefaultParagraphStyle()
    {
        $object = new PHPWord();
        $object->setDefaultParagraphStyle(array());
        $this->assertInstanceOf(
            'PHPWord_Style_Paragraph',
            PHPWord_Style::getStyle('Normal')
        );
    }

    /**
     * @covers PHPWord::addParagraphStyle
     * @covers PHPWord::addFontStyle
     * @covers PHPWord::addTableStyle
     * @covers PHPWord::addLinkStyle
     */
    public function testAddStyles()
    {
        $object = new PHPWord();
        $styles = array('Paragraph' => 'Paragraph', 'Font' => 'Font',
            'Table' => 'TableFull', 'Link' => 'Font');
        foreach ($styles as $key => $value) {
            $method = "add{$key}Style";
            $styleId = "{$key} Style";
            $styleType = "PHPWord_Style_{$value}";
            $object->$method($styleId, array());
            $this->assertInstanceOf(
                $styleType,
                PHPWord_Style::getStyle($styleId)
            );
        }

    }

    /**
     * @covers PHPWord::addTitleStyle
     */
    public function testAddTitleStyle()
    {
        $object = new PHPWord();
        $titleLevel = 1;
        $titleName = "Heading_{$titleLevel}";
        $object->addTitleStyle($titleLevel, array());
        $this->assertInstanceOf(
            'PHPWord_Style_Font',
            PHPWord_Style::getStyle($titleName)
        );
    }

    /**
     * @covers PHPWord::loadTemplate
     */
    public function testLoadTemplate()
    {
        $file = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_DIR_ROOT, '_files', 'templates', 'blank.docx')
        );
        $object = new PHPWord();
        $this->assertInstanceOf(
            'PHPWord_Template',
            $object->loadTemplate($file)
        );
    }

    /**
     * @covers PHPWord::loadTemplate
     * @expectedException PHPWord_Exception
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
