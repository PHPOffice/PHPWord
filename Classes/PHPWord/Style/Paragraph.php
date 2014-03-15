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
 * @version    0.8.0
 */

use PhpOffice\PhpWord\Exceptions\InvalidStyleException;

/**
 * PHPWord_Style_Paragraph
 */
class PHPWord_Style_Paragraph
{
    const LINE_HEIGHT = 240;

    /**
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
     * Hanging by how much
     *
     * @var int
     */
    private $_hanging;

    /**
     * Parent style
     *
     * @var string
     */
    private $_basedOn = 'Normal';

    /**
     * Style for next paragraph
     *
     * @var string
     */
    private $_next;

    /**
     * Allow first/last line to display on a separate page
     *
     * @var bool
     */
    private $_widowControl = true;

    /**
     * Keep paragraph with next paragraph
     *
     * @var bool
     */
    private $_keepNext = false;

    /**
     * Keep all lines on one page
     *
     * @var bool
     */
    private $_keepLines = false;

    /**
     * Start paragraph on next page
     *
     * @var bool
     */
    private $_pageBreakBefore = false;

    /**
     * @param array $style
     * @return $this
     */
    public function setArrayStyle(array $style = array())
    {
        foreach ($style as $key => $value) {
            if ($key === 'line-height') {
                null;
            } elseif (substr($key, 0, 1) !== '_') {
                $key = '_' . $key;
            }
            $this->setStyleValue($key, $value);
        }

        return $this;
    }

    /**
     * Set Style value
     *
     * @param string $key
     * @param mixed $value
     */
    public function setStyleValue($key, $value)
    {
        if ($key == '_indent' || $key == '_hanging') {
            $value = $value * 720;
        } elseif ($key == '_spacing') {
            $value += 240; // because line height of 1 matches 240 twips
        } elseif ($key === 'line-height') {
            $this->setLineHeight($value);
            return;
        }
        $this->$key = $value;
        $method = 'set' . substr($key, 1);
        if (method_exists($this, $method)) {
            $this->$method($value);
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
     * Get hanging
     *
     * @return int
     */
    public function getHanging()
    {
        return $this->_hanging;
    }

    /**
     * Set hanging
     *
     * @param int $pValue
     * @return PHPWord_Style_Paragraph
     */
    public function setHanging($pValue = null)
    {
        $this->_hanging = $pValue;
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

    /*
     * Set tabs
     *
     * @param   array   $pValue
     * @return  PHPWord_Style_Paragraph
     */
    public function setTabs($pValue = null)
    {
        if (is_array($pValue)) {
            $this->_tabs = new PHPWord_Style_Tabs($pValue);
        }
        return $this;
    }

    /**
     * Get parent style ID
     *
     * @return  string
     */
    public function getBasedOn()
    {
        return $this->_basedOn;
    }

    /**
     * Set parent style ID
     *
     * @param   string $pValue
     * @return  PHPWord_Style_Paragraph
     */
    public function setBasedOn($pValue = 'Normal')
    {
        $this->_basedOn = $pValue;
        return $this;
    }

    /**
     * Get style for next paragraph
     *
     * @return string
     */
    public function getNext()
    {
        return $this->_next;
    }

    /**
     * Set style for next paragraph
     *
     * @param   string $pValue
     * @return  PHPWord_Style_Paragraph
     */
    public function setNext($pValue = null)
    {
        $this->_next = $pValue;
        return $this;
    }

    /**
     * Get allow first/last line to display on a separate page setting
     *
     * @return  bool
     */
    public function getWidowControl()
    {
        return $this->_widowControl;
    }

    /**
     * Set keep paragraph with next paragraph setting
     *
     * @param   bool $pValue
     * @return  PHPWord_Style_Paragraph
     */
    public function setWidowControl($pValue = true)
    {
        if (!is_bool($pValue)) {
            $pValue = true;
        }
        $this->_widowControl = $pValue;
        return $this;
    }

    /**
     * Get keep paragraph with next paragraph setting
     *
     * @return  bool
     */
    public function getKeepNext()
    {
        return $this->_keepNext;
    }

    /**
     * Set keep paragraph with next paragraph setting
     *
     * @param   bool $pValue
     * @return  PHPWord_Style_Paragraph
     */
    public function setKeepNext($pValue = false)
    {
        if (!is_bool($pValue)) {
            $pValue = false;
        }
        $this->_keepNext = $pValue;
        return $this;
    }

    /**
     * Get keep all lines on one page setting
     *
     * @return  bool
     */
    public function getKeepLines()
    {
        return $this->_keepLines;
    }

    /**
     * Set keep all lines on one page setting
     *
     * @param   bool $pValue
     * @return  PHPWord_Style_Paragraph
     */
    public function setKeepLines($pValue = false)
    {
        if (!is_bool($pValue)) {
            $pValue = false;
        }
        $this->_keepLines = $pValue;
        return $this;
    }

    /**
     * Get start paragraph on next page setting
     *
     * @return bool
     */
    public function getPageBreakBefore()
    {
        return $this->_pageBreakBefore;
    }

    /**
     * Set start paragraph on next page setting
     *
     * @param   bool $pValue
     * @return  PHPWord_Style_Paragraph
     */
    public function setPageBreakBefore($pValue = false)
    {
        if (!is_bool($pValue)) {
            $pValue = false;
        }
        $this->_pageBreakBefore = $pValue;
        return $this;
    }

    /**
     * Set the line height
     *
     * @param int|float|string $lineHeight
     * @return $this
     * @throws InvalidStyleException
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
     * @return int|float
     */
    public function getLineHeight()
    {
        return $this->lineHeight;
    }
}
