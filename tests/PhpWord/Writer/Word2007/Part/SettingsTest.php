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
namespace PhpOffice\PhpWord\Writer\Word2007\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TestHelperDOCX;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\Zoom;
use PhpOffice\PhpWord\ComplexType\TrackChangesView;

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
        $phpWord->getSettings()->getDocumentProtection()->setEditing('forms');

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
        $this->assertEquals($phpWord->getCompatibility()->getOoxmlVersion(), 15);
    }

    /**
     * Test language
     */
    public function testLanguage()
    {
        $phpWord = new PhpWord();
        
        $doc = TestHelperDOCX::getDocument($phpWord);
        
        $file = 'word/settings.xml';
        
        $path = '/w:settings/w:themeFontLang';
        $this->assertTrue($doc->elementExists($path, $file));
        $element = $doc->getElement($path, $file);
        
        $this->assertEquals('en-US', $element->getAttribute('w:val'));
    }

    /**
     * Test spelling
     */
    public function testSpelling()
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setHideSpellingErrors(true);
        
        $doc = TestHelperDOCX::getDocument($phpWord);
        
        $file = 'word/settings.xml';
        
        $path = '/w:settings/w:hideSpellingErrors';
        $this->assertTrue($doc->elementExists($path, $file));
        $element = $doc->getElement($path, $file);
        
        $this->assertNotEquals('false', $element->getAttribute('w:val'));
    }

    /**
     * Test even and odd headers
     */
    public function testEvenAndOddHeaders()
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setEvenAndOddHeaders(true);
        
        $doc = TestHelperDOCX::getDocument($phpWord);
        
        $file = 'word/settings.xml';
        
        $path = '/w:settings/w:evenAndOddHeaders';
        $this->assertTrue($doc->elementExists($path, $file));
        
        $element = $doc->getElement($path, $file);
        $this->assertNotEquals('false', $element->getAttribute('w:val'));
    }

    /**
     * Test zoom percentage
     */
    public function testZoomPercentage()
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setZoom(75);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:zoom';
        $this->assertTrue($doc->elementExists($path, $file));
        
        $element = $doc->getElement($path, $file);
        $this->assertEquals('75', $element->getAttribute('w:percent'));
    }

    /**
     * Test zoom value
     */
    public function testZoomValue()
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setZoom(Zoom::FULL_PAGE);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:zoom';
        $this->assertTrue($doc->elementExists($path, $file));
        
        $element = $doc->getElement($path, $file);
        $this->assertEquals('fullPage', $element->getAttribute('w:val'));
    }

    /**
     * Test Revision View
     */
    public function testRevisionView()
    {
        $trackChangesView = new TrackChangesView();
        $trackChangesView->setFormatting(false);
        $trackChangesView->setComments(true);

        $phpWord = new PhpWord();
        $phpWord->getSettings()->setRevisionView($trackChangesView);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:revisionView';
        $this->assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        $this->assertEquals('false', $element->getAttribute('w:formatting'));
        $this->assertEquals('true', $element->getAttribute('w:comments'));
    }

    /**
     * Test track Revisions
     */
    public function testTrackRevisions()
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setTrackRevisions(true);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:trackRevisions';
        $this->assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        $this->assertNotEquals('false', $element->getAttribute('w:val'));
    }

    /**
     * Test doNotTrackMoves
     */
    public function testDoNotTrackMoves()
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setDoNotTrackMoves(true);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:doNotTrackMoves';
        $this->assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        $this->assertNotEquals('false', $element->getAttribute('w:val'));
    }

    /**
     * Test DoNotTrackFormatting
     */
    public function testDoNotTrackFormatting()
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setDoNotTrackFormatting(true);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:doNotTrackFormatting';
        $this->assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        $this->assertNotEquals('false', $element->getAttribute('w:val'));
    }
}
