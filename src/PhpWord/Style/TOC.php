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

namespace PhpOffice\PhpWord\Style;

/**
 * TOC style
 */
class TOC extends Tab
{
    /**
     * Tab leader types for backward compatibility
     *
     * @const string
     * @deprecated 0.11.0
     */
    const TABLEADER_DOT = self::TAB_LEADER_DOT;
    const TABLEADER_UNDERSCORE = self::TAB_LEADER_UNDERSCORE;
    const TABLEADER_LINE = self::TAB_LEADER_HYPHEN;
    const TABLEADER_NONE = self::TAB_LEADER_NONE;

    /**
     * Indent
     *
     * @var int|float (twip)
     */
    private $indent = 200;

    /**
     * Create a new TOC Style
     */
    public function __construct()
    {
        parent::__construct(self::TAB_STOP_RIGHT, 9062, self::TAB_LEADER_DOT);
    }

    /**
     * Get Tab Position
     *
     * @return int|float
     */
    public function getTabPos()
    {
        return $this->getPosition();
    }

    /**
     * Set Tab Position
     *
     * @param int|float $value
     * @return self
     */
    public function setTabPos($value)
    {
        return $this->setPosition($value);
    }

    /**
     * Get Tab Leader
     *
     * @return string
     */
    public function getTabLeader()
    {
        return $this->getLeader();
    }

    /**
     * Set Tab Leader
     *
     * @param string $value
     * @return self
     */
    public function setTabLeader($value = self::TAB_LEADER_DOT)
    {
        return $this->setLeader($value);
    }

    /**
     * Get Indent
     *
     * @return int|float
     */
    public function getIndent()
    {
        return $this->indent;
    }

    /**
     * Set Indent
     *
     * @param int|float $value
     * @return self
     */
    public function setIndent($value)
    {
        $this->indent = $this->setNumericVal($value, $this->indent);

        return $this;
    }
}
