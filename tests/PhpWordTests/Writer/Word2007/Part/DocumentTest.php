<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\Writer\Word2007\Part;

use DateTime;
use PhpOffice\PhpWord\ComplexType\FootnoteProperties;
use PhpOffice\PhpWord\Metadata\DocInfo;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\NumberFormat;
use PhpOffice\PhpWord\Style\Cell;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Part\Document.
 *
 * @runTestsInSeparateProcesses
 */
class DocumentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Write custom properties.
     */
    public function testWriteCustomProps(): void
    {
        $phpWord = new PhpWord();
        $docInfo = $phpWord->getDocInfo();

        $docInfo->setCustomProperty('key1', null);
        $docInfo->setCustomProperty('key2', true);
        $docInfo->setCustomProperty('key3', 3);
        $docInfo->setCustomProperty('key4', 4.4);
        $docInfo->setCustomProperty('key5', 'value5');
        $docInfo->setCustomProperty('key6', new DateTime());
        $docInfo->setCustomProperty('key7', time(), DocInfo::PROPERTY_TYPE_DATE);

        $doc = TestHelperDOCX::getDocument($phpWord);
        self::assertNotNull($doc);

        //         $this->assertTrue($doc->elementExists('/Properties/property[name="key1"]/vt:lpwstr'));
        //         $this->assertTrue($doc->elementExists('/Properties/property[name="key2"]/vt:bool'));
        //         $this->assertTrue($doc->elementExists('/Properties/property[name="key3"]/vt:i4'));
        //         $this->assertTrue($doc->elementExists('/Properties/property[name="key4"]/vt:r8'));
        //         $this->assertTrue($doc->elementExists('/Properties/property[name="key5"]/vt:lpwstr'));
    }

    /**
     * Write end section page numbering.
     */
    public function testWriteEndSectionPageNumbering(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addHeader();
        $section->addHeader('first');
        $style = $section->getStyle();
        $style->setLandscape();
        $style->setPageNumberingStart(2);
        $style->setBorderSize(240);
        $style->setBreakType('nextPage');

        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:sectPr/w:pgNumType');

        self::assertEquals(2, $element->getAttribute('w:start'));
    }

    /**
     * Write section footnote properties.
     */
    public function testSectionFootnoteProperties(): void
    {
        $properties = new FootnoteProperties();
        $properties->setPos(FootnoteProperties::POSITION_DOC_END);
        $properties->setNumFmt(NumberFormat::LOWER_ROMAN);
        $properties->setNumStart(1);
        $properties->setNumRestart(FootnoteProperties::RESTART_NUMBER_EACH_PAGE);

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->setFootnoteProperties($properties);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = $doc->getElement('/w:document/w:body/w:sectPr/w:footnotePr/w:pos');
        self::assertEquals(FootnoteProperties::POSITION_DOC_END, $element->getAttribute('w:val'));

        $element = $doc->getElement('/w:document/w:body/w:sectPr/w:footnotePr/w:numFmt');
        self::assertEquals(NumberFormat::LOWER_ROMAN, $element->getAttribute('w:val'));

        $element = $doc->getElement('/w:document/w:body/w:sectPr/w:footnotePr/w:numStart');
        self::assertEquals(1, $element->getAttribute('w:val'));

        $element = $doc->getElement('/w:document/w:body/w:sectPr/w:footnotePr/w:numRestart');
        self::assertEquals(FootnoteProperties::RESTART_NUMBER_EACH_PAGE, $element->getAttribute('w:val'));
    }

    /**
     * Write elements.
     */
    public function testElements(): void
    {
        $objectSrc = __DIR__ . '/../../../_files/documents/sheet.xls';

        $phpWord = new PhpWord();
        $phpWord->addTitleStyle(1, ['color' => '333333', 'bold' => true]);
        $phpWord->addTitleStyle(2, ['color' => '666666']);
        $section = $phpWord->addSection();
        $section->addTOC();
        $section->addPageBreak();
        $section->addText('After page break.');
        $section->addTitle('Title 1', 1);
        $section->addListItem('List Item 1', 0);
        $section->addListItem('List Item 2', 0);
        $section->addListItem('List Item 3', 0);

        $section = $phpWord->addSection();
        $section->addTitle('Title 2', 2);
        $section->addObject($objectSrc);
        $section->addTextBox([]);
        $section->addTextBox(
            [
                'wrappingStyle' => 'square',
                'positioning' => 'relative',
                'posHorizontalRel' => 'margin',
                'posVerticalRel' => 'margin',
                'innerMargin' => 10,
                'borderSize' => 1,
                'borderColor' => '#FF0',
            ]
        );
        $section->addTextBox(['wrappingStyle' => 'tight', 'positioning' => 'absolute', 'alignment' => Jc::CENTER]);
        $section->addListItemRun()->addText('List item run 1');
        $section->addField(
            'DATE',
            ['dateformat' => 'dddd d MMMM yyyy H:mm:ss'],
            ['PreserveFormat', 'LunarCalendar']
        );
        $section->addField(
            'DATE',
            ['dateformat' => 'dddd d MMMM yyyy H:mm:ss'],
            ['PreserveFormat', 'SakaEraCalendar']
        );
        $section->addField(
            'DATE',
            ['dateformat' => 'dddd d MMMM yyyy H:mm:ss'],
            ['PreserveFormat', 'LastUsedFormat']
        );
        $section->addField('PAGE', ['format' => 'ArabicDash']);
        $section->addLine(
            [
                'width' => 10,
                'height' => 10,
                'positioning' => 'absolute',
                'beginArrow' => 'block',
                'endArrow' => 'open',
                'dash' => 'rounddot',
                'weight' => 10,
            ]
        );

        $doc = TestHelperDOCX::getDocument($phpWord);

        // TOC
        $element = $doc->getElement('/w:document/w:body/w:p[1]/w:pPr/w:tabs/w:tab');
        self::assertEquals('right', $element->getAttribute('w:val'));
        self::assertEquals('dot', $element->getAttribute('w:leader'));
        self::assertEquals(9062, $element->getAttribute('w:pos'));

        // Page break
        $element = $doc->getElement('/w:document/w:body/w:p[4]/w:r/w:br');
        self::assertEquals('page', $element->getAttribute('w:type'));

        // Title
        $element = $doc->getElement('/w:document/w:body/w:p[6]/w:pPr/w:pStyle');
        self::assertEquals('Heading1', $element->getAttribute('w:val'));

        // List item
        $element = $doc->getElement('/w:document/w:body/w:p[7]/w:pPr/w:numPr/w:numId');
        self::assertEquals(3, $element->getAttribute('w:val'));

        // Object
        $element = $doc->getElement('/w:document/w:body/w:p[12]/w:r/w:object/o:OLEObject');
        self::assertEquals('Embed', $element->getAttribute('Type'));
    }

    /**
     * Write element with some styles.
     */
    public function testElementStyles(): void
    {
        $objectSrc = __DIR__ . '/../../../_files/documents/sheet.xls';

        $tabs = [new \PhpOffice\PhpWord\Style\Tab('right', 9090)];
        $phpWord = new PhpWord();
        $phpWord->addParagraphStyle(
            'pStyle',
            [
                'alignment' => Jc::CENTER,
                'tabs' => $tabs,
                'shading' => ['fill' => 'FFFF99'],
                'borderSize' => 4,
            ]
        ); // Style #1
        $phpWord->addFontStyle(
            'fStyle',
            [
                'size' => '20',
                'bold' => true,
                'allCaps' => true,
                'scale' => 200,
                'spacing' => 240,
                'kerning' => 10,
            ]
        ); // Style #2
        $phpWord->addTitleStyle(1, ['color' => '333333', 'doubleStrikethrough' => true]); // Style #3
        $phpWord->addTableStyle('tStyle', ['borderSize' => 1]);
        $fontStyle = new Font('text', ['alignment' => Jc::CENTER]);

        $section = $phpWord->addSection();
        $section->addListItem('List Item', 0, null, null, 'pStyle'); // Style #5
        $section->addObject($objectSrc, ['alignment' => Jc::CENTER]);
        $section->addTOC($fontStyle);
        $section->addTitle('Title 1', 1);
        $section->addTOC('fStyle');
        $table = $section->addTable('tStyle');
        $table->setWidth(100);
        $doc = TestHelperDOCX::getDocument($phpWord);

        // List item
        $element = $doc->getElement('/w:document/w:body/w:p[1]/w:pPr/w:numPr/w:numId');
        self::assertEquals(5, $element->getAttribute('w:val'));

        // Object
        $element = $doc->getElement('/w:document/w:body/w:p[2]/w:r/w:object/o:OLEObject');
        self::assertEquals('Embed', $element->getAttribute('Type'));

        // TOC
        $element = $doc->getElement('/w:document/w:body/w:p[3]/w:pPr/w:tabs/w:tab');
        self::assertEquals('right', $element->getAttribute('w:val'));
        self::assertEquals('dot', $element->getAttribute('w:leader'));
        self::assertEquals(9062, $element->getAttribute('w:pos'));
    }

    /**
     * Test write text element.
     */
    public function testWriteText(): void
    {
        $rStyle = 'rStyle';
        $pStyle = 'pStyle';

        $phpWord = new PhpWord();
        $phpWord->addFontStyle($rStyle, ['bold' => true]);
        $phpWord->addParagraphStyle($pStyle, ['hanging' => 120, 'indent' => 120]);
        $section = $phpWord->addSection();
        $section->addText('Test', $rStyle, $pStyle);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p/w:r/w:rPr/w:rStyle';
        self::assertEquals($rStyle, $doc->getElementAttribute($element, 'w:val'));
        $element = '/w:document/w:body/w:p/w:pPr/w:pStyle';
        self::assertEquals($pStyle, $doc->getElementAttribute($element, 'w:val'));
    }

    /**
     * Test write textrun element.
     */
    public function testWriteTextRun(): void
    {
        $pStyle = 'pStyle';
        $aStyle = ['alignment' => Jc::BOTH, 'spaceBefore' => 120, 'spaceAfter' => 120];
        $imageSrc = __DIR__ . '/../../../_files/images/earth.jpg';

        $phpWord = new PhpWord();
        $phpWord->addParagraphStyle($pStyle, $aStyle);
        $section = $phpWord->addSection('Test');
        $textrun = $section->addTextRun($pStyle);
        $textrun->addText('Test');
        $textrun->addTextBreak();
        $textrun = $section->addTextRun($aStyle);
        $textrun->addLink('https://github.com/PHPOffice/PHPWord');
        $textrun->addImage($imageSrc, ['alignment' => Jc::CENTER]);
        $textrun->addFootnote();
        $doc = TestHelperDOCX::getDocument($phpWord);

        $parent = '/w:document/w:body/w:p';
        self::assertTrue($doc->elementExists("{$parent}/w:pPr/w:pStyle[@w:val='{$pStyle}']"));
    }

    /**
     * Test write link element.
     */
    public function testWriteLink(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $fontStyleArray = ['bold' => true];
        $fontStyleName = 'Font Style';
        $paragraphStyleArray = ['alignment' => Jc::CENTER];
        $paragraphStyleName = 'Paragraph Style';

        $expected = 'PHPWord on GitHub';
        $section->addLink('https://github.com/PHPOffice/PHPWord', $expected);
        $section->addLink('https://github.com/PHPOffice/PHPWord', 'Test', $fontStyleArray, $paragraphStyleArray);
        $section->addLink('https://github.com/PHPOffice/PHPWord', 'Test', $fontStyleName, $paragraphStyleName);

        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:hyperlink/w:r/w:t');

        self::assertEquals($expected, $element->nodeValue);
    }

    /**
     * Test write preserve text element.
     */
    public function testWritePreserveText(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $footer = $section->addFooter();
        $fontStyleArray = ['bold' => true];
        $fontStyleName = 'Font';
        $paragraphStyleArray = ['alignment' => Jc::END];
        $paragraphStyleName = 'Paragraph';

        $footer->addPreserveText('Page {PAGE}');
        $footer->addPreserveText('{PAGE}', $fontStyleArray, $paragraphStyleArray);
        $footer->addPreserveText('{PAGE}', $fontStyleName, $paragraphStyleName);

        $doc = TestHelperDOCX::getDocument($phpWord);
        $preserve = $doc->getElement('w:p/w:r[2]/w:instrText', 'word/footer1.xml');

        self::assertEquals('PAGE', $preserve->nodeValue);
        self::assertEquals('preserve', $preserve->getAttribute('xml:space'));
    }

    /**
     * Test write text break.
     */
    public function testWriteTextBreak(): void
    {
        $fArray = ['size' => 12];
        $pArray = ['spacing' => 240];
        $fName = 'fStyle';
        $pName = 'pStyle';

        $phpWord = new PhpWord();
        $phpWord->addFontStyle($fName, $fArray);
        $phpWord->addParagraphStyle($pName, $pArray);
        $section = $phpWord->addSection();
        $section->addTextBreak();
        $section->addTextBreak(1, $fArray, $pArray);
        $section->addTextBreak(1, $fName, $pName);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:rPr/w:rStyle');
        self::assertEquals($fName, $element->getAttribute('w:val'));
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:pStyle');
        self::assertEquals($pName, $element->getAttribute('w:val'));
    }

    /**
     * covers ::_writeImage.
     */
    public function testWriteImage(): void
    {
        $phpWord = new PhpWord();
        $styles = ['alignment' => Jc::START, 'width' => 40, 'height' => 40, 'marginTop' => -1, 'marginLeft' => -1];
        $wraps = ['inline', 'behind', 'infront', 'square', 'tight'];
        $section = $phpWord->addSection();
        foreach ($wraps as $wrap) {
            $styles['wrappingStyle'] = $wrap;
            $section->addImage(__DIR__ . '/../../../_files/images/earth.jpg', $styles);
        }

        $archiveFile = realpath(__DIR__ . '/../../../_files/documents/reader.docx');
        $imageFile = 'word/media/image1.jpeg';
        $source = 'zip://' . $archiveFile . '#' . $imageFile;
        $section->addImage($source);

        $doc = TestHelperDOCX::getDocument($phpWord);

        // behind
        $element = $doc->getElement('/w:document/w:body/w:p[2]/w:r/w:pict/v:shape');
        $style = $element->getAttribute('style');

        // Try to address CI coverage issue for PHP 7.1 and 7.2 when using regex match assertions
        if (method_exists(static::class, 'assertRegExp')) {
            self::assertRegExp('/z\-index:\-[0-9]*/', $style);
        } else {
            self::assertMatchesRegularExpression('/z\-index:\-[0-9]*/', $style);
        }

        // square
        $element = $doc->getElement('/w:document/w:body/w:p[4]/w:r/w:pict/v:shape/w10:wrap');
        self::assertEquals('square', $element->getAttribute('type'));
    }

    /**
     * covers ::_writeWatermark.
     */
    public function testWriteWatermark(): void
    {
        $imageSrc = __DIR__ . '/../../../_files/images/earth.jpg';

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $header = $section->addHeader();
        $header->addWatermark($imageSrc);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = $doc->getElement('/w:document/w:body/w:sectPr/w:headerReference');
        self::assertStringStartsWith('rId', $element->getAttribute('r:id'));
    }

    /**
     * covers ::_writeTitle.
     */
    public function testWriteTitle(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addTitleStyle(1, ['bold' => true], ['spaceAfter' => 240]);
        $phpWord->addSection()->addTitle('Test', 1);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p/w:pPr/w:pStyle';
        self::assertEquals('Heading1', $doc->getElementAttribute($element, 'w:val'));
    }

    /**
     * covers ::_writeCheckbox.
     */
    public function testWriteCheckbox(): void
    {
        $rStyle = 'rStyle';
        $pStyle = 'pStyle';

        $phpWord = new PhpWord();
        // $phpWord->addFontStyle($rStyle, array('bold' => true));
        // $phpWord->addParagraphStyle($pStyle, array('hanging' => 120, 'indent' => 120));
        $section = $phpWord->addSection();
        $section->addCheckBox('Check1', 'Test', $rStyle, $pStyle);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $element = '/w:document/w:body/w:p/w:r/w:fldChar/w:ffData/w:name';
        self::assertEquals('Check1', $doc->getElementAttribute($element, 'w:val'));
    }

    /**
     * covers ::_writeParagraphStyle.
     */
    public function testWriteParagraphStyle(): void
    {
        // Create the doc
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $attributes = [
            'alignment' => Jc::END,
            'widowControl' => false,
            'keepNext' => true,
            'keepLines' => true,
            'pageBreakBefore' => true,
        ];
        foreach ($attributes as $attribute => $value) {
            $section->addText('Test', null, [$attribute => $value]);
        }
        $doc = TestHelperDOCX::getDocument($phpWord);

        // Test the attributes
        $attributeCount = 0;
        foreach ($attributes as $key => $value) {
            ++$attributeCount;
            $nodeName = ($key == 'alignment') ? 'jc' : $key;
            $path = "/w:document/w:body/w:p[{$attributeCount}]/w:pPr/w:{$nodeName}";
            if ('alignment' != $key) {
                $value = $value ? 1 : 0;
            }
            $element = $doc->getElement($path);
            self::assertEquals($value, $element->getAttribute('w:val'));
        }
    }

    /**
     * covers ::_writeTextStyle.
     */
    public function testWriteFontStyle(): void
    {
        $phpWord = new PhpWord();
        $styles['name'] = 'Verdana';
        $styles['size'] = 14;
        $styles['bold'] = true;
        $styles['italic'] = true;
        $styles['underline'] = 'dash';
        $styles['strikethrough'] = true;
        $styles['superScript'] = true;
        $styles['color'] = 'FF0000';
        $styles['fgColor'] = 'yellow';
        $styles['bgColor'] = 'FFFF00';
        $styles['hint'] = 'eastAsia';
        $styles['smallCaps'] = true;

        $section = $phpWord->addSection();
        $section->addText('Test', $styles);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $parent = '/w:document/w:body/w:p/w:r/w:rPr';
        self::assertEquals($styles['name'], $doc->getElementAttribute("{$parent}/w:rFonts", 'w:ascii'));
        self::assertEquals($styles['size'] * 2, $doc->getElementAttribute("{$parent}/w:sz", 'w:val'));
        self::assertTrue($doc->elementExists("{$parent}/w:b"));
        self::assertTrue($doc->elementExists("{$parent}/w:i"));
        self::assertEquals($styles['underline'], $doc->getElementAttribute("{$parent}/w:u", 'w:val'));
        self::assertTrue($doc->elementExists("{$parent}/w:strike"));
        self::assertEquals('superscript', $doc->getElementAttribute("{$parent}/w:vertAlign", 'w:val'));
        self::assertEquals($styles['color'], $doc->getElementAttribute("{$parent}/w:color", 'w:val'));
        self::assertEquals($styles['fgColor'], $doc->getElementAttribute("{$parent}/w:highlight", 'w:val'));
        self::assertTrue($doc->elementExists("{$parent}/w:smallCaps"));
    }

    /**
     * Tests that if no color is set on a cell a border gets writen with the default color.
     */
    public function testWriteDefaultColor(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $cStyles['borderTopSize'] = 120;

        $table = $section->addTable();
        $table->addRow();
        $cell = $table->addCell(null, $cStyles);
        $cell->addText('Test');

        $doc = TestHelperDOCX::getDocument($phpWord);
        self::assertEquals(
            Cell::DEFAULT_BORDER_COLOR,
            $doc->getElementAttribute(
                '/w:document/w:body/w:tbl/w:tr/w:tc/w:tcPr/w:tcBorders/w:top',
                'w:color'
            )
        );
    }

    /**
     * covers ::_writeTableStyle.
     */
    public function testWriteTableStyle(): void
    {
        $phpWord = new PhpWord();
        $rHeight = 120;
        $cWidth = 120;
        $imageSrc = __DIR__ . '/../../../_files/images/earth.jpg';
        $objectSrc = __DIR__ . '/../../../_files/documents/sheet.xls';

        $tStyles['width'] = 50;
        $tStyles['cellMarginTop'] = 120;
        $tStyles['cellMarginRight'] = 120;
        $tStyles['cellMarginBottom'] = 120;
        $tStyles['cellMarginLeft'] = 120;
        $rStyles['tblHeader'] = true;
        $rStyles['cantSplit'] = true;
        $cStyles['valign'] = 'top';
        $cStyles['textDirection'] = 'btLr';
        $cStyles['bgColor'] = 'FF0000';
        $cStyles['borderTopSize'] = 120;
        $cStyles['borderBottomSize'] = 120;
        $cStyles['borderLeftSize'] = 120;
        $cStyles['borderRightSize'] = 120;
        $cStyles['borderTopColor'] = 'FF0000';
        $cStyles['borderBottomColor'] = 'FF0000';
        $cStyles['borderLeftColor'] = 'FF0000';
        $cStyles['borderRightColor'] = 'FF0000';
        $cStyles['vMerge'] = 'restart';

        $section = $phpWord->addSection();
        $table = $section->addTable($tStyles);
        $table->setWidth(100);
        $table->addRow($rHeight, $rStyles);
        $cell = $table->addCell($cWidth, $cStyles);
        $cell->addText('Test');
        $cell->addTextBreak();
        $cell->addLink('https://github.com/PHPOffice/PHPWord');
        $cell->addListItem('Test');
        $cell->addImage($imageSrc);
        $cell->addObject($objectSrc);
        $textrun = $cell->addTextRun();
        $textrun->addText('Test');

        $doc = TestHelperDOCX::getDocument($phpWord);

        $parent = '/w:document/w:body/w:tbl/w:tblPr/w:tblCellMar';

        $parent = '/w:document/w:body/w:tbl/w:tr/w:trPr';
        self::assertEquals($rHeight, $doc->getElementAttribute("{$parent}/w:trHeight", 'w:val'));
        self::assertEquals($rStyles['tblHeader'], $doc->getElementAttribute("{$parent}/w:tblHeader", 'w:val'));
        self::assertEquals($rStyles['cantSplit'], $doc->getElementAttribute("{$parent}/w:cantSplit", 'w:val'));

        $parent = '/w:document/w:body/w:tbl/w:tr/w:tc/w:tcPr';
        self::assertEquals($cWidth, $doc->getElementAttribute("{$parent}/w:tcW", 'w:w'));
        self::assertEquals($cStyles['valign'], $doc->getElementAttribute("{$parent}/w:vAlign", 'w:val'));
        self::assertEquals($cStyles['textDirection'], $doc->getElementAttribute("{$parent}/w:textDirection", 'w:val'));
    }

    /**
     * covers ::_writeCellStyle.
     */
    public function testWriteCellStyleCellGridSpan(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

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

        $table->addRow();
        $cell = $table->addCell(200, ['borderRightColor' => 'FF0000']);
        $cell->getStyle()->setGridSpan(5);

        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:tbl/w:tr/w:tc/w:tcPr/w:gridSpan');

        self::assertEquals(5, $element->getAttribute('w:val'));
    }

    /**
     * Test write gutter and line numbering.
     */
    public function testWriteGutterAndLineNumbering(): void
    {
        $pageMarginPath = '/w:document/w:body/w:sectPr/w:pgMar';
        $lineNumberingPath = '/w:document/w:body/w:sectPr/w:lnNumType';

        $phpWord = new PhpWord();
        $phpWord->addSection(['gutter' => 240, 'lineNumbering' => []]);
        $doc = TestHelperDOCX::getDocument($phpWord);

        self::assertEquals(240, $doc->getElement($pageMarginPath)->getAttribute('w:gutter'));
        self::assertTrue($doc->elementExists($lineNumberingPath));
    }
}
