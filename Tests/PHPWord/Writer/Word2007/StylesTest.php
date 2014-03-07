<?php
namespace PHPWord\Tests;

use PHPUnit_Framework_TestCase;
use PHPWord;
use PHPWord_Writer_Word2007_Styles;

/**
 * Class PHPWord_Writer_Word2007_StylesTest
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class PHPWord_Writer_Word2007_StylesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test write styles
     */
    public function testWriteStyles()
    {
        $PHPWord = new PHPWord();

        $defaultStyle = array('align' => 'both');
        $baseStyle = array('basedOn' => 'Normal');
        $newStyle = array('basedOn' => 'Base Style', 'next' => 'Normal');
        $PHPWord->setDefaultParagraphStyle($defaultStyle);
        $PHPWord->addParagraphStyle('Base Style', $baseStyle);
        $PHPWord->addParagraphStyle('New Style', $newStyle);
        $doc = TestHelperDOCX::getDocument($PHPWord);
        $file = 'word/styles.xml';

        // Normal style generated?
        $path = '/w:styles/w:style[@w:styleId="Normal"]/w:name';
        $element = $doc->getElement($path, $file);
        $this->assertEquals('Normal', $element->getAttribute('w:val'));

        // Parent style referenced?
        $path = '/w:styles/w:style[@w:styleId="New Style"]/w:basedOn';
        $element = $doc->getElement($path, $file);
        $this->assertEquals('Base Style', $element->getAttribute('w:val'));

        // Next paragraph style correct?
        $path = '/w:styles/w:style[@w:styleId="New Style"]/w:next';
        $element = $doc->getElement($path, $file);
        $this->assertEquals('Normal', $element->getAttribute('w:val'));
    }

}
