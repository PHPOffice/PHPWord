<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * document files.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

/**
 * This file is part of PHPWord - A pure PHP library to read and write documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser General Public License.
 */

namespace PhpOffice\PhpWordTests\Writer\ODText\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWordTests\TestHelperDOCX;
use PHPUnit\Framework\TestCase;

/**
 * Test class for ODText form controls.
 */
class FormControlsTest extends TestCase
{
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testWritesNativeFormControlsAndAnchors(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $checkBox = $section->addCheckBox('accept', 'Accept');
        $textInput = $section->addFormField('textinput')->setName('name')->setDefault('Default')->setValue('Alice');
        $dropdown = $section->addFormField('dropdown')->setName('color')->setEntries(['Red', 'Blue'])->setDefault(0)->setValue(1);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $form = '/office:document-content/office:body/office:text/office:forms/form:form';

        self::assertTrue($doc->elementExists($form));
        self::assertSame('accept', $doc->getElementAttribute($form . '/form:checkbox', 'form:name'));
        self::assertSame('unchecked', $doc->getElementAttribute($form . '/form:checkbox', 'form:current-state'));
        self::assertSame('Accept', $doc->getElementAttribute($form . '/form:checkbox', 'form:label'));
        self::assertSame('Alice', $doc->getElementAttribute($form . '/form:text', 'form:current-value'));
        self::assertSame('Default', $doc->getElementAttribute($form . '/form:text', 'form:value'));
        self::assertSame('Blue', $doc->getElement($form . '/form:listbox/form:option[2]')->textContent);
        self::assertSame('true', $doc->getElementAttribute($form . '/form:listbox/form:option[2]', 'form:current-selected'));
        self::assertTrue($doc->elementExists('/office:document-content/office:body/office:text/text:section/text:p[2]/draw:control[@draw:control="control-' . $checkBox->getElementId() . '"]'));
        self::assertTrue($doc->elementExists('/office:document-content/office:body/office:text/text:section/text:p[3]/draw:control[@draw:control="control-' . $textInput->getElementId() . '"]'));
        self::assertTrue($doc->elementExists('/office:document-content/office:body/office:text/text:section/text:p[4]/draw:control[@draw:control="control-' . $dropdown->getElementId() . '"]'));
    }
}
