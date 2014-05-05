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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */
namespace PhpOffice\PhpWord\Tests\Writer\Word2007\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Part\Notes
 *
 * @runTestsInSeparateProcesses
 */
class FootnotesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Write footnotes
     */
    public function testWriteFootnotes()
    {
        $phpWord = new PhpWord();
        $phpWord->addParagraphStyle('pStyle', array('align' => 'left'));
        $section = $phpWord->addSection();
        $section->addText('Text');
        $footnote1 = $section->addFootnote('pStyle');
        $footnote1->addText('Footnote');
        $footnote1->addTextBreak();
        $footnote1->addLink('http://google.com');
        $footnote2 = $section->addEndnote(array('align' => 'left'));
        $footnote2->addText('Endnote');
        $doc = TestHelperDOCX::getDocument($phpWord);

        $this->assertTrue($doc->elementExists("/w:document/w:body/w:p/w:r/w:footnoteReference"));
        $this->assertTrue($doc->elementExists("/w:document/w:body/w:p/w:r/w:endnoteReference"));
    }
}
