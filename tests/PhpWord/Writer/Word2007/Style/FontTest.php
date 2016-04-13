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
 * @copyright   2010-2015 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */
namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Style\Font
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Style\Font
 * @runTestsInSeparateProcesses
 */
class FontTest extends \PHPUnit_Framework_TestCase
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
    public function testFontRTL()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $textrun = $section->addTextRun();
        $textrun->addText(htmlspecialchars('سلام این یک پاراگراف راست به چپ است', ENT_COMPAT, 'UTF-8'), array('rtl' => true));
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $file = 'word/document.xml';
        $path = '/w:document/w:body/w:p/w:r/w:rPr/w:rtl';
        $this->assertTrue($doc->elementExists($path, $file));
    }
}
