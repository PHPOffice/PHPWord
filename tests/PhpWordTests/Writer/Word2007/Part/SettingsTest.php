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

namespace PhpOffice\PhpWordTests\Writer\Word2007\Part;

use PhpOffice\PhpWord\ComplexType\ProofState;
use PhpOffice\PhpWord\ComplexType\TrackChangesView;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Microsoft\PasswordEncoder;
use PhpOffice\PhpWord\SimpleType\Zoom;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Part\Settings.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Part\Settings
 */
class SettingsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test document protection.
     */
    public function testDocumentProtection(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->getDocumentProtection()->setEditing('forms');

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:documentProtection';
        self::assertTrue($doc->elementExists($path, $file));
    }

    /**
     * Test document protection with password.
     */
    public function testDocumentProtectionWithPassword(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->getDocumentProtection()->setEditing('readOnly');
        $phpWord->getSettings()->getDocumentProtection()->setPassword('testÄö@€!$&');
        $phpWord->getSettings()->getDocumentProtection()->setSalt(base64_decode('uq81pJRRGFIY5U+E9gt8tA=='));
        $phpWord->getSettings()->getDocumentProtection()->setAlgorithm(PasswordEncoder::ALGORITHM_MD2);
        $phpWord->getSettings()->getDocumentProtection()->setSpinCount(10);
        $sect = $phpWord->addSection();
        $sect->addText('This is a protected document');

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:documentProtection';
        self::assertTrue($doc->elementExists($path, $file));
        self::assertEquals('rUuJbk6LuN2/qFyp7IUPQA==', $doc->getElement($path, $file)->getAttribute('w:hash'));
        self::assertEquals('1', $doc->getElement($path, $file)->getAttribute('w:cryptAlgorithmSid'));
        self::assertEquals('10', $doc->getElement($path, $file)->getAttribute('w:cryptSpinCount'));
    }

    /**
     * Test document protection with password without setting salt.
     */
    public function testDocumentProtectionWithPasswordNoSalt(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->getDocumentProtection()->setEditing('readOnly');
        $phpWord->getSettings()->getDocumentProtection()->setPassword('testÄö@€!$&');
        //$phpWord->getSettings()->getDocumentProtection()->setSalt(base64_decode('uq81pJRRGFIY5U+E9gt8tA=='));
        $phpWord->getSettings()->getDocumentProtection()->setAlgorithm(PasswordEncoder::ALGORITHM_MD2);
        $phpWord->getSettings()->getDocumentProtection()->setSpinCount(10);
        $sect = $phpWord->addSection();
        $sect->addText('This is a protected document');

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:documentProtection';
        self::assertTrue($doc->elementExists($path, $file));
        //$this->assertEquals('rUuJbk6LuN2/qFyp7IUPQA==', $doc->getElement($path, $file)->getAttribute('w:hash'));
        self::assertEquals('1', $doc->getElement($path, $file)->getAttribute('w:cryptAlgorithmSid'));
        self::assertEquals('10', $doc->getElement($path, $file)->getAttribute('w:cryptSpinCount'));
    }

    /**
     * Test compatibility.
     */
    public function testCompatibility(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getCompatibility()->setOoxmlVersion(15);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:compat/w:compatSetting';
        self::assertTrue($doc->elementExists($path, $file));
        self::assertEquals($phpWord->getCompatibility()->getOoxmlVersion(), 15);
    }

    /**
     * Test language.
     */
    public function testDefaultLanguage(): void
    {
        $phpWord = new PhpWord();

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:themeFontLang';
        self::assertTrue($doc->elementExists($path, $file));
        $element = $doc->getElement($path, $file);

        self::assertEquals('en-US', $element->getAttribute('w:val'));
    }

    /**
     * Test language.
     */
    public function testLanguage(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::DE_DE, Language::KO_KR, Language::HE_IL));
        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:themeFontLang';
        self::assertTrue($doc->elementExists($path, $file));
        $element = $doc->getElement($path, $file);

        self::assertEquals(Language::DE_DE, $element->getAttribute('w:val'));
        self::assertEquals(Language::KO_KR, $element->getAttribute('w:eastAsia'));
        self::assertEquals(Language::HE_IL, $element->getAttribute('w:bidi'));
    }

    /**
     * Test proofState.
     */
    public function testProofState(): void
    {
        $proofState = new ProofState();
        $proofState->setSpelling(ProofState::DIRTY);
        $proofState->setGrammar(ProofState::DIRTY);
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setProofState($proofState);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:proofState';
        self::assertTrue($doc->elementExists($path, $file));
        $element = $doc->getElement($path, $file);

        self::assertEquals('dirty', $element->getAttribute('w:spelling'));
        self::assertEquals('dirty', $element->getAttribute('w:grammar'));
    }

    /**
     * Test spelling.
     */
    public function testSpelling(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setHideSpellingErrors(true);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:hideSpellingErrors';
        self::assertTrue($doc->elementExists($path, $file));
        $element = $doc->getElement($path, $file);

        self::assertSame('true', $element->getAttribute('w:val'));
    }

    /**
     * Test even and odd headers.
     */
    public function testEvenAndOddHeaders(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setEvenAndOddHeaders(true);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:evenAndOddHeaders';
        self::assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        self::assertSame('true', $element->getAttribute('w:val'));
    }

    public function testUpdateFields(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setUpdateFields(true);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:updateFields';
        self::assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        self::assertSame('true', $element->getAttribute('w:val'));
    }

    /**
     * Test zoom percentage.
     */
    public function testZoomPercentage(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setZoom(75);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:zoom';
        self::assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        self::assertEquals('75', $element->getAttribute('w:percent'));
    }

    /**
     * Test zoom value.
     */
    public function testZoomValue(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setZoom(Zoom::FULL_PAGE);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:zoom';
        self::assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        self::assertEquals('fullPage', $element->getAttribute('w:val'));
    }

    public function testMirrorMargins(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setMirrorMargins(true);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:mirrorMargins';
        self::assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        self::assertSame('true', $element->getAttribute('w:val'));
    }

    /**
     * Test Revision View.
     */
    public function testRevisionView(): void
    {
        $trackChangesView = new TrackChangesView();
        $trackChangesView->setFormatting(false);
        $trackChangesView->setComments(true);

        $phpWord = new PhpWord();
        $phpWord->getSettings()->setRevisionView($trackChangesView);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:revisionView';
        self::assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        self::assertEquals('false', $element->getAttribute('w:formatting'));
        self::assertEquals('true', $element->getAttribute('w:comments'));
    }

    public function testHideSpellingErrors(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setHideSpellingErrors(true);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:hideSpellingErrors';
        self::assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        self::assertSame('true', $element->getAttribute('w:val'));
    }

    public function testHideGrammaticalErrors(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setHideGrammaticalErrors(true);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:hideGrammaticalErrors';
        self::assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        self::assertSame('true', $element->getAttribute('w:val'));
    }

    /**
     * Test track Revisions.
     */
    public function testTrackRevisions(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setTrackRevisions(true);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:trackRevisions';
        self::assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        self::assertSame('true', $element->getAttribute('w:val'));
    }

    /**
     * Test doNotTrackMoves.
     */
    public function testDoNotTrackMoves(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setDoNotTrackMoves(true);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:doNotTrackMoves';
        self::assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        self::assertSame('true', $element->getAttribute('w:val'));
    }

    /**
     * Test DoNotTrackFormatting.
     */
    public function testDoNotTrackFormatting(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setDoNotTrackFormatting(true);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:doNotTrackFormatting';
        self::assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        self::assertSame('true', $element->getAttribute('w:val'));
    }

    public function testAutoHyphenation(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setAutoHyphenation(true);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:autoHyphenation';
        self::assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        self::assertSame('true', $element->getAttribute('w:val'));
    }

    public function testConsecutiveHyphenLimit(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setConsecutiveHyphenLimit(2);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:consecutiveHyphenLimit';
        self::assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        self::assertSame('2', $element->getAttribute('w:val'));
    }

    public function testHyphenationZone(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setHyphenationZone(100);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:hyphenationZone';
        self::assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        self::assertSame('100', $element->getAttribute('w:val'));
    }

    public function testDoNotHyphenateCaps(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setDoNotHyphenateCaps(true);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:doNotHyphenateCaps';
        self::assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        self::assertSame('true', $element->getAttribute('w:val'));
    }

    public function testBookFoldPrinting(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setBookFoldPrinting(true);

        $doc = TestHelperDOCX::getDocument($phpWord);

        $file = 'word/settings.xml';

        $path = '/w:settings/w:bookFoldPrinting';
        self::assertTrue($doc->elementExists($path, $file));

        $element = $doc->getElement($path, $file);
        self::assertSame('true', $element->getAttribute('w:val'));
    }
}
