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

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Style\Font
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Style\Font
 * @runTestsInSeparateProcesses
 */
class FontTest extends \PHPUnit\Framework\TestCase
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
        $textrun->addText('سلام این یک پاراگراف راست به چپ است', array('rtl' => true, 'lang' => 'ar-DZ'));
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $file = 'word/document.xml';
        $path = '/w:document/w:body/w:p/w:r/w:rPr/w:rtl';
        $this->assertTrue($doc->elementExists($path, $file));
    }

    public function testFontRTLNamed()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $stnam = 'fstyle';
        $phpWord->addFontStyle($stnam, array(
            'rtl'  => true,
            'name' => 'Courier New',
            'size' => 8,
            ));
        $section = $phpWord->addSection();
        $txt = 'היום יום שני';  // Translation = Today is Monday
        $section->addText($txt, $stnam);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $element = '/w:document/w:body/w:p/w:r';
        $txtelem = $element . '/w:t';
        $styelem = $element . '/w:rPr';
        $this->assertTrue($doc->elementExists($txtelem));
        $this->assertEquals($txt, $doc->getElement($txtelem)->textContent);
        $this->assertTrue($doc->elementExists($styelem));
        $this->assertTrue($doc->elementExists($styelem . '/w:rStyle'));
        $this->assertEquals($stnam, $doc->getElementAttribute($styelem . '/w:rStyle', 'w:val'));
        $this->assertTrue($doc->elementExists($styelem . '/w:rtl'));
    }

    public function testFontNotRTLNamed()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $stnam = 'fstyle';
        $phpWord->addFontStyle($stnam, array(
            //'rtl'  => true,
            'name' => 'Courier New',
            'size' => 8,
            ));
        $section = $phpWord->addSection();
        $txt = 'היום יום שני';  // Translation = Today is Monday
        $section->addText($txt, $stnam);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $element = '/w:document/w:body/w:p/w:r';
        $txtelem = $element . '/w:t';
        $styelem = $element . '/w:rPr';
        $this->assertTrue($doc->elementExists($txtelem));
        $this->assertEquals($txt, $doc->getElement($txtelem)->textContent);
        $this->assertTrue($doc->elementExists($styelem));
        $this->assertTrue($doc->elementExists($styelem . '/w:rStyle'));
        $this->assertEquals($stnam, $doc->getElementAttribute($styelem . '/w:rStyle', 'w:val'));
        $this->assertFalse($doc->elementExists($styelem . '/w:rtl'));
    }

    public function testNoProof()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $fontStyle = array(
            'noProof' => true,
            'name'    => 'Courier New',
            'size'    => 8,
            );
        $section = $phpWord->addSection();
        $txt = 'spellung error';
        $section->addText($txt, $fontStyle);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $element = '/w:document/w:body/w:p/w:r';
        $txtelem = $element . '/w:t';
        $styelem = $element . '/w:rPr';
        $noproofelem = $styelem . '/w:noProof';
        $this->assertTrue($doc->elementExists($txtelem));
        $this->assertEquals($txt, $doc->getElement($txtelem)->textContent);
        $this->assertTrue($doc->elementExists($styelem));
        $this->assertTrue($doc->elementExists($noproofelem));
        $this->assertEquals('1', $doc->getElementAttribute($noproofelem, 'w:val'));
    }

    /**
     * Test writing font with language
     */
    public function testFontWithLang()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Ce texte-ci est en français.', array('lang' => \PhpOffice\PhpWord\Style\Language::FR_BE));
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $file = 'word/document.xml';
        $path = '/w:document/w:body/w:p/w:r/w:rPr/w:lang';
        $this->assertTrue($doc->elementExists($path, $file));
    }

    /**
     * Test writing position
     */
    public function testPosition()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText('This text is lowered', array('position' => -20));
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:p/w:r/w:rPr/w:position';
        $this->assertTrue($doc->elementExists($path));
        $this->assertEquals(-20, $doc->getElementAttribute($path, 'w:val'));
    }
}
