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

namespace PhpOffice\PhpWordTests\Writer\Word2007\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Element subnamespace.
 */
class FormFieldTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test form fields.
     */
    public function testFormFieldElements(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addFormField('textinput')->setName('MyTextBox');
        $section->addFormField('checkbox')->setDefault(true)->setValue('Your name');
        $section->addFormField('checkbox')->setDefault(true);
        $section->addFormField('dropdown')->setEntries(['Choice 1', 'Choice 2', 'Choice 3', '']);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $path = '/w:document/w:body/w:p[1]/w:r/w:fldChar/w:ffData';
        self::assertTrue($doc->elementExists("$path/w:textInput"));
        self::assertEquals('MyTextBox', $doc->getElementAttribute("$path/w:name", 'w:val'));

        $path = '/w:document/w:body/w:p[2]/w:r/w:fldChar/w:ffData';
        self::assertTrue($doc->elementExists("$path/w:checkBox"));
        $path = '/w:document/w:body/w:p[2]/w:r[4]/w:t';
        self::assertEquals('Your name', $doc->getElement($path)->textContent);

        $path = '/w:document/w:body/w:p[3]/w:r/w:fldChar/w:ffData';
        self::assertTrue($doc->elementExists("$path/w:checkBox"));

        $path = '/w:document/w:body/w:p[4]/w:r/w:fldChar/w:ffData/w:ddList';
        self::assertTrue($doc->elementExists($path));
        self::assertEquals('Choice 1', $doc->getElementAttribute("$path/w:listEntry[1]", 'w:val'));
        self::assertEquals('Choice 2', $doc->getElementAttribute("$path/w:listEntry[2]", 'w:val'));
        self::assertEquals('Choice 3', $doc->getElementAttribute("$path/w:listEntry[3]", 'w:val'));
        self::assertEquals('', trim($doc->getElementAttribute("$path/w:listEntry[4]", 'w:val'), ' '));
    }

    /**
     * Test that a form field writes a default only when it has one, and that the one it writes has a value.
     */
    public function testFormFieldDefault(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addFormField('textinput')->setName('NoDefault');
        $section->addFormField('textinput')->setName('WithDefault')->setDefault('Hello');
        $section->addFormField('checkbox')->setName('MyCheckBox');
        $section->addFormField('dropdown')->setName('NoDefault')->setEntries(['Choice 1', 'Choice 2']);
        $section->addFormField('dropdown')->setName('WithDefault')->setEntries(['Choice 1', 'Choice 2'])->setDefault(1);
        $section->addFormField('dropdown')->setName('WithValue')->setEntries(['Choice 1', 'Choice 2'])->setValue(1);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $path = '/w:document/w:body/w:p[1]/w:r/w:fldChar/w:ffData/w:textInput';
        self::assertTrue($doc->elementExists($path));
        self::assertFalse($doc->elementExists($path . '/w:default'));

        $path = '/w:document/w:body/w:p[2]/w:r/w:fldChar/w:ffData/w:textInput/w:default';
        self::assertSame('Hello', $doc->getElementAttribute($path, 'w:val'));

        $path = '/w:document/w:body/w:p[3]/w:r/w:fldChar/w:ffData/w:checkBox';
        self::assertTrue($doc->elementExists($path . '/w:sizeAuto'));
        self::assertFalse($doc->hasElementAttribute($path . '/w:sizeAuto', 'w:val'));
        self::assertSame('0', $doc->getElementAttribute($path . '/w:default', 'w:val'));
        self::assertSame('0', $doc->getElementAttribute($path . '/w:checked', 'w:val'));

        $path = '/w:document/w:body/w:p[4]/w:r/w:fldChar/w:ffData/w:ddList';
        self::assertFalse($doc->elementExists($path . '/w:default'));
        self::assertFalse($doc->elementExists($path . '/w:result'));

        $path = '/w:document/w:body/w:p[5]/w:r/w:fldChar/w:ffData/w:ddList';
        self::assertSame('1', $doc->getElementAttribute($path . '/w:default', 'w:val'));
        self::assertSame('1', $doc->getElementAttribute($path . '/w:result', 'w:val'));

        $path = '/w:document/w:body/w:p[6]/w:r/w:fldChar/w:ffData/w:ddList';
        self::assertFalse($doc->elementExists($path . '/w:default'));
        self::assertSame('1', $doc->getElementAttribute($path . '/w:result', 'w:val'));
    }
}
