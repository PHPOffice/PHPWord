<?php

namespace PhpOffice\PhpWordTests\Writer\ODText\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWordTests\TestHelperDOCX;

class TableTest extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testWritesRowHeightsAndExactHeightAsAutomaticStyles(): void
    {
        $phpWord = new PhpWord();
        $table = $phpWord->addSection()->addTable();

        $table->addRow(720)->addCell()->addText('minimum');
        $table->addRow(1440, ['exactHeight' => true])->addCell()->addText('exact');
        $table->addRow(2880)->addCell()->addText('minimum');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $rows = [
            [
                'height' => '0.5in',
                'exact' => null,
            ],
            [
                'height' => '1in',
                'exact' => 'false',
            ],
            [
                'height' => '2in',
                'exact' => null,
            ],
        ];

        foreach ($rows as $index => $expected) {
            $row = '/office:document-content/office:body/office:text/text:section/table:table/table:table-row[' . ($index + 1) . ']';
            self::assertTrue($doc->hasElementAttribute($row, 'table:style-name'));

            $styleName = $doc->getElementAttribute($row, 'table:style-name');
            $style = "/office:document-content/office:automatic-styles/style:style[@style:name='{$styleName}']";
            self::assertTrue($doc->elementExists($style));
            self::assertEquals('table-row', $doc->getElementAttribute($style, 'style:family'));

            $properties = $style . '/style:table-row-properties';
            self::assertTrue($doc->elementExists($properties));
            if ($expected['exact'] === null) {
                self::assertEquals($expected['height'], $doc->getElementAttribute($properties, 'style:min-row-height'));
                self::assertFalse($doc->hasElementAttribute($properties, 'style:row-height'));
            } else {
                self::assertEquals($expected['height'], $doc->getElementAttribute($properties, 'style:row-height'));
                self::assertEquals($expected['exact'], $doc->getElementAttribute($properties, 'style:use-optimal-row-height'));
            }
        }
    }

    public function testWritesIndependentCellBorderStylesAsAutomaticStyles(): void
    {
        $phpWord = new PhpWord();
        $table = $phpWord->addSection()->addTable();
        $row = $table->addRow();

        $styles = [
            ['borderTopSize' => 10, 'borderTopColor' => '112233', 'borderTopStyle' => 'dashed'],
            ['borderBottomSize' => 20, 'borderBottomColor' => '223344'],
            ['borderLeftSize' => 30, 'borderLeftColor' => '334455'],
            ['borderRightSize' => 40, 'borderRightColor' => '445566'],
            [
                'borderTopSize' => 10,
                'borderTopColor' => '112233',
                'borderBottomSize' => 20,
                'borderBottomColor' => '223344',
            ],
            [
                'borderLeftSize' => 30,
                'borderLeftColor' => '334455',
                'borderRightSize' => 40,
                'borderRightColor' => '445566',
            ],
            [
                'borderTopSize' => 10,
                'borderTopColor' => '112233',
                'borderBottomSize' => 20,
                'borderBottomColor' => '223344',
                'borderLeftSize' => 30,
                'borderLeftColor' => '334455',
                'borderRightSize' => 40,
                'borderRightColor' => '445566',
            ],
            [
                'bgColor' => 'abcdef',
                'paddingTop' => 144,
                'paddingBottom' => 288,
                'paddingLeft' => 432,
                'paddingRight' => 576,
                'valign' => 'center',
                'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_TBRL,
                'noWrap' => false,
            ],
            null,
        ];

        foreach ($styles as $index => $style) {
            $row->addCell(null, $style)->addText((string) ($index + 1));
        }

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $base = '/office:document-content/office:body/office:text/text:section/table:table/table:table-row/table:table-cell';

        foreach ($styles as $index => $style) {
            $cell = $base . '[' . ($index + 1) . ']';
            if ($style === null) {
                self::assertFalse($doc->hasElementAttribute($cell, 'table:style-name'));

                continue;
            }

            self::assertTrue($doc->hasElementAttribute($cell, 'table:style-name'));
            $styleName = $doc->getElementAttribute($cell, 'table:style-name');
            $automaticStyle = "/office:document-content/office:automatic-styles/style:style[@style:name='{$styleName}']";
            self::assertEquals('table-cell', $doc->getElementAttribute($automaticStyle, 'style:family'));
            $properties = $automaticStyle . '/style:table-cell-properties';

            foreach (['Top', 'Bottom', 'Left', 'Right'] as $side) {
                $size = $style['border' . $side . 'Size'] ?? null;
                $color = $style['border' . $side . 'Color'] ?? null;
                $attribute = 'fo:border-' . strtolower($side);
                if ($size === null) {
                    self::assertFalse($doc->hasElementAttribute($properties, $attribute));
                } else {
                    self::assertEquals(
                        number_format($size / 20, 3, '.', '') . 'pt ' . ($style['border' . $side . 'Style'] ?? 'solid') . ' #' . $color,
                        $doc->getElementAttribute($properties, $attribute)
                    );
                }
            }

            if ($index === 7) {
                self::assertEquals('#abcdef', $doc->getElementAttribute($properties, 'fo:background-color'));
                self::assertEquals('0.100in', $doc->getElementAttribute($properties, 'fo:padding-top'));
                self::assertEquals('0.200in', $doc->getElementAttribute($properties, 'fo:padding-bottom'));
                self::assertEquals('0.300in', $doc->getElementAttribute($properties, 'fo:padding-left'));
                self::assertEquals('0.400in', $doc->getElementAttribute($properties, 'fo:padding-right'));
                self::assertEquals('middle', $doc->getElementAttribute($properties, 'style:vertical-align'));
                self::assertEquals('tb-rl', $doc->getElementAttribute($properties, 'style:writing-mode'));
                self::assertEquals('wrap', $doc->getElementAttribute($properties, 'fo:wrap-option'));
            }
        }
    }

    public function testWritesTableBackgroundAlignmentAndWidth(): void
    {
        $phpWord = new PhpWord();
        $table = $phpWord->addSection()->addTable([
            'bgColor' => 'abcdef',
            'alignment' => 'right',
            'width' => 2880,
        ]);
        $table->addRow()->addCell()->addText('styled');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $tablePath = '/office:document-content/office:automatic-styles/style:style[@style:family="table"]/style:table-properties';

        self::assertEquals('#abcdef', $doc->getElementAttribute($tablePath, 'fo:background-color'));
        self::assertEquals('right', $doc->getElementAttribute($tablePath, 'table:align'));
        self::assertEquals('2.000in', $doc->getElementAttribute($tablePath, 'style:width'));
    }

    public function testMapsTableBordersAndCellMarginsToCellStyles(): void
    {
        $phpWord = new PhpWord();
        $table = $phpWord->addSection()->addTable([
            'borderSize' => 10,
            'borderColor' => '123456',
            'cellMargin' => 144,
        ]);
        $table->addRow()->addCell()->addText('one');
        $table->getRows()[0]->addCell()->addText('two');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $base = '/office:document-content/office:body/office:text/text:section/table:table/table:table-row/table:table-cell';
        foreach ([1, 2] as $index) {
            $cell = $base . '[' . $index . ']';
            $styleName = $doc->getElementAttribute($cell, 'table:style-name');
            $properties = "/office:document-content/office:automatic-styles/style:style[@style:name='{$styleName}']/style:table-cell-properties";
            self::assertEquals('0.500pt solid #123456', $doc->getElementAttribute($properties, 'fo:border-top'));
            self::assertEquals('0.100in', $doc->getElementAttribute($properties, 'fo:padding-top'));
        }
    }
}
