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

namespace PhpOffice\PhpWordTests\Writer\ODText\Style;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWordTests\TestHelperDOCX;

class NumberingTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed after each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testAddListItemRun(): void
    {
        $expected = 'MyOwnNumberingStyle';

        $phpWord = new PhpWord();
        $phpWord->addNumberingStyle($expected, [
            'type' => 'multilevel',
            'levels' => [
                [
                    'start' => 1,
                    'format' => 'decimal',
                    'restart' => 1,
                    'suffix' => 'space',
                    'text' => '%1.',
                    'alignment' => Jc::START,
                ],
            ],
        ]);
        $phpWord->addSection()
            ->addListItemRun(0, $expected)
            ->addText('List item run 1');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $doc->setDefaultFile('styles.xml');

        $xPath = '/office:document-styles/office:styles';
        self::assertTrue($doc->elementExists($xPath));
        self::assertTrue($doc->elementExists($xPath . '/text:list-style'));
        self::assertTrue($doc->hasElementAttribute($xPath . '/text:list-style', 'style:name'));
        self::assertEquals($expected, $doc->getElementAttribute($xPath . '/text:list-style', 'style:name'));
        self::assertTrue($doc->elementExists($xPath . '/text:list-style/text:list-level-style-bullet'));
        self::assertTrue($doc->elementExists($xPath . '/text:list-style/text:list-level-style-bullet/style:list-level-properties'));
        self::assertTrue($doc->elementExists($xPath . '/text:list-style/text:list-level-style-bullet/style:list-level-properties/style:list-level-label-alignment'));
        self::assertTrue($doc->elementExists($xPath . '/text:list-style/text:list-level-style-bullet/style:text-properties'));
    }
}
