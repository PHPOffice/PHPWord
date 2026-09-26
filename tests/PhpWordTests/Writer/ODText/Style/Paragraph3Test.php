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
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWordTests\TestHelperDOCX;

class Paragraph3Test extends \PHPUnit\Framework\TestCase
{
    /**
     * Test text run paragraph style using named style.
     */
    public function testTextAndTextRun(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addParagraphStyle('alignCenter', [
            'align' => 'center',
        ]);
        $alignCenter = new Paragraph();
        $alignCenter->setStyleName('alignCenter');
        $phpWord->addParagraphStyle('alignEnd', [
            'align' => 'end',
        ]);
        $alignEnd = new Paragraph();
        $alignEnd->setStyleName('alignEnd');
        $section = $phpWord->addSection();
        $section->addText('Should be aligned center.', null, $alignCenter);
        $tr = $section->addTextRun($alignEnd);
        $tr->addText('Should be aligned right.');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:body/office:text/text:section';
        $element = "$s2a/text:p[2]";
        self::assertEquals('alignCenter', $doc->getElementAttribute($element, 'text:style-name'));
        $element = "$s2a/text:p[3]";
        self::assertEquals('alignEnd', $doc->getElementAttribute($element, 'text:style-name'));

        $doc->setDefaultFile('styles.xml');
        $element = '/office:document-styles/office:styles/style:style[1]';
        self::assertEquals('alignCenter', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        self::assertEquals('center', $doc->getElementAttribute($element, 'fo:text-align'));
        $element = '/office:document-styles/office:styles/style:style[2]';
        self::assertEquals('alignEnd', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        self::assertEquals('end', $doc->getElementAttribute($element, 'fo:text-align'));
    }
}
