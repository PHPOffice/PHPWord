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
namespace PhpOffice\PhpWord\SimpleType;

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

    const NUMBER_FORMAT_DECIMAL = 'decimal';
    const NUMBER_FORMAT_UPPER_ROMAN = 'upperRoman';
    const NUMBER_FORMAT_LOWER_ROMAN = 'lowerRoman';
    const NUMBER_FORMAT_UPPER_LETTER = 'upperLetter';
    const NUMBER_FORMAT_LOWER_LETTER = 'lowerLetter';
    const NUMBER_FORMAT_ORDINAL = 'ordinal';
    const NUMBER_FORMAT_CARDINAL_TEXT = 'cardinalText';
    const NUMBER_FORMAT_ORDINAL_TEXT = 'ordinalText';
    const NUMBER_FORMAT_NONE = 'none';
    const NUMBER_FORMAT_BULLET = 'bullet';

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
     * Footnote Numbering Format
     *
     * @var string
     */
    private $numFmt;

    /**
     * Footnote and Endnote Numbering Starting Value
     *
     * @var decimal
     */
    private $numStart;

    /**
     * Footnote and Endnote Numbering Restart Location
     *
     * @var string
     */
    private $numRestart;

    public function getPos()
    {
        return $this->pos;
    }

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
    }

    public function getNumFmt()
    {
        return $this->numFmt;
    }

    public function setNumFmt($numFmt)
    {
        $numberFormat = array(
            self::NUMBER_FORMAT_DECIMAL,
            self::NUMBER_FORMAT_UPPER_ROMAN,
            self::NUMBER_FORMAT_LOWER_ROMAN,
            self::NUMBER_FORMAT_UPPER_LETTER,
            self::NUMBER_FORMAT_LOWER_LETTER,
            self::NUMBER_FORMAT_ORDINAL,
            self::NUMBER_FORMAT_CARDINAL_TEXT,
            self::NUMBER_FORMAT_ORDINAL_TEXT,
            self::NUMBER_FORMAT_NONE,
            self::NUMBER_FORMAT_BULLET
        );

        if (in_array($numFmt, $numberFormat)) {
            $this->numFmt = $numFmt;
        } else {
            throw new \InvalidArgumentException("Invalid value, on of " . implode(', ', $numberFormat) . " possible");
        }
    }

    public function getNumStart()
    {
        return $this->numStart;
    }

    public function setNumStart($numStart)
    {
        $this->numStart = $numStart;
    }

    public function getNumRestart()
    {
        return $this->numRestart;
    }

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
    }
}
