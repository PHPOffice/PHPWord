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

namespace PhpOffice\PhpWordTests\Writer\ODText\Style;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWordTests\TestHelperDOCX;

class Paragraph2Test extends \PHPUnit\Framework\TestCase
{
    /**
     * Test textAlign.
     */
    public function testTextAlign(): void
    {
        $phpWord = new PhpWord();
        Settings::setDefaultRtl(true);
        $align1 = ['alignment' => 'end'];
        $align2 = ['alignment' => 'start'];
        $phpWord->setDefaultParagraphStyle($align1);
        $section = $phpWord->addSection();
        $section->addText('Should use default alignment (right for this doc)');
        $section->addText('Explicit right alignment', null, $align2);
        $section->addText('Explicit left alignment', null, $align1);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        self::assertTrue($doc->elementExists($s2a));

        $element = "$s2a/style:style[4]";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('Normal', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('right', $doc->getElementAttribute($element, 'fo:text-align'));

        $element = "$s2a/style:style[6]/style:paragraph-properties";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('right', $doc->getElementAttribute($element, 'fo:text-align'));

        $element = "$s2a/style:style[8]/style:paragraph-properties";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('left', $doc->getElementAttribute($element, 'fo:text-align'));

        $doc->setDefaultFile('styles.xml');
        $element = '/office:document-styles/office:styles/style:style';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('Normal', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('left', $doc->getElementAttribute($element, 'fo:text-align'));
    }

    /**
     * Test text run paragraph style using named style.
     */
    public function testTextRun(): void
    {
        $phpWord = new PhpWord();
        Settings::setDefaultRtl(false);
        $phpWord->addParagraphStyle('parstyle1', ['align' => 'start']);
        $phpWord->addParagraphStyle('parstyle2', ['align' => 'end']);
        $section = $phpWord->addSection();
        $trx = $section->addTextRun('parstyle1');
        $trx->addText('First text in textrun. ');
        $trx->addText('Second text - paragraph style is specified but ignored.', null, 'parstyle2');
        $section->addText('Third text added to section not textrun - paragraph style is specified and used.', null, 'parstyle2');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $element = "$s2a/style:style[3]";
        self::assertEquals('P1_parstyle1', $doc->getElementAttribute($element, 'style:name'));
        self::assertEquals('parstyle1', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element = "$s2a/style:style[9]";
        self::assertEquals('P4_parstyle2', $doc->getElementAttribute($element, 'style:name'));
        self::assertEquals('parstyle2', $doc->getElementAttribute($element, 'style:parent-style-name'));

        $s2a = '/office:document-content/office:body/office:text/text:section';
        $element = "$s2a/text:p[2]";
        self::assertEquals('P1_parstyle1', $doc->getElementAttribute($element, 'text:style-name'));
        $element = "$s2a/text:p[3]";
        self::assertEquals('P4_parstyle2', $doc->getElementAttribute($element, 'text:style-name'));

        $doc->setDefaultFile('styles.xml');
        $element = '/office:document-styles/office:styles/style:style[1]';
        self::assertEquals('parstyle1', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        self::assertEquals('left', $doc->getElementAttribute($element, 'fo:text-align'));
        $element = '/office:document-styles/office:styles/style:style[2]';
        self::assertEquals('parstyle2', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        self::assertEquals('right', $doc->getElementAttribute($element, 'fo:text-align'));
    }

    /**
     * Test text run paragraph style using unnamed style.
     */
    public function testTextRunUnnamed(): void
    {
        $phpWord = new PhpWord();
        Settings::setDefaultRtl(false);
        $parstyle1 = ['align' => 'start'];
        $parstyle2 = ['align' => 'end'];
        $section = $phpWord->addSection();
        $trx = $section->addTextRun($parstyle1);
        $trx->addText('First text in textrun. ');
        $trx->addText('Second text - paragraph style is specified but ignored.', null, $parstyle2);
        $section->addText('Third text added to section not textrun - paragraph style is specified and used.', null, $parstyle2);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $element = "$s2a/style:style[3]";
        self::assertEquals('P1', $doc->getElementAttribute($element, 'style:name'));
        self::assertEquals('Normal', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        self::assertEquals('left', $doc->getElementAttribute($element, 'fo:text-align'));
        $element = "$s2a/style:style[9]";
        self::assertEquals('P4', $doc->getElementAttribute($element, 'style:name'));
        self::assertEquals('Normal', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        self::assertEquals('right', $doc->getElementAttribute($element, 'fo:text-align'));

        $s2a = '/office:document-content/office:body/office:text/text:section';
        $element = "$s2a/text:p[2]";
        self::assertEquals('P1', $doc->getElementAttribute($element, 'text:style-name'));
        $element = "$s2a/text:p[3]";
        self::assertEquals('P4', $doc->getElementAttribute($element, 'text:style-name'));
    }

    public function testWhenNullifed(): void
    {
        $dflt1 = Settings::isDefaultRtl();
        self::assertFalse($dflt1);
        $phpWord = new PhpWord();
        $dflt2 = Settings::isDefaultRtl();
        self::assertNull($dflt2);
    }
}
