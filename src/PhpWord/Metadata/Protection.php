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
 * Document protection class
 *
 * @since 0.12.0
 * @link http://www.datypic.com/sc/ooxml/t-w_CT_DocProtect.html
 */
class Protection
{
    /**
     * Editing restriction none|readOnly|comments|trackedChanges|forms
     *
     * @var string
     * @link http://www.datypic.com/sc/ooxml/a-w_edit-1.html
     */
    private $editing;

    /**
     * Hashed password
     *
     * @var string
     */
    private $password = '';

    /**
     * Number of hashing iterations
     *
     * @var int
     */
    private $spinCount = 100000;

    /**
     * Algorithm-SID (see to \PhpOffice\PhpWord\Writer\Word2007\Part\Settings::$algorithmMapping)
     *
     * @var int
     */
    private $mswordAlgorithmSid = 4;

    /**
     * Hashed salt
     *
     * @var string
     */
    private $salt = '';

    /**
     * Create a new instance
     *
     * @param string $editing
     */
    public function __construct($editing = null)
    {
        $this->setEditing($editing);
    }

    /**
     * Get editing protection
     *
     * @return string
     */
    public function getEditing()
    {
        return $this->editing;
    }

    /**
     * Set editing protection
     *
     * @param string $editing
     * @return self
     */
    public function setEditing($editing = null)
    {
        $this->editing = $editing;

        return $this;
    }

    /**
     * Get password hash
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get count for hash iterations
     *
     * @return int
     */
    public function getSpinCount()
    {
        return $this->spinCount;
    }

    /**
     * Set count for hash iterations
     *
     * @param $spinCount
     * @return self
     */
    public function setSpinCount($spinCount)
    {
        $this->spinCount = $spinCount;

        return $this;
    }

    /**
     * Get algorithm-sid
     *
     * @return int
     */
    public function getMswordAlgorithmSid()
    {
        return $this->mswordAlgorithmSid;
    }

    /**
     * Set algorithm-sid (see \PhpOffice\PhpWord\Writer\Word2007\Part\Settings::$algorithmMapping)
     *
     * @param $mswordAlgorithmSid
     * @return self
     */
    public function setMswordAlgorithmSid($mswordAlgorithmSid)
    {
        $this->mswordAlgorithmSid = $mswordAlgorithmSid;

        return $this;
    }

    /**
     * Get salt hash
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set salt hash
     *
     * @param $salt
     * @return self
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }
}
