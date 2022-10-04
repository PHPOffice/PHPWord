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

namespace PhpOffice\PhpWordTests\Writer\Word2007\Part;

use DateTime;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Part\Comment.
 *
 * @runTestsInSeparateProcesses
 */
class CommentsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Write comments.
     */
    public function testWriteComments(): void
    {
        $comment = new \PhpOffice\PhpWord\Element\Comment('Authors name', new DateTime(), 'my_initials');
        $comment->addText('Test');

        $phpWord = new PhpWord();
        $phpWord->addComment($comment);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $path = '/w:comments/w:comment';
        $file = 'word/comments.xml';

        self::assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        self::assertNotNull($element->getAttribute('w:id'));
        self::assertEquals('Authors name', $element->getAttribute('w:author'));
        self::assertEquals('my_initials', $element->getAttribute('w:initials'));
    }
}
