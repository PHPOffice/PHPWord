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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

/**
 * Shape style
 *
 * @since 0.12.0
 * @todo Skew http://www.schemacentral.com/sc/ooxml/t-o_CT_Skew.html
 */
class Shape extends AbstractStyle
{
    /**
     * Points
     *
     * - Arc: startAngle endAngle; 0 = top center, moving clockwise
     * - Curve: from-x1,from-y1 to-x2,to-y2 control1-x,control1-y control2-x,control2-y
     * - Line: from-x1,from-y1 to-x2,to-y2
     * - Polyline: x1,y1 x2,y2 ...
     * - Rect and oval: Not applicable
     *
     * @var string
     */
    private $points;

    /**
     * Roundness measure of corners; 0 = straightest (rectangular); 1 = roundest (circle/oval)
     *
     * Only for rect
     *
     * @var int|float
     */
    private $roundness;

    /**
     * Frame
     *
     * @var \PhpOffice\PhpWord\Style\Frame
     */
    private $frame;

    /**
     * Fill
     *
     * @var \PhpOffice\PhpWord\Style\Fill
     */
    private $fill;

    /**
     * Outline
     *
     * @var \PhpOffice\PhpWord\Style\Outline
     */
    private $outline;

    /**
     * Shadow
     *
     * @var \PhpOffice\PhpWord\Style\Shadow
     */
    private $shadow;

    /**
     * 3D extrusion
     *
     * @var \PhpOffice\PhpWord\Style\Extrusion
     */
    private $extrusion;

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
     * Get points
     *
     * @return string
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set points
     *
     * @param string $value
     * @return self
     */
    public function setPoints($value = null)
    {
        $this->points = $value;

        return $this;
    }

    /**
     * Get roundness
     *
     * @return int|float
     */
    public function getRoundness()
    {
        return $this->roundness;
    }

    /**
     * Set roundness
     *
     * @param int|float $value
     * @return self
     */
    public function setRoundness($value = null)
    {
        $this->roundness = $this->setNumericVal($value, null);

        return $this;
    }

    /**
     * Get frame
     *
     * @return \PhpOffice\PhpWord\Style\Frame
     */
    public function getFrame()
    {
        return $this->frame;
    }

    /**
     * Set frame
     *
     * @param mixed $value
     * @return self
     */
    public function setFrame($value = null)
    {
        $this->setObjectVal($value, 'Frame', $this->frame);

        return $this;
    }

    /**
     * Get fill
     *
     * @return \PhpOffice\PhpWord\Style\Fill
     */
    public function getFill()
    {
        return $this->fill;
    }

    /**
     * Set fill
     *
     * @param mixed $value
     * @return self
     */
    public function setFill($value = null)
    {
        $this->setObjectVal($value, 'Fill', $this->fill);

        return $this;
    }

    /**
     * Get outline
     *
     * @return \PhpOffice\PhpWord\Style\Outline
     */
    public function getOutline()
    {
        return $this->outline;
    }

    /**
     * Set outline
     *
     * @param mixed $value
     * @return self
     */
    public function setOutline($value = null)
    {
        $this->setObjectVal($value, 'Outline', $this->outline);

        return $this;
    }

    /**
     * Get shadow
     *
     * @return \PhpOffice\PhpWord\Style\Shadow
     */
    public function getShadow()
    {
        return $this->shadow;
    }

    /**
     * Set shadow
     *
     * @param mixed $value
     * @return self
     */
    public function setShadow($value = null)
    {
        $this->setObjectVal($value, 'Shadow', $this->shadow);

        return $this;
    }

    /**
     * Get 3D extrusion
     *
     * @return \PhpOffice\PhpWord\Style\Extrusion
     */
    public function getExtrusion()
    {
        return $this->extrusion;
    }

    /**
     * Set 3D extrusion
     *
     * @param mixed $value
     * @return self
     */
    public function setExtrusion($value = null)
    {
        $this->setObjectVal($value, 'Extrusion', $this->extrusion);

        return $this;
    }
}
