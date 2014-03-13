<?php
namespace PHPWord\Tests\Writer\Word2007;

use PHPWord;
use PHPWord\Tests\TestHelperDOCX;

/**
 * Class PHPWord_Writer_Word2007_StylesTest
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class StylesTest extends \PHPUnit_Framework_TestCase
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

        $pStyle = array('align' => 'both');
        $pBase = array('basedOn' => 'Normal');
        $pNew = array('basedOn' => 'Base Style', 'next' => 'Normal');
        $rStyle = array('size' => 20);
        $tStyle = array(
            'bgColor' => 'FF0000',
            'cellMarginTop' => 120,
            'cellMarginBottom' => 120,
            'cellMarginLeft' => 120,
            'cellMarginRight' => 120,
            'borderTopSize' => 120,
            'borderBottomSize' => 120,
            'borderLeftSize' => 120,
            'borderRightSize' => 120,
            'borderInsideHSize' => 120,
            'borderInsideVSize' => 120,
        );
        $PHPWord->setDefaultParagraphStyle($pStyle);
        $PHPWord->addParagraphStyle('Base Style', $pBase);
        $PHPWord->addParagraphStyle('New Style', $pNew);
        $PHPWord->addFontStyle('New Style', $rStyle, $pStyle);
        $PHPWord->addTableStyle('Table Style', $tStyle, $tStyle);
        $PHPWord->addTitleStyle(1, $rStyle, $pStyle);
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
