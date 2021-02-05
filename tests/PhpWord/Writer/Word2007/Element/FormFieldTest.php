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

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Element subnamespace
 */
class FormFieldTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test form fields
     */
    public function testFormFieldElements()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addFormField('textinput')->setName('MyTextBox');
        $section->addFormField('checkbox')->setDefault(true)->setValue('Your name');
        $section->addFormField('checkbox')->setDefault(true);
        $section->addFormField('dropdown')->setEntries(array('Choice 1', 'Choice 2', 'Choice 3', ''));

        $doc = TestHelperDOCX::getDocument($phpWord);

        $path = '/w:document/w:body/w:p[1]/w:r/w:fldChar/w:ffData';
        $this->assertTrue($doc->elementExists("$path/w:textInput"));
        $this->assertEquals('MyTextBox', $doc->getElementAttribute("$path/w:name", 'w:val'));

        $path = '/w:document/w:body/w:p[2]/w:r/w:fldChar/w:ffData';
        $this->assertTrue($doc->elementExists("$path/w:checkBox"));
        $path = '/w:document/w:body/w:p[2]/w:r[4]/w:t';
        $this->assertEquals('Your name', $doc->getElement($path)->textContent);

        $path = '/w:document/w:body/w:p[3]/w:r/w:fldChar/w:ffData';
        $this->assertTrue($doc->elementExists("$path/w:checkBox"));

        $path = '/w:document/w:body/w:p[4]/w:r/w:fldChar/w:ffData/w:ddList';
        $this->assertTrue($doc->elementExists($path));
        $this->assertEquals('Choice 1', $doc->getElementAttribute("$path/w:listEntry[1]", 'w:val'));
        $this->assertEquals('Choice 2', $doc->getElementAttribute("$path/w:listEntry[2]", 'w:val'));
        $this->assertEquals('Choice 3', $doc->getElementAttribute("$path/w:listEntry[3]", 'w:val'));
        $this->assertEquals('', trim($doc->getElementAttribute("$path/w:listEntry[4]", 'w:val'), ' '));
    }
}
