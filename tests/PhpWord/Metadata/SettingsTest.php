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
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Metadata;

use PhpOffice\PhpWord\ComplexType\ProofState;
use PhpOffice\PhpWord\SimpleType\Zoom;

/**
 * Test class for PhpOffice\PhpWord\Metadata\Settings
 *
 * @runTestsInSeparateProcesses
 */
class SettingsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * EvenAndOddHeaders
     */
    public function testSetEvenAndOddHeaders()
    {
        $oSettings = new Settings();
        $oSettings->setEvenAndOddHeaders(true);
        $this->assertTrue($oSettings->hasEvenAndOddHeaders());
    }

    /**
     * HideGrammaticalErrors
     */
    public function testHideGrammaticalErrors()
    {
        $oSettings = new Settings();
        $oSettings->setHideGrammaticalErrors(true);
        $this->assertTrue($oSettings->hasHideGrammaticalErrors());
    }

    /**
     * HideSpellingErrors
     */
    public function testHideSpellingErrors()
    {
        $oSettings = new Settings();
        $oSettings->setHideSpellingErrors(true);
        $this->assertTrue($oSettings->hasHideSpellingErrors());
    }

    /**
     * DocumentProtection
     */
    public function testDocumentProtection()
    {
        $oSettings = new Settings();
        $oSettings->setDocumentProtection(new Protection('trackedChanges'));
        $this->assertNotNull($oSettings->getDocumentProtection());

        $this->assertEquals('trackedChanges', $oSettings->getDocumentProtection()->getEditing());
    }

    /**
     * Test setting an invalid salt
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidSalt()
    {
        $protection = new Protection();
        $protection->setSalt('123');
    }

    /**
     * TrackRevistions
     */
    public function testTrackRevisions()
    {
        $oSettings = new Settings();
        $oSettings->setTrackRevisions(true);
        $this->assertTrue($oSettings->hasTrackRevisions());
    }

    /**
     * DoNotTrackFormatting
     */
    public function testDoNotTrackFormatting()
    {
        $oSettings = new Settings();
        $oSettings->setDoNotTrackFormatting(true);
        $this->assertTrue($oSettings->hasDoNotTrackFormatting());
    }

    /**
     * DoNotTrackMoves
     */
    public function testDoNotTrackMoves()
    {
        $oSettings = new Settings();
        $oSettings->setDoNotTrackMoves(true);
        $this->assertTrue($oSettings->hasDoNotTrackMoves());
    }

    /**
     * ProofState
     */
    public function testProofState()
    {
        $proofState = new ProofState();
        $proofState->setGrammar(ProofState::CLEAN);
        $proofState->setSpelling(ProofState::DIRTY);

        $oSettings = new Settings();
        $oSettings->setProofState($proofState);
        $this->assertNotNull($oSettings->getProofState());
        $this->assertEquals(ProofState::CLEAN, $oSettings->getProofState()->getGrammar());
        $this->assertEquals(ProofState::DIRTY, $oSettings->getProofState()->getSpelling());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongProofStateGrammar()
    {
        $proofState = new ProofState();
        $proofState->setGrammar('wrong');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongProofStateSpelling()
    {
        $proofState = new ProofState();
        $proofState->setSpelling('wrong');
    }

    /**
     * Zoom as percentage
     */
    public function testZoomPercentage()
    {
        $oSettings = new Settings();
        $oSettings->setZoom(75);
        $this->assertEquals(75, $oSettings->getZoom());
    }

    /**
     * Zoom as string
     */
    public function testZoomEnum()
    {
        $oSettings = new Settings();
        $oSettings->setZoom(Zoom::FULL_PAGE);
        $this->assertEquals('fullPage', $oSettings->getZoom());
    }

    /**
     * Test Update Fields on update
     */
    public function testUpdateFields()
    {
        $oSettings = new Settings();
        $oSettings->setUpdateFields(true);
        $this->assertTrue($oSettings->hasUpdateFields());
    }
}
