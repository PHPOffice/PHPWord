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

namespace PhpOffice\PhpWord\Metadata;

/**
 * Test class for PhpOffice\PhpWord\Metadata\Settings
 *
 * @runTestsInSeparateProcesses
 */
class SettingsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * EvenAndOddHeaders
     */
    public function testSetEvenAndOddHeaders()
    {
        $oSettings = new Settings();
        $oSettings->setEvenAndOddHeaders(true);
        $this->assertEquals(true, $oSettings->hasEvenAndOddHeaders());
    }

    /**
     * HideGrammaticalErrors
     */
    public function testHideGrammaticalErrors()
    {
        $oSettings = new Settings();
        $oSettings->setHideGrammaticalErrors(true);
        $this->assertEquals(true, $oSettings->hasHideGrammaticalErrors());
    }

    /**
     * HideSpellingErrors
     */
    public function testHideSpellingErrors()
    {
        $oSettings = new Settings();
        $oSettings->setHideSpellingErrors(true);
        $this->assertEquals(true, $oSettings->hasHideSpellingErrors());
    }

    /**
     * DocumentProtection
     */
    public function testDocumentProtection()
    {
        $oSettings = new Settings();
        $oSettings->setDocumentProtection(new Protection());
        $this->assertNotNull($oSettings->getDocumentProtection());

        $oSettings->getDocumentProtection()->setEditing('trackedChanges');
        $this->assertEquals('trackedChanges', $oSettings->getDocumentProtection()->getEditing());
    }
}
