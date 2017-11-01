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
namespace PhpOffice\PhpWord\ComplexType;

use PhpOffice\PhpWord\SimpleType\NumberFormat;

/**
 * Footnote properties
 *
 * @see http://www.datypic.com/sc/ooxml/e-w_footnotePr-1.html
 */
final class FootnoteProperties
{

    const RESTART_NUMBER_CONTINUOUS = 'continuous';
    const RESTART_NUMBER_EACH_SECTION = 'eachSect';
    const RESTART_NUMBER_EACH_PAGE = 'eachPage';

    const POSITION_PAGE_BOTTOM = 'pageBottom';
    const POSITION_BENEATH_TEXT = 'beneathText';
    const POSITION_SECTION_END = 'sectEnd';
    const POSITION_DOC_END = 'docEnd';

    /**
     * Footnote Positioning Location
     *
     * @var string
     */
    private $pos;

    /**
     * Footnote Numbering Format w:numFmt, one of PhpOffice\PhpWord\SimpleType\NumberFormat
     *
     * @var string
     */
    private $numFmt;

    /**
     * Footnote and Endnote Numbering Starting Value
     *
     * @var double
     */
    private $numStart;

    /**
     * Footnote and Endnote Numbering Restart Location
     *
     * @var string
     */
    private $numRestart;

    /**
     * Get the Footnote Positioning Location
     *
     * @return string
     */
    public function getPos()
    {
        return $this->pos;
    }

    /**
     * Set the Footnote Positioning Location (pageBottom, beneathText, sectEnd, docEnd)
     *
     * @param string $pos
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setPos($pos)
    {
        $position = array(
            self::POSITION_PAGE_BOTTOM,
            self::POSITION_BENEATH_TEXT,
            self::POSITION_SECTION_END,
            self::POSITION_DOC_END
        );

        if (in_array($pos, $position)) {
            $this->pos = $pos;
        } else {
            throw new \InvalidArgumentException("Invalid value, on of " . implode(', ', $position) . " possible");
        }
        return $this;
    }

    /**
     * Get the Footnote Numbering Format
     *
     * @return string
     */
    public function getNumFmt()
    {
        return $this->numFmt;
    }

    /**
     * Set the Footnote Numbering Format
     *
     * @param string $numFmt One of NumberFormat
     * @return self
     */
    public function setNumFmt($numFmt)
    {
        NumberFormat::validate($numFmt);
        $this->numFmt = $numFmt;
        return $this;
    }

    /**
     * Get the Footnote Numbering Format
     *
     * @return double
     */
    public function getNumStart()
    {
        return $this->numStart;
    }

    /**
     * Set the Footnote Numbering Format
     *
     * @param double $numStart
     * @return self
     */
    public function setNumStart($numStart)
    {
        $this->numStart = $numStart;
        return $this;
    }

    /**
     * Get the Footnote and Endnote Numbering Starting Value
     *
     * @return string
     */
    public function getNumRestart()
    {
        return $this->numRestart;
    }

    /**
     * Set the Footnote and Endnote Numbering Starting Value (continuous, eachSect, eachPage)
     *
     * @param  string $numRestart
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setNumRestart($numRestart)
    {
        $restartNumbers = array(
            self::RESTART_NUMBER_CONTINUOUS,
            self::RESTART_NUMBER_EACH_SECTION,
            self::RESTART_NUMBER_EACH_PAGE
        );

        if (in_array($numRestart, $restartNumbers)) {
            $this->numRestart= $numRestart;
        } else {
            throw new \InvalidArgumentException("Invalid value, on of " . implode(', ', $restartNumbers) . " possible");
        }
        return $this;
    }
}
