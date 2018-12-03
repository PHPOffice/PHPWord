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
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Part\Styles
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Part\Styles
 * @runTestsInSeparateProcesses
 */
class StylesTest extends \PHPUnit\Framework\TestCase
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

    public function testFontStyleBasedOn()
    {
        $phpWord = new PhpWord();

        $baseParagraphStyle = new Paragraph();
        $baseParagraphStyle->setAlignment(Jc::CENTER);
        $baseParagraphStyle = $phpWord->addParagraphStyle('BaseStyle', $baseParagraphStyle);

        $childFont = new Font();
        $childFont->setParagraph($baseParagraphStyle);
        $childFont->setSize(16);
        $childFont = $phpWord->addFontStyle('ChildFontStyle', $childFont);

        $otherFont = new Font();
        $otherFont->setSize(20);
        $otherFont = $phpWord->addFontStyle('OtherFontStyle', $otherFont);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/styles.xml';

        // Normal style generated?
        $path = '/w:styles/w:style[@w:styleId="BaseStyle"]/w:name';
        $element = $doc->getElement($path, $file);
        $this->assertEquals('BaseStyle', $element->getAttribute('w:val'));

        // Font style with paragraph should have it's base style set to that paragraphs style name
        $path = '/w:styles/w:style[w:name/@w:val="ChildFontStyle"]/w:basedOn';
        $element = $doc->getElement($path, $file);
        $this->assertEquals('BaseStyle', $element->getAttribute('w:val'));

        // Font style without paragraph should not have a base style set
        $path = '/w:styles/w:style[w:name/@w:val="OtherFontStyle"]/w:basedOn';
        $element = $doc->getElement($path, $file);
        $this->assertNull($element);
    }

    public function testFontStyleBasedOnOtherFontStyle()
    {
        $phpWord = new PhpWord();

        $styleGenerationP = new Paragraph();
        $styleGenerationP->setAlignment(Jc::BOTH);

        $styleGeneration = new Font();
        $styleGeneration->setParagraph($styleGenerationP);
        $styleGeneration->setSize(9.5);
        $phpWord->addFontStyle('Generation', $styleGeneration);

        $styleGenerationEteinteP = new Paragraph();
        $styleGenerationEteinteP->setBasedOn('Generation');

        $styleGenerationEteinte = new Font();
        $styleGenerationEteinte->setParagraph($styleGenerationEteinteP);
        $styleGenerationEteinte->setSize(8.5);
        $phpWord->addFontStyle('GeneratEteinte', $styleGenerationEteinte);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/styles.xml';

        $path = '/w:styles/w:style[@w:styleId="GeneratEteinte"]/w:basedOn';
        $element = $doc->getElement($path, $file);
        $this->assertEquals('Generation', $element->getAttribute('w:val'));
    }
}
