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
 * Setting class
 *
 * @since 0.14.0
 * @link http://www.datypic.com/sc/ooxml/t-w_CT_Settings.html
 */
class Settings
{

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
     * Document Editing Restrictions
     * 
     * @var PhpOffice\PhpWord\Metadata\Protection
     */
    private $documentProtection;

    /**
     * Enables different header for odd and even pages.
     *
     * @var bool
     */
    private $evenAndOddHeaders = false;

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
}
