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
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */
namespace PhpOffice\PhpWord\Writer\Word2007\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Part\Styles
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Part\Styles
 * @runTestsInSeparateProcesses
 */
class StylesTest extends \PHPUnit_Framework_TestCase
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
    public function testWriteStyles()
    {
        $phpWord = new PhpWord();

        $pStyle = array('alignment' => Jc::BOTH);
        $pBase = array('basedOn' => 'Normal');
        $pNew = array('basedOn' => 'Base Style', 'next' => 'Normal');
        $rStyle = array('size' => 20);
        $tStyle = array('bgColor' => 'FF0000', 'cellMargin' => 120, 'borderSize' => 120);
        $firstRowStyle = array('bgColor' => '0000FF', 'borderSize' => 120, 'borderColor' => '00FF00');
        $phpWord->setDefaultParagraphStyle($pStyle);
        $phpWord->addParagraphStyle('Base Style', $pBase);
        $phpWord->addParagraphStyle('New Style', $pNew);
        $phpWord->addFontStyle('New Style', $rStyle, $pStyle);
        $phpWord->addTableStyle('Table Style', $tStyle, $firstRowStyle);
        $phpWord->addTitleStyle(1, $rStyle, $pStyle);
        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/styles.xml';

        // Normal style generated?
        $path = '/w:styles/w:style[@w:styleId="Normal"]/w:name';
        $element = $doc->getElement($path, $file);
        $this->assertEquals('Normal', $element->getAttribute('w:val'));

        // Parent style referenced?
        $path = '/w:styles/w:style[@w:styleId="New Style"]/w:basedOn';
        $element = $doc->getElement($path, $file);
        $this->assertEquals('Base Style', $element->getAttribute('w:val'));

        // Next paragraph style correct?
        $path = '/w:styles/w:style[@w:styleId="New Style"]/w:next';
        $element = $doc->getElement($path, $file);
        $this->assertEquals('Normal', $element->getAttribute('w:val'));
    }
}
