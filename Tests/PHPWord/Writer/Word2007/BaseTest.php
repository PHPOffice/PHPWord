<?php
namespace PHPWord\Tests\Writer\Word2007;

use PHPWord;
use PHPWord_Style;
use PHPWord\Tests\TestHelperDOCX;

/**
 * Class BaseTest
 *
 * @package             PHPWord\Tests
 * @coversDefaultClass  PHPWord_Writer_Word2007_Base
 * @runTestsInSeparateProcesses
 */
class BaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * covers   ::_writeText
     */
    public function testWriteText()
    {
        $rStyle = 'rStyle';
        $pStyle = 'pStyle';

        $PHPWord = new PHPWord();
        $PHPWord->addFontStyle($rStyle, array('bold' => true));
        $PHPWord->addParagraphStyle($pStyle, array('hanging' => 120, 'indent' => 120));
        $section = $PHPWord->createSection();
        $section->addText('Test', $rStyle, $pStyle);
        $doc = TestHelperDOCX::getDocument($PHPWord);

        $element = "/w:document/w:body/w:p/w:r/w:rPr/w:rStyle";
        $this->assertEquals($rStyle, $doc->getElementAttribute($element, 'w:val'));
        $element = "/w:document/w:body/w:p/w:pPr/w:pStyle";
        $this->assertEquals($pStyle, $doc->getElementAttribute($element, 'w:val'));
    }

    /**
     * covers   ::_writeTextRun
     */
    public function testWriteTextRun()
    {
        $pStyle = 'pStyle';
        $aStyle = array('align' => 'justify', 'spaceBefore' => 120, 'spaceAfter' => 120);
        $imageSrc = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'earth.jpg')
        );

        $PHPWord = new PHPWord();
        $PHPWord->addParagraphStyle($pStyle, $aStyle);
        $section = $PHPWord->createSection('Test');
        $textrun = $section->createTextRun($pStyle);
        $textrun->addText('Test');
        $textrun->addTextBreak();
        $textrun = $section->createTextRun($aStyle);
        $textrun->addLink('http://test.com');
        $textrun->addImage($imageSrc);
        $doc = TestHelperDOCX::getDocument($PHPWord);

        $parent = "/w:document/w:body/w:p";
        $this->assertTrue($doc->elementExists("{$parent}/w:pPr/w:pStyle[@w:val='{$pStyle}']"));
    }

    /**
     * covers   ::_writeLink
     */
    public function testWriteLink()
    {
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();

        $expected = 'PHPWord';
        $section->addLink('http://github.com/phpoffice/phpword', $expected);

        $doc = TestHelperDOCX::getDocument($PHPWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:hyperlink/w:r/w:t');

        $this->assertEquals($expected, $element->nodeValue);
    }

    /**
     * covers   ::_writePreserveText
     */
    public function testWritePreserveText()
    {
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();
        $footer = $section->createFooter();

        $footer->addPreserveText('{PAGE}');

        $doc = TestHelperDOCX::getDocument($PHPWord);
        $preserve = $doc->getElement("w:p/w:r[2]/w:instrText", 'word/footer1.xml');

        $this->assertEquals('PAGE', $preserve->nodeValue);
        $this->assertEquals('preserve', $preserve->getAttribute('xml:space'));
    }
    /**
     * covers   ::_writeTextBreak
     */
    public function testWriteTextBreak()
    {
        $fArray = array('size' => 12);
        $pArray = array('spacing' => 240);
        $fName = 'fStyle';
        $pName = 'pStyle';

        $PHPWord = new PHPWord();
        $PHPWord->addFontStyle($fName, $fArray);
        $PHPWord->addParagraphStyle($pName, $pArray);
        $section = $PHPWord->createSection();
        $section->addTextBreak();
        $section->addTextBreak(1, $fArray, $pArray);
        $section->addTextBreak(1, $fName, $pName);
        $doc = TestHelperDOCX::getDocument($PHPWord);

        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:rPr/w:rStyle');
        $this->assertEquals($fName, $element->getAttribute('w:val'));
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:pStyle');
        $this->assertEquals($pName, $element->getAttribute('w:val'));
    }

    /**
     * covers   ::_writeParagraphStyle
     */
    public function testWriteParagraphStyleAlign()
    {
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();

        $section->addText('This is my text', null, array('align' => 'right'));

        $doc = TestHelperDOCX::getDocument($PHPWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:jc');

        $this->assertEquals('right', $element->getAttribute('w:val'));
    }

    /**
     * covers   ::_writeParagraphStyle
     */
    public function testWriteParagraphStylePagination()
    {
        // Create the doc
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();
        $attributes = array(
            'widowControl' => false,
            'keepNext' => true,
            'keepLines' => true,
            'pageBreakBefore' => true,
        );
        foreach ($attributes as $attribute => $value) {
            $section->addText('Test', null, array($attribute => $value));
        }
        $doc = TestHelperDOCX::getDocument($PHPWord);

        // Test the attributes
        $i = 0;
        foreach ($attributes as $key => $value) {
            $i++;
            $path = "/w:document/w:body/w:p[{$i}]/w:pPr/w:{$key}";
            $element = $doc->getElement($path);
            $expected = $value ? 1 : 0;
            $this->assertEquals($expected, $element->getAttribute('w:val'));
        }
    }

    /**
     * covers   ::_writeTextStyle
     */
    public function testWriteFontStyle()
    {
        $PHPWord = new PHPWord();
        $styles['name'] = 'Verdana';
        $styles['size'] = 14;
        $styles['bold'] = true;
        $styles['italic'] = true;
        $styles['underline'] = 'dash';
        $styles['strikethrough'] = true;
        $styles['superScript'] = true;
        $styles['color'] = 'FF0000';
        $styles['fgColor'] = 'yellow';

        $section = $PHPWord->createSection();
        $section->addText('Test', $styles);
        $doc = TestHelperDOCX::getDocument($PHPWord);

        $parent = '/w:document/w:body/w:p/w:r/w:rPr';
        $this->assertEquals($styles['name'], $doc->getElementAttribute("{$parent}/w:rFonts", 'w:ascii'));
        $this->assertEquals($styles['size'] * 2, $doc->getElementAttribute("{$parent}/w:sz", 'w:val'));
        $this->assertTrue($doc->elementExists("{$parent}/w:b"));
        $this->assertTrue($doc->elementExists("{$parent}/w:i"));
        $this->assertEquals($styles['underline'], $doc->getElementAttribute("{$parent}/w:u", 'w:val'));
        $this->assertTrue($doc->elementExists("{$parent}/w:strike"));
        $this->assertEquals('superscript', $doc->getElementAttribute("{$parent}/w:vertAlign", 'w:val'));
        $this->assertEquals($styles['color'], $doc->getElementAttribute("{$parent}/w:color", 'w:val'));
        $this->assertEquals($styles['fgColor'], $doc->getElementAttribute("{$parent}/w:highlight", 'w:val'));
    }

    /**
     * covers   ::_writeTableStyle
     */
    public function testWriteTableStyle()
    {
        $PHPWord = new PHPWord();
        $tWidth = 120;
        $rHeight = 120;
        $cWidth = 120;
        $tStyles["cellMarginTop"] = 120;
        $tStyles["cellMarginRight"] = 120;
        $tStyles["cellMarginBottom"] = 120;
        $tStyles["cellMarginLeft"] = 120;
        $rStyles["tblHeader"] = true;
        $rStyles["cantSplit"] = true;
        $cStyles["valign"] = 'top';
        $cStyles["textDirection"] = 'btLr';
        $cStyles["bgColor"] = 'FF0000';
        $cStyles["borderTopSize"] = 120;
        $cStyles["borderBottomSize"] = 120;
        $cStyles["borderLeftSize"] = 120;
        $cStyles["borderRightSize"] = 120;
        $cStyles["borderTopColor"] = 'FF0000';
        $cStyles["borderBottomColor"] = 'FF0000';
        $cStyles["borderLeftColor"] = 'FF0000';
        $cStyles["borderRightColor"] = 'FF0000';

        $section = $PHPWord->createSection();
        $table = $section->addTable($tStyles);
        $table->setWidth = 100;
        $table->addRow($rHeight, $rStyles);
        $cell = $table->addCell($cWidth, $cStyles);
        $cell->addText('Test');
        $cell->addTextBreak();
        $cell->addLink('http://google.com');
        $cell->addListItem('Test');
        $textrun = $cell->createTextRun();
        $textrun->addText('Test');

        $doc = TestHelperDOCX::getDocument($PHPWord);

        $parent = '/w:document/w:body/w:tbl/w:tblPr/w:tblCellMar';
        $this->assertEquals($tStyles['cellMarginTop'], $doc->getElementAttribute("{$parent}/w:top", 'w:w'));
        $this->assertEquals($tStyles['cellMarginRight'], $doc->getElementAttribute("{$parent}/w:right", 'w:w'));
        $this->assertEquals($tStyles['cellMarginBottom'], $doc->getElementAttribute("{$parent}/w:bottom", 'w:w'));
        $this->assertEquals($tStyles['cellMarginLeft'], $doc->getElementAttribute("{$parent}/w:right", 'w:w'));

        $parent = '/w:document/w:body/w:tbl/w:tr/w:trPr';
        $this->assertEquals($rHeight, $doc->getElementAttribute("{$parent}/w:trHeight", 'w:val'));
        $this->assertEquals($rStyles['tblHeader'], $doc->getElementAttribute("{$parent}/w:tblHeader", 'w:val'));
        $this->assertEquals($rStyles['cantSplit'], $doc->getElementAttribute("{$parent}/w:cantSplit", 'w:val'));

        $parent = '/w:document/w:body/w:tbl/w:tr/w:tc/w:tcPr';
        $this->assertEquals($cWidth, $doc->getElementAttribute("{$parent}/w:tcW", 'w:w'));
        $this->assertEquals($cStyles['valign'], $doc->getElementAttribute("{$parent}/w:vAlign", 'w:val'));
        $this->assertEquals($cStyles['textDirection'], $doc->getElementAttribute("{$parent}/w:textDirection", 'w:val'));
    }

    /**
     * covers   ::_writeCellStyle
     */
    public function testWriteCellStyleCellGridSpan()
    {
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();

        $table = $section->addTable();

        $table->addRow();
        $cell = $table->addCell(200);
        $cell->getStyle()->setGridSpan(5);

        $table->addRow();
        $table->addCell(40);
        $table->addCell(40);
        $table->addCell(40);
        $table->addCell(40);
        $table->addCell(40);

        $doc = TestHelperDOCX::getDocument($PHPWord);
        $element = $doc->getElement('/w:document/w:body/w:tbl/w:tr/w:tc/w:tcPr/w:gridSpan');

        $this->assertEquals(5, $element->getAttribute('w:val'));
    }

    /**
     * covers   ::_writeImage
     */
    public function testWriteImagePosition()
    {
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();
        $section->addImage(
            PHPWORD_TESTS_DIR_ROOT . '/_files/images/earth.jpg',
            array(
                'marginTop' => -1,
                'marginLeft' => -1,
                'wrappingStyle' => 'behind'
            )
        );

        $doc = TestHelperDOCX::getDocument($PHPWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:r/w:pict/v:shape');

        $style = $element->getAttribute('style');

        $this->assertRegExp('/z\-index:\-[0-9]*/', $style);
        $this->assertRegExp('/position:absolute;/', $style);
    }

    /**
     * covers   ::_writeWatermark
     */
    public function testWriteWatermark()
    {
        $imageSrc = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'earth.jpg')
        );

        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection();
        $header = $section->createHeader();
        $header->addWatermark($imageSrc);
        $doc = TestHelperDOCX::getDocument($PHPWord);

        $element = $doc->getElement("/w:document/w:body/w:sectPr/w:headerReference");
        $this->assertStringStartsWith("rId", $element->getAttribute('r:id'));
    }

    /**
     * covers   ::_writeTitle
     */
    public function testWriteTitle()
    {
        $PHPWord = new PHPWord();
        $PHPWord->addTitleStyle(1, array('bold' => true), array('spaceAfter' => 240));
        $PHPWord->createSection()->addTitle('Test', 1);
        $doc = TestHelperDOCX::getDocument($PHPWord);

        $element = "/w:document/w:body/w:p/w:pPr/w:pStyle";
        $this->assertEquals('Heading1', $doc->getElementAttribute($element, 'w:val'));
        $element = "/w:document/w:body/w:p/w:r/w:fldChar";
        $this->assertEquals('end', $doc->getElementAttribute($element, 'w:fldCharType'));
    }
}
