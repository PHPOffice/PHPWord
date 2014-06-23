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
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */
namespace PhpOffice\PhpWord\Tests\Writer\Word2007\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Tests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Part\Settings
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Part\Settings
 */
class SettingsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test document protection
     */
    public function testDocumentProtection()
    {
        $phpWord = new PhpWord();
        $phpWord->getProtection()->setEditing('forms');

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:documentProtection';
        $this->assertTrue($doc->elementExists($path, $file));
    }

    /**
     * Test compatibility
     */
    public function testCompatibility()
    {
        $phpWord = new PhpWord();
        $phpWord->getCompatibility()->setOoxmlVersion(15);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:compat/w:compatSetting';
        $this->assertTrue($doc->elementExists($path, $file));
    }
}
