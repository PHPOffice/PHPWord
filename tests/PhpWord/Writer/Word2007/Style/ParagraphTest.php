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

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Style\Paragraph
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Style\Paragraph
 * @runTestsInSeparateProcesses
 */
class ParagraphTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test write styles
     */
    public function testParagraphNumbering()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->addParagraphStyle('testStyle', array('indent' => '10'));
        $section = $phpWord->addSection();
        $section->addText('test', null, array('numStyle' => 'testStyle', 'numLevel' => '1'));
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:p/w:pPr/w:numPr/w:ilvl';
        $this->assertTrue($doc->elementExists($path));
    }
}
