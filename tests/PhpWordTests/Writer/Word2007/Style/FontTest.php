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

namespace PhpOffice\PhpWordTests\Writer\Word2007\Style;

use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Style\Font.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Style\Font
 *
 * @runTestsInSeparateProcesses
 */
class FontTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test write styles.
     */
    public function testFontRTL(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $textrun = $section->addTextRun();
        $textrun->addText('سلام این یک پاراگراف راست به چپ است', ['rtl' => true, 'lang' => 'ar-DZ']);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $file = 'word/document.xml';
        $path = '/w:document/w:body/w:p/w:r/w:rPr/w:rtl';
        self::assertTrue($doc->elementExists($path, $file));
    }

    public function testFontRTLNamed(): void
    {
        $phpWord = new PhpWord();
        $stnam = 'fstyle';
        $phpWord->addFontStyle($stnam, [
            'rtl' => true,
            'name' => 'Courier New',
            'size' => 8,
        ]);
        $section = $phpWord->addSection();
        $txt = 'היום יום שני';  // Translation = Today is Monday
        $section->addText($txt, $stnam);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $element = '/w:document/w:body/w:p/w:r';
        $txtelem = $element . '/w:t';
        $styelem = $element . '/w:rPr';
        self::assertTrue($doc->elementExists($txtelem));
        self::assertEquals($txt, $doc->getElement($txtelem)->textContent);
        self::assertTrue($doc->elementExists($styelem));
        self::assertTrue($doc->elementExists($styelem . '/w:rStyle'));
        self::assertEquals($stnam, $doc->getElementAttribute($styelem . '/w:rStyle', 'w:val'));
        self::assertTrue($doc->elementExists($styelem . '/w:rtl'));
    }

    public function testFontNotRTLNamed(): void
    {
        $phpWord = new PhpWord();
        $stnam = 'fstyle';
        $phpWord->addFontStyle($stnam, [
            //'rtl'  => true,
            'name' => 'Courier New',
            'size' => 8,
        ]);
        $section = $phpWord->addSection();
        $txt = 'היום יום שני';  // Translation = Today is Monday
        $section->addText($txt, $stnam);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $element = '/w:document/w:body/w:p/w:r';
        $txtelem = $element . '/w:t';
        $styelem = $element . '/w:rPr';
        self::assertTrue($doc->elementExists($txtelem));
        self::assertEquals($txt, $doc->getElement($txtelem)->textContent);
        self::assertTrue($doc->elementExists($styelem));
        self::assertTrue($doc->elementExists($styelem . '/w:rStyle'));
        self::assertEquals($stnam, $doc->getElementAttribute($styelem . '/w:rStyle', 'w:val'));
        self::assertFalse($doc->elementExists($styelem . '/w:rtl'));
    }

    public function testNoProof(): void
    {
        $phpWord = new PhpWord();
        $fontStyle = [
            'noProof' => true,
            'name' => 'Courier New',
            'size' => 8,
        ];
        $section = $phpWord->addSection();
        $txt = 'spellung error';
        $section->addText($txt, $fontStyle);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $element = '/w:document/w:body/w:p/w:r';
        $txtelem = $element . '/w:t';
        $styelem = $element . '/w:rPr';
        $noproofelem = $styelem . '/w:noProof';
        self::assertTrue($doc->elementExists($txtelem));
        self::assertEquals($txt, $doc->getElement($txtelem)->textContent);
        self::assertTrue($doc->elementExists($styelem));
        self::assertTrue($doc->elementExists($noproofelem));
        self::assertEquals('1', $doc->getElementAttribute($noproofelem, 'w:val'));
    }

    /**
     * Test writing font with language.
     */
    public function testFontWithLang(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Ce texte-ci est en français.', ['lang' => \PhpOffice\PhpWord\Style\Language::FR_BE]);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $file = 'word/document.xml';
        $path = '/w:document/w:body/w:p/w:r/w:rPr/w:lang';
        self::assertTrue($doc->elementExists($path, $file));
    }

    /**
     * Test writing position.
     */
    public function testPosition(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('This text is lowered', ['position' => -20]);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:p/w:r/w:rPr/w:position';
        self::assertTrue($doc->elementExists($path));
        self::assertEquals(-20, $doc->getElementAttribute($path, 'w:val'));
    }

    public static function testRgb(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection(['pageNumberingStart' => 1]);
        $html = implode(
            "\n",
            [
                '<table>',
                '<tbody>',
                '<tr>',
                '<td style="color: #A7D9C1;">This one is in color.</td>',
                '<td style="color: rgb(167, 217, 193);">This one too.</td>',
                '</tr>',
                '</tbody>',
                '</table>',
            ]
        );

        Html::addHtml($section, $html, false, false);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $element = '/w:document/w:body/w:tbl/w:tr/w:tc/w:p/w:r';
        $txtelem = $element . '/w:t';
        $styelem = $element . '/w:rPr';
        self::assertTrue($doc->elementExists($txtelem));
        self::assertSame('This one is in color.', $doc->getElement($txtelem)->textContent);
        self::assertTrue($doc->elementExists($styelem));
        self::assertTrue($doc->elementExists($styelem . '/w:color'));
        self::assertSame('A7D9C1', $doc->getElementAttribute($styelem . '/w:color', 'w:val'));

        $element = '/w:document/w:body/w:tbl/w:tr/w:tc[2]/w:p/w:r';
        $txtelem = $element . '/w:t';
        $styelem = $element . '/w:rPr';
        self::assertTrue($doc->elementExists($txtelem));
        self::assertSame('This one too.', $doc->getElement($txtelem)->textContent);
        self::assertTrue($doc->elementExists($styelem));
        self::assertTrue($doc->elementExists($styelem . '/w:color'));
        self::assertSame('A7D9C1', $doc->getElementAttribute($styelem . '/w:color', 'w:val'));
    }

	/**
	 * Test writing underline color.
	 *
	 * @throws CreateTemporaryFileException
*/
	public function testUnderlineColor(): void
	{
		$phpWord = new PhpWord();
		$section = $phpWord->addSection();
		$text = 'This text has an underline color';

		// Test with a valid color (hex)
		$fontStyle = [
			'underline' => Font::UNDERLINE_SINGLE,
			'underlineColor' => 'FF0000', // Red
		];
		$section->addText($text, $fontStyle);

		$doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

		$element = '/w:document/w:body/w:p/w:r';
		$styelem = $element . '/w:rPr';
		$underlineElem = $styelem . '/w:u';
		$colorElem = $styelem . '/w:u/w:color';

		// Check that underline is present
		self::assertTrue($doc->elementExists($underlineElem));
		self::assertEquals('single', $doc->getElementAttribute($underlineElem, 'w:val'));

		// Check that underline color is written
		self::assertTrue($doc->elementExists($colorElem));
		self::assertEquals('FF0000', $doc->getElementAttribute($colorElem, 'w:val'));
	}

	/**
	 * Test that underline color is not written when empty.
	 *
	 * @throws CreateTemporaryFileException
	 */
	public function testUnderlineColorEmpty(): void
	{
		$phpWord = new PhpWord();
		$section = $phpWord->addSection();
		$text = 'This text has underline but no color';

		$fontStyle = [
			'underline' => Font::UNDERLINE_SINGLE,
			'underlineColor' => '', // Empty → should not generate w:color
		];
		$section->addText($text, $fontStyle);

		$doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

		$element = '/w:document/w:body/w:p/w:r';
		$styelem = $element . '/w:rPr';
		$underlineElem = $styelem . '/w:u';
		$colorElem = $styelem . '/w:u/w:color';

		// Check underline is present
		self::assertTrue($doc->elementExists($underlineElem));
		self::assertEquals('single', $doc->getElementAttribute($underlineElem, 'w:val'));

		// Check that w:color is NOT present (because value is empty)
		self::assertFalse($doc->elementExists($colorElem));
	}
}
