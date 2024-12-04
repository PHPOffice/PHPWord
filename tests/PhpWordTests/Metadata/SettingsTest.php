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

namespace PhpOffice\PhpWordTests\Metadata;

use InvalidArgumentException;
use PhpOffice\PhpWord\ComplexType\ProofState;
use PhpOffice\PhpWord\Metadata\Protection;
use PhpOffice\PhpWord\Metadata\Settings;
use PhpOffice\PhpWord\SimpleType\Zoom;

/**
 * Test class for PhpOffice\PhpWord\Metadata\Settings.
 *
 * @runTestsInSeparateProcesses
 */
class SettingsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * EvenAndOddHeaders.
     */
    public function testSetEvenAndOddHeaders(): void
    {
        $oSettings = new Settings();
        $oSettings->setEvenAndOddHeaders(true);
        self::assertTrue($oSettings->hasEvenAndOddHeaders());
    }

    /**
     * HideGrammaticalErrors.
     */
    public function testHideGrammaticalErrors(): void
    {
        $oSettings = new Settings();
        $oSettings->setHideGrammaticalErrors(true);
        self::assertTrue($oSettings->hasHideGrammaticalErrors());
    }

    /**
     * HideSpellingErrors.
     */
    public function testHideSpellingErrors(): void
    {
        $oSettings = new Settings();
        $oSettings->setHideSpellingErrors(true);
        self::assertTrue($oSettings->hasHideSpellingErrors());
    }

    /**
     * DocumentProtection.
     */
    public function testDocumentProtection(): void
    {
        $oSettings = new Settings();
        $oSettings->setDocumentProtection(new Protection('trackedChanges'));
        self::assertNotNull($oSettings->getDocumentProtection());

        self::assertEquals('trackedChanges', $oSettings->getDocumentProtection()->getEditing());
    }

    /**
     * Test setting an invalid salt.
     */
    public function testInvalidSalt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $protection = new Protection();
        $protection->setSalt('123');
    }

    /**
     * TrackRevistions.
     */
    public function testTrackRevisions(): void
    {
        $oSettings = new Settings();
        $oSettings->setTrackRevisions(true);
        self::assertTrue($oSettings->hasTrackRevisions());
    }

    /**
     * DoNotTrackFormatting.
     */
    public function testDoNotTrackFormatting(): void
    {
        $oSettings = new Settings();
        $oSettings->setDoNotTrackFormatting(true);
        self::assertTrue($oSettings->hasDoNotTrackFormatting());
    }

    /**
     * DoNotTrackMoves.
     */
    public function testDoNotTrackMoves(): void
    {
        $oSettings = new Settings();
        $oSettings->setDoNotTrackMoves(true);
        self::assertTrue($oSettings->hasDoNotTrackMoves());
    }

    /**
     * ProofState.
     */
    public function testProofState(): void
    {
        $proofState = new ProofState();
        $proofState->setGrammar(ProofState::CLEAN);
        $proofState->setSpelling(ProofState::DIRTY);

        $oSettings = new Settings();
        $oSettings->setProofState($proofState);
        self::assertNotNull($oSettings->getProofState());
        self::assertEquals(ProofState::CLEAN, $oSettings->getProofState()->getGrammar());
        self::assertEquals(ProofState::DIRTY, $oSettings->getProofState()->getSpelling());
    }

    public function testWrongProofStateGrammar(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $proofState = new ProofState();
        $proofState->setGrammar('wrong');
    }

    public function testWrongProofStateSpelling(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $proofState = new ProofState();
        $proofState->setSpelling('wrong');
    }

    /**
     * Zoom as percentage.
     */
    public function testZoomPercentage(): void
    {
        $oSettings = new Settings();
        $oSettings->setZoom(75);
        self::assertEquals(75, $oSettings->getZoom());
    }

    /**
     * Zoom as string.
     */
    public function testZoomEnum(): void
    {
        $oSettings = new Settings();
        $oSettings->setZoom(Zoom::FULL_PAGE);
        self::assertEquals('fullPage', $oSettings->getZoom());
    }

    /**
     * Test Update Fields on update.
     */
    public function testUpdateFields(): void
    {
        $oSettings = new Settings();
        $oSettings->setUpdateFields(true);
        self::assertTrue($oSettings->hasUpdateFields());
    }

    public function testAutoHyphenation(): void
    {
        $oSettings = new Settings();
        $oSettings->setAutoHyphenation(true);
        self::assertTrue($oSettings->hasAutoHyphenation());
    }

    public function testDefaultAutoHyphenation(): void
    {
        $oSettings = new Settings();
        self::assertNull($oSettings->hasAutoHyphenation());
    }

    public function testConsecutiveHyphenLimit(): void
    {
        $consecutiveHypenLimit = 2;
        $oSettings = new Settings();
        $oSettings->setConsecutiveHyphenLimit($consecutiveHypenLimit);
        self::assertSame($consecutiveHypenLimit, $oSettings->getConsecutiveHyphenLimit());
    }

    public function testDefaultConsecutiveHyphenLimit(): void
    {
        $oSettings = new Settings();
        self::assertNull($oSettings->getConsecutiveHyphenLimit());
    }

    public function testHyphenationZone(): void
    {
        $hyphenationZoneInTwip = 100;
        $oSettings = new Settings();
        $oSettings->setHyphenationZone($hyphenationZoneInTwip);
        self::assertSame($hyphenationZoneInTwip, $oSettings->getHyphenationZone());
    }

    public function testDefaultHyphenationZone(): void
    {
        $oSettings = new Settings();
        self::assertNull($oSettings->getHyphenationZone());
    }

    public function testDoNotHyphenateCaps(): void
    {
        $oSettings = new Settings();
        $oSettings->setDoNotHyphenateCaps(true);
        self::assertTrue($oSettings->hasDoNotHyphenateCaps());
    }

    public function testDefaultDoNotHyphenateCaps(): void
    {
        $oSettings = new Settings();
        self::assertNull($oSettings->hasDoNotHyphenateCaps());
    }

    public function testBookFoldPrinting(): void
    {
        $oSettings = new Settings();
        self::assertInstanceOf(Settings::class, $oSettings->setBookFoldPrinting(true));
        self::assertTrue($oSettings->hasBookFoldPrinting());
        self::assertInstanceOf(Settings::class, $oSettings->setBookFoldPrinting(false));
        self::assertFalse($oSettings->hasBookFoldPrinting());
    }

    public function testDefaultBookFoldPrinting(): void
    {
        $oSettings = new Settings();
        self::assertFalse($oSettings->hasBookFoldPrinting());
    }
}
