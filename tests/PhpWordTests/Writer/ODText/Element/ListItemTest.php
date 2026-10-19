<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\Writer\ODText\Element;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style\ListItem;
use PhpOffice\PhpWordTests\TestHelperDOCX;
use PHPUnit\Framework\TestCase;

class ListItemTest extends TestCase
{
    /**
     * Executed after each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testAddListItem(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addListItem('First');
        $section->addListItem('Second', 1, ['bold' => true], ['listType' => ListItem::TYPE_NUMBER], ['alignment' => 'center']);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $xPath = '/office:document-content/office:body/office:text/text:section';
        self::assertEquals('PHPWordListType3', $doc->getElementAttribute($xPath . '/text:list[1]', 'text:style-name'));
        self::assertEquals('First', $doc->getElement($xPath . '/text:list[1]/text:list-item/text:p')->nodeValue);

        $xPath .= '/text:list[2]';
        $listStyle = $doc->getElementAttribute($xPath, 'text:style-name');
        self::assertStringStartsWith('PHPWordListType7', $listStyle);
        $xPath .= '/text:list-item/text:list/text:list-item/text:p';
        self::assertEquals('Second', $doc->getElement($xPath)->nodeValue);
        $paragraphStyle = $doc->getElementAttribute($xPath, 'text:style-name');
        $fontStyle = $doc->getElementAttribute($xPath . '/text:span', 'text:style-name');

        $xPath = '/office:document-content/office:automatic-styles/style:style';
        self::assertEquals('center', $doc->getElementAttribute($xPath . '[@style:name="' . $paragraphStyle . '"]/style:paragraph-properties', 'fo:text-align'));
        self::assertEquals('bold', $doc->getElementAttribute($xPath . '[@style:name="' . $fontStyle . '"]/style:text-properties', 'fo:font-weight'));

        $doc->setDefaultFile('styles.xml');
        self::assertTrue($doc->elementExists('/office:document-styles/office:styles/text:list-style[@style:name="' . $listStyle . '"]'));
    }

    public function testListItemsSurviveAnOdtRoundTrip(): void
    {
        // The ODText Reader reads a list as ListItem elements (#2159)
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addListItem('Apples');
        $section->addListItem('Pears');
        $filename = (string) tempnam(Settings::getTempDir(), 'PhpWord');
        IOFactory::createWriter($phpWord, 'ODText')->save($filename);
        $phpWord2 = IOFactory::load($filename, 'ODText');
        unlink($filename);

        $doc = TestHelperDOCX::getDocument($phpWord2, 'ODText');

        $xPath = '/office:document-content/office:body/office:text/text:section/text:list';
        self::assertEquals('Apples', $doc->getElement($xPath . '[1]/text:list-item/text:p')->nodeValue);
        self::assertEquals('Pears', $doc->getElement($xPath . '[2]/text:list-item/text:p')->nodeValue);
    }
}
