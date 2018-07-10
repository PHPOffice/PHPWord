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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Part\Comment
 *
 * @runTestsInSeparateProcesses
 */
class CommentsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Write comments
     */
    public function testWriteComments()
    {
        $comment = new \PhpOffice\PhpWord\Element\Comment('Authors name', new \DateTime(), 'my_initials');
        $comment->addText('Test');

        $phpWord = new PhpWord();
        $phpWord->addComment($comment);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $path = '/w:comments/w:comment';
        $file = 'word/comments.xml';

        $this->assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        $this->assertNotNull($element->getAttribute('w:id'));
        $this->assertEquals('Authors name', $element->getAttribute('w:author'));
        $this->assertEquals('my_initials', $element->getAttribute('w:initials'));
    }
}
