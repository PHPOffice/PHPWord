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

namespace PhpOffice\PhpWord\Style;

/**
 * Line numbering style
 *
 * @link http://www.schemacentral.com/sc/ooxml/t-w_CT_LineNumber.html
 * @since 0.10.0
 */
class LineNumbering extends AbstractStyle
{
    /** @const string Line numbering restart setting http://www.schemacentral.com/sc/ooxml/a-w_restart-1.html */
    const LINE_NUMBERING_CONTINUOUS  = 'continuous';
    const LINE_NUMBERING_NEW_PAGE    = 'newPage';
    const LINE_NUMBERING_NEW_SECTION = 'newSection';

    /**
     * Line numbering starting value
     *
     * @var int
     */
    private $start = 1;

    /**
     * Line number increments
     *
     * @var int
     */
    private $increment = 1;

    /**
     * Distance between text and line numbering in twip
     *
     * @var int|float
     */
    private $distance;

    /**
     * Line numbering restart setting continuous|newPage|newSection
     *
     * @var string
     * @link http://www.schemacentral.com/sc/ooxml/a-w_restart-1.html
     */
    private $restart;

    /**
     * Create a new instance
     *
     * @param array $style
     */
    public function __construct($style = array())
    {
        $this->setStyleByArray($style);
    }

    /**
     * Get start
     *
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set start
     *
     * @param int $value
     * @return self
     */
    public function setStart($value = null)
    {
        $this->start = $this->setIntVal($value, $this->start);

        return $this;
    }

    /**
     * Get increment
     *
     * @return int
     */
    public function getIncrement()
    {
        return $this->increment;
    }

    /**
     * Set increment
     *
     * @param int $value
     * @return self
     */
    public function setIncrement($value = null)
    {
        $this->increment = $this->setIntVal($value, $this->increment);

        return $this;
    }

    /**
     * Get distance
     *
     * @return int|float
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Set distance
     *
     * @param int|float $value
     * @return self
     */
    public function setDistance($value = null)
    {
        $this->distance = $this->setNumericVal($value, $this->distance);

        return $this;
    }

    /**
     * Get restart
     *
     * @return string
     */
    public function getRestart()
    {
        return $this->restart;
    }

    /**
     * Set distance
     *
     * @param string $value
     * @return self
     */
    public function setRestart($value = null)
    {
        $enum = array(self::LINE_NUMBERING_CONTINUOUS, self::LINE_NUMBERING_NEW_PAGE, self::LINE_NUMBERING_NEW_SECTION);
        $this->restart = $this->setEnumVal($value, $enum, $this->restart);

        return $this;
    }
}
