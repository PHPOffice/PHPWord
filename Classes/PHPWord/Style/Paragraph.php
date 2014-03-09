<?php
/**
 * PHPWord
 *
 * Copyright (c) 2014 PHPWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.7.0
 */

use PHPWord\Exceptions\InvalidStyleException;

/**
 * PHPWord_Style_Paragraph
 */
class PHPWord_Style_Paragraph
{
    const LINE_HEIGHT = 240;

    /*
     * Text line height
     *
     * @var int
     */
    private $lineHeight;

    /**
     * Paragraph alignment
     *
     * @var string
     */
    private $_align;

    /**
     * Space before Paragraph
     *
     * @var int
     */
    private $_spaceBefore;

    /**
     * Space after Paragraph
     *
     * @var int
     */
    private $_spaceAfter;

    /**
     * Spacing between breaks
     *
     * @var int
     */
    private $_spacing;

    /**
     * Set of Custom Tab Stops
     *
     * @var array
     */
    private $_tabs;

    /**
     * Indent by how much
     *
     * @var int
     */
    private $_indent;

    /**
     * Set Style value
     *
     * @param string $key
     * @param mixed $value
     */
    public function setStyleValue($key, $value)
    {
        if ($key == '_indent') {
            $value = (int)$value * 720; // 720 twips per indent
            $this->$key = $value;
        } elseif ($key == '_spacing') {
            $value += 240; // because line height of 1 matches 240 twips
            $this->$key = $value;
        } elseif ($key === '_tabs') {
            $value = new PHPWord_Style_Tabs($value);
            $this->$key = $value;
        } elseif ($key === 'line-height') {
            $this->setLineHeight($value);
        } else {
            $this->$key = $value;
        }
    }

    /**
     * Get Paragraph Alignment
     *
     * @return string
     */
    public function getAlign()
    {
        return $this->_align;
    }

    /**
     * Set Paragraph Alignment
     *
     * @param string $pValue
     * @return PHPWord_Style_Paragraph
     */
    public function setAlign($pValue = null)
    {
        if (strtolower($pValue) == 'justify') {
            // justify becames both
            $pValue = 'both';
        }
        $this->_align = $pValue;
        return $this;
    }

    /**
     * Get Space before Paragraph
     *
     * @return string
     */
    public function getSpaceBefore()
    {
        return $this->_spaceBefore;
    }

    /**
     * Set Space before Paragraph
     *
     * @param int $pValue
     * @return PHPWord_Style_Paragraph
     */
    public function setSpaceBefore($pValue = null)
    {
        $this->_spaceBefore = $pValue;
        return $this;
    }

    /**
     * Get Space after Paragraph
     *
     * @return string
     */
    public function getSpaceAfter()
    {
        return $this->_spaceAfter;
    }

    /**
     * Set Space after Paragraph
     *
     * @param int $pValue
     * @return PHPWord_Style_Paragraph
     */
    public function setSpaceAfter($pValue = null)
    {
        $this->_spaceAfter = $pValue;
        return $this;
    }

    /**
     * Get Spacing between breaks
     *
     * @return int
     */
    public function getSpacing()
    {
        return $this->_spacing;
    }

    /**
     * Set Spacing between breaks
     *
     * @param int $pValue
     * @return PHPWord_Style_Paragraph
     */
    public function setSpacing($pValue = null)
    {
        $this->_spacing = $pValue;
        return $this;
    }

    /**
     * Get indentation
     *
     * @return int
     */
    public function getIndent()
    {
        return $this->_indent;
    }

    /**
     * Set indentation
     *
     * @param int $pValue
     * @return PHPWord_Style_Paragraph
     */
    public function setIndent($pValue = null)
    {
        $this->_indent = $pValue;
        return $this;
    }

    /**
     * Get tabs
     *
     * @return PHPWord_Style_Tabs
     */
    public function getTabs()
    {
        return $this->_tabs;
    }

    /**
     * Set the line height
     *
     * @param int|float|string $lineHeight
     * @return $this
     * @throws \PHPWord\Exceptions\InvalidStyleException
     */
    public function setLineHeight($lineHeight)
    {
        if (is_string($lineHeight)) {
            $lineHeight = floatval(preg_replace('/[^0-9\.\,]/', '', $lineHeight));
        }

        if ((!is_integer($lineHeight) && !is_float($lineHeight)) || !$lineHeight) {
            throw new InvalidStyleException('Line height must be a valid number');
        }

        $this->lineHeight = $lineHeight;
        $this->setSpacing($lineHeight * self::LINE_HEIGHT);
        return $this;
    }

    /**
     * @return int
     */
    public function getLineHeight()
    {
        return $this->lineHeight;
    }
}