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

namespace PhpOffice\PhpWordTests\Writer\Word2007\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWordTests\TestHelperDOCX;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Field.
 */
class FieldTest extends TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test Field write.
     */
    public function testWriteWithRefType(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addField(
            'REF',
            [
                'name' => 'my-bookmark',
            ],
            [
                'InsertParagraphNumberRelativeContext',
                'CreateHyperLink',
            ]
        );

        $section->addListItem('line one item');
        $section->addListItem('line two item');
        $section->addBookmark('my-bookmark');
        $section->addListItem('line three item');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $refFieldPath = '/w:document/w:body/w:p[1]/w:r[2]/w:instrText';
        self::assertTrue($doc->elementExists($refFieldPath));

        $bookMarkElement = $doc->getElement($refFieldPath);
        self::assertNotNull($bookMarkElement);
        self::assertEquals(' REF my-bookmark \r \h ', $bookMarkElement->textContent);

        $bookmarkPath = '/w:document/w:body/w:bookmarkStart';
        self::assertTrue($doc->elementExists($bookmarkPath));
        self::assertEquals('my-bookmark', $doc->getElementAttribute("$bookmarkPath", 'w:name'));
    }
}
