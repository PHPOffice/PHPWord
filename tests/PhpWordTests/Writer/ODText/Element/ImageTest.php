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
use PhpOffice\PhpWord\Style\Image;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\ODText\Element\Image.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\ODText\Element\Image
 */
class ImageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed after each method of the class.
     */
    protected function tearDown(): void
    {
        Settings::setDefaultRtl(null);
        TestHelperDOCX::clear();
    }

    /**
     * Test writing image.
     */
    public function testImage1(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addImage(__DIR__ . '/../../../_files/images/earth.jpg');
        $section->addImage(__DIR__ . '/../../../_files/images/mario.gif', ['align' => 'end']);
        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $element = "$s2a/style:style[3]";
        self::assertEquals('IM1', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        self::assertEquals('', $doc->getElementAttribute($element, 'fo:text-align'));
        $element = "$s2a/style:style[4]";
        self::assertEquals('IM2', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        self::assertEquals('end', $doc->getElementAttribute($element, 'fo:text-align'));

        $path = '/office:document-content/office:body/office:text/text:section/text:p[2]';
        self::assertTrue($doc->elementExists($path));
        self::assertFalse($doc->hasElementAttribute($path, 'draw:text-style-name'));
        self::assertEquals('IM1', $doc->getElementAttribute($path, 'text:style-name'));
        $path = '/office:document-content/office:body/office:text/text:section/text:p[3]';
        self::assertTrue($doc->elementExists($path));
        self::assertFalse($doc->hasElementAttribute($path, 'draw:text-style-name'));
        self::assertEquals('IM2', $doc->getElementAttribute($path, 'text:style-name'));
    }

    /**
     * Test writing image, with non-default bidi.
     */
    public function testImage2(): void
    {
        $phpWord = new PhpWord();
        Settings::setDefaultRtl(false);
        $section = $phpWord->addSection();
        $section->addImage(__DIR__ . '/../../../_files/images/earth.jpg');
        $section->addImage(__DIR__ . '/../../../_files/images/mario.gif', ['align' => 'end']);
        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $element = "$s2a/style:style[3]";
        self::assertEquals('IM1', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        self::assertEquals('left', $doc->getElementAttribute($element, 'fo:text-align'));
        $element = "$s2a/style:style[4]";
        self::assertEquals('IM2', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        self::assertEquals('right', $doc->getElementAttribute($element, 'fo:text-align'));

        $path = '/office:document-content/office:body/office:text/text:section/text:p[2]';
        self::assertTrue($doc->elementExists($path));
        self::assertEquals('IM1', $doc->getElementAttribute($path, 'text:style-name'));
        self::assertFalse($doc->hasElementAttribute($path, 'draw:text-style-name'));
        $path = '/office:document-content/office:body/office:text/text:section/text:p[3]';
        self::assertTrue($doc->elementExists($path));
        self::assertEquals('IM2', $doc->getElementAttribute($path, 'text:style-name'));
        self::assertFalse($doc->hasElementAttribute($path, 'draw:text-style-name'));
    }

    /**
     * Test writing image not in a section.
     */
    public function testImageInTextRun(): void
    {
        $phpWord = new PhpWord();
        Settings::setDefaultRtl(false);
        $section = $phpWord->addSection();
        $textRun = $section->addTextRun();
        $textRun->addImage(__DIR__ . '/../../../_files/images/earth.jpg');
        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $element = "$s2a/style:style[4]";
        self::assertEquals('IM1', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        self::assertEquals('left', $doc->getElementAttribute($element, 'fo:text-align'));

        $path = '/office:document-content/office:body/office:text/text:section/text:p[2]';
        self::assertTrue($doc->elementExists($path));
        self::assertEquals('P1', $doc->getElementAttribute($path, 'text:style-name'));
        $path = '/office:document-content/office:body/office:text/text:section/text:p[2]/draw:frame';
        self::assertTrue($doc->elementExists($path));
        self::assertTrue($doc->hasElementAttribute($path, 'draw:text-style-name'));
        self::assertEquals('IM1', $doc->getElementAttribute($path, 'draw:text-style-name'));
    }
}
