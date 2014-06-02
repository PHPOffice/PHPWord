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
 * Outline defines the line/border of the object
 *
 * @link http://www.schemacentral.com/sc/ooxml/t-v_CT_Stroke.html
 * @since 0.12.0
 */
class Outline extends AbstractStyle
{
    /**
     * Line style constants
     *
     * @link http://www.schemacentral.com/sc/ooxml/t-v_ST_StrokeLineStyle.html
     * @const string
     */
    const LINE_SINGLE = 'single';
    const LINE_THIN_THIN = 'thinThin';
    const LINE_THIN_THICK = 'thinThick';
    const LINE_THICK_THIN = 'thickThin';
    const LINE_THICK_BETWEEN_THIN = 'thickBetweenThin';

    /**
     * Arrowhead type constants
     *
     * @link http://www.schemacentral.com/sc/ooxml/t-v_ST_StrokeArrowType.html
     * @const string
     */
    const ARROW_NONE = 'none';
    const ARROW_BLOCK = 'block';
    const ARROW_CLASSIC = 'classic';
    const ARROW_OVAL = 'oval';
    const ARROW_DIAMOND = 'diamond';
    const ARROW_OPEN = 'open';

    /**
     * Outline weight
     *
     * @var int|float
     */
    private $weight;

    /**
     * Outline color
     *
     * @var string
     */
    private $color;

    /**
     * Dash type
     *
     * @var string
     */
    private $dash;

    /**
     * Line style
     *
     * @var string
     */
    private $line;

    /**
     * Start arrow type
     *
     * @var string
     */
    private $startArrow;

    /**
     * End arrow type
     *
     * @var string
     */
    private $endArrow;

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
     * Get weight
     *
     * @return int|float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set weight
     *
     * @param int|float $value
     * @return self
     */
    public function setWeight($value = null)
    {
        $this->weight = $this->setNumericVal($value, null);

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set color
     *
     * @param string $value
     * @return self
     */
    public function setColor($value = null)
    {
        $this->color = $value;

        return $this;
    }

    /**
     * Get dash type
     *
     * @return string
     */
    public function getDash()
    {
        return $this->dash;
    }

    /**
     * Set dash type
     *
     * @param string $value
     * @return self
     */
    public function setDash($value = null)
    {
        $this->dash = $value;

        return $this;
    }

    /**
     * Get line style
     *
     * @return string
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Set line style
     *
     * @param string $value
     * @return self
     */
    public function setLine($value = null)
    {
        $enum = array(self::LINE_SINGLE, self::LINE_THIN_THIN, self::LINE_THIN_THICK,
            self::LINE_THICK_THIN, self::LINE_THICK_BETWEEN_THIN);
        $this->line = $this->setEnumVal($value, $enum, null);

        return $this;
    }

    /**
     * Get startArrow
     *
     * @return string
     */
    public function getStartArrow()
    {
        return $this->startArrow;
    }

    /**
     * Set pattern
     *
     * @param string $value
     * @return self
     */
    public function setStartArrow($value = null)
    {
        $enum = array(self::ARROW_NONE, self::ARROW_BLOCK, self::ARROW_CLASSIC,
            self::ARROW_OVAL, self::ARROW_DIAMOND, self::ARROW_OPEN);
        $this->startArrow = $this->setEnumVal($value, $enum, null);

        return $this;
    }

    /**
     * Get endArrow
     *
     * @return string
     */
    public function getEndArrow()
    {
        return $this->endArrow;
    }

    /**
     * Set pattern
     *
     * @param string $value
     * @return self
     */
    public function setEndArrow($value = null)
    {
        $enum = array(self::ARROW_NONE, self::ARROW_BLOCK, self::ARROW_CLASSIC,
            self::ARROW_OVAL, self::ARROW_DIAMOND, self::ARROW_OPEN);
        $this->endArrow = $this->setEnumVal($value, $enum, null);

        return $this;
    }
}
