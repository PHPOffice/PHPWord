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

use PhpOffice\PhpWord\ComplexType\ProofState;
use PhpOffice\PhpWord\SimpleType\Zoom;
use PhpOffice\PhpWord\ComplexType\TrackChangesView;
use PhpOffice\PhpWord\Style\Language;

/**
 * Setting class
 *
 * @since 0.14.0
 * @link http://www.datypic.com/sc/ooxml/t-w_CT_Settings.html
 */
class Settings
{

    /**
     * Magnification Setting
     *
     * @link http://www.datypic.com/sc/ooxml/e-w_zoom-1.html
     * @var mixed either integer, in which case it treated as a percent, or one of PhpOffice\PhpWord\SimpleType\Zoom
     */
    private $zoom = 100;

    /**
     * Hide spelling errors
     *
     * @var boolean
     */
    private $hideSpellingErrors = false;

    /**
     * Hide grammatical errors
     *
     * @var boolean
     */
    private $hideGrammaticalErrors = false;

    /**
     * Visibility of Annotation Types
     *
     * @var TrackChangesView
     */
    private $revisionView;

    /**
     * Track Revisions to Document
     *
     * @var boolean
     */
    private $trackRevisions = false;

    /**
     * Do Not Use Move Syntax When Tracking Revisions
     *
     * @var boolean
     */
    private $doNotTrackMoves = false;

    /**
     * Do Not Track Formatting Revisions When Tracking Revisions
     *
     * @var boolean
     */
    private $doNotTrackFormatting = false;

    /**
     * Spelling and Grammatical Checking State
     *
     * @var \PhpOffice\PhpWord\Metadata\ProofState
     */
    private $proofState;

    /**
     * Document Editing Restrictions
     *
     * @var \PhpOffice\PhpWord\Metadata\Protection
     */
    private $documentProtection;

    /**
     * Enables different header for odd and even pages.
     *
     * @var bool
     */
    private $evenAndOddHeaders = false;

    /**
     * Theme Font Languages
     * 
     * @var Language
     */
    private $themeFontLang;

    /**
     * Radix Point for Field Code Evaluation
     *
     * @var string
     */
    private $decimalSymbol = '.';

    /**
     * @return Protection
     */
    public function getDocumentProtection()
    {
        if ($this->documentProtection == null) {
            $this->documentProtection = new Protection();
        }
        return $this->documentProtection;
    }

    /**
     * @param Protection $documentProtection
     */
    public function setDocumentProtection($documentProtection)
    {
        $this->documentProtection = $documentProtection;
    }

    /**
     * @return ProofState
     */
    public function getProofState()
    {
        if ($this->proofState == null) {
            $this->proofState = new ProofState();
        }
        return $this->proofState;
    }

    /**
     * @param ProofState $proofState
     */
    public function setProofState($proofState)
    {
        $this->proofState = $proofState;
    }

    /**
     * Are spelling errors hidden
     *
     * @return boolean
     */
    public function hasHideSpellingErrors()
    {
        return $this->hideSpellingErrors;
    }

    /**
     * Hide spelling errors
     *
     * @param boolean $hideSpellingErrors
     */
    public function setHideSpellingErrors($hideSpellingErrors)
    {
        $this->hideSpellingErrors = $hideSpellingErrors === null ? true : $hideSpellingErrors;
    }

    /**
     * Are grammatical errors hidden
     *
     * @return boolean
     */
    public function hasHideGrammaticalErrors()
    {
        return $this->hideGrammaticalErrors;
    }

    /**
     * Hide grammatical errors
     *
     * @param boolean $hideGrammaticalErrors
     */
    public function setHideGrammaticalErrors($hideGrammaticalErrors)
    {
        $this->hideGrammaticalErrors = $hideGrammaticalErrors === null ? true : $hideGrammaticalErrors;
    }

    /**
     * @return boolean
     */
    public function hasEvenAndOddHeaders()
    {
        return $this->evenAndOddHeaders;
    }

    /**
     * @param boolean $evenAndOddHeaders
     */
    public function setEvenAndOddHeaders($evenAndOddHeaders)
    {
        $this->evenAndOddHeaders = $evenAndOddHeaders === null ? true : $evenAndOddHeaders;
    }

    /**
     * Get the Visibility of Annotation Types
     *
     * @return \PhpOffice\PhpWord\ComplexType\TrackChangesView
     */
    public function getRevisionView()
    {
        return $this->revisionView;
    }

    /**
     * Set the Visibility of Annotation Types
     *
     * @param TrackChangesView $trackChangesView
     */
    public function setRevisionView(TrackChangesView $trackChangesView = null)
    {
        $this->revisionView = $trackChangesView;
    }

    /**
     * @return boolean
     */
    public function hasTrackRevisions()
    {
        return $this->trackRevisions;
    }

    /**
     * @param boolean $trackRevisions
     */
    public function setTrackRevisions($trackRevisions)
    {
        $this->trackRevisions = $trackRevisions === null ? true : $trackRevisions;
    }

    /**
     * @return boolean
     */
    public function hasDoNotTrackMoves()
    {
        return $this->doNotTrackMoves;
    }

    /**
     * @param boolean $doNotTrackMoves
     */
    public function setDoNotTrackMoves($doNotTrackMoves)
    {
        $this->doNotTrackMoves = $doNotTrackMoves === null ? true : $doNotTrackMoves;
    }

    /**
     * @return boolean
     */
    public function hasDoNotTrackFormatting()
    {
        return $this->doNotTrackFormatting;
    }

    /**
     * @param boolean $doNotTrackFormatting
     */
    public function setDoNotTrackFormatting($doNotTrackFormatting)
    {
        $this->doNotTrackFormatting = $doNotTrackFormatting === null ? true : $doNotTrackFormatting;
    }

    /**
     * @return mixed
     */
    public function getZoom()
    {
        return $this->zoom;
    }

    /**
     * @param mixed $zoom
     */
    public function setZoom($zoom)
    {
        if (is_numeric($zoom)) {
            // zoom is a percentage
            $this->zoom = $zoom;
        } else {
            Zoom::validate($zoom);
            $this->zoom = $zoom;
        }
    }

    /**
     * Returns the Language
     * 
     * @return Language
     */
    public function getThemeFontLang()
    {
        return $this->themeFontLang;
    }

    /**
     * sets the Language for this document
     * 
     * @param Language $themeFontLang
     */
    public function setThemeFontLang($themeFontLang)
    {
        $this->themeFontLang = $themeFontLang;
    }

    /**
     * Returns the Radix Point for Field Code Evaluation
     *
     * @return string
     */
    public function getDecimalSymbol()
    {
        return $this->decimalSymbol;
    }

    /**
     * sets the Radix Point for Field Code Evaluation
     *
     * @param string $decimalSymbol
     */
    public function setDecimalSymbol($decimalSymbol)
    {
        $this->decimalSymbol = $decimalSymbol;
    }
}
