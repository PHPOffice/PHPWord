<?php
namespace PhpOffice\PhpWord\Tests\Writer\Word2007;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;

/**
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
        $phpWord = new PhpWord();

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
        $phpWord->setDefaultParagraphStyle($pStyle);
        $phpWord->addParagraphStyle('Base Style', $pBase);
        $phpWord->addParagraphStyle('New Style', $pNew);
        $phpWord->addFontStyle('New Style', $rStyle, $pStyle);
        $phpWord->addTableStyle('Table Style', $tStyle, $tStyle);
        $phpWord->addTitleStyle(1, $rStyle, $pStyle);
        $doc = TestHelperDOCX::getDocument($phpWord);

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
