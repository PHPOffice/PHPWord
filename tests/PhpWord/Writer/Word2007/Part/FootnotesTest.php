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
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * @coversNothing
 * @runTestsInSeparateProcesses
 */
class FootnotesTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    public function testWriteFootnotes()
    {
        $phpWord = new PhpWord();
        $phpWord->addParagraphStyle('pStyle', array('alignment' => Jc::START));
        $section = $phpWord->addSection();
        $section->addText('Text');
        $footnote1 = $section->addFootnote('pStyle');
        $footnote1->addText('Footnote');
        $footnote1->addTextBreak();
        $footnote1->addLink('https://github.com/PHPOffice/PHPWord');
        $footnote2 = $section->addEndnote(array('alignment' => Jc::START));
        $footnote2->addText('Endnote');
        $doc = TestHelperDOCX::getDocument($phpWord);

        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:footnoteReference'));
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:endnoteReference'));
    }
}
