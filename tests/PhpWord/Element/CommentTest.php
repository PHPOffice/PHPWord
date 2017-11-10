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
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

/**
 * Test class for PhpOffice\PhpWord\Element\Header
 *
 * @runTestsInSeparateProcesses
 */
class CommentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * New instance
     */
    public function testConstructDefault()
    {
        $author = 'Test User';
        $date = new \DateTime('2000-01-01');
        $initials = 'default_user';
        $oComment = new Comment($author, $date, $initials);

        $oText = new Text('dummy text');
        $oComment->setStartElement($oText);
        $oComment->setEndElement($oText);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Comment', $oComment);
        $this->assertEquals($author, $oComment->getAuthor());
        $this->assertEquals($date, $oComment->getDate());
        $this->assertEquals($initials, $oComment->getInitials());
        $this->assertEquals($oText, $oComment->getStartElement());
        $this->assertEquals($oText, $oComment->getEndElement());
    }

    /**
     * Add text
     */
    public function testAddText()
    {
        $oComment = new Comment('Test User', new \DateTime(), 'my_initials');
        $element = $oComment->addText('text');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $element);
        $this->assertCount(1, $oComment->getElements());
        $this->assertEquals('text', $element->getText());
    }

    /**
     * Get elements
     */
    public function testGetElements()
    {
        $oComment = new Comment('Test User', new \DateTime(), 'my_initials');

        $this->assertInternalType('array', $oComment->getElements());
    }

    /**
     * Set/get relation Id
     */
    public function testRelationId()
    {
        $oComment = new Comment('Test User', new \DateTime(), 'my_initials');

        $iVal = rand(1, 1000);
        $oComment->setRelationId($iVal);
        $this->assertEquals($iVal, $oComment->getRelationId());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionOnCommentStartOnComment()
    {
        $dummyComment = new Comment('Test User', new \DateTime(), 'my_initials');
        $oComment = new Comment('Test User', new \DateTime(), 'my_initials');
        $oComment->setCommentRangeStart($dummyComment);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionOnCommentEndOnComment()
    {
        $dummyComment = new Comment('Test User', new \DateTime(), 'my_initials');
        $oComment = new Comment('Test User', new \DateTime(), 'my_initials');
        $oComment->setCommentRangeEnd($dummyComment);
    }
}
