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
}
