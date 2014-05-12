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

use PhpOffice\PhpWord\Exception\InvalidStyleException;
use PhpOffice\PhpWord\Shared\String;

/**
 * Paragraph style
 */
class Paragraph extends AbstractStyle
{
    /**
     * @const int One line height equals 240 twip
     */
    const LINE_HEIGHT = 240;

    /**
     * @const string Alignment http://www.schemacentral.com/sc/ooxml/t-w_ST_Jc.html
     */
    const ALIGN_LEFT = 'left'; // Align left
    const ALIGN_RIGHT = 'right'; // Align right
    const ALIGN_CENTER = 'center'; // Align center
    const ALIGN_BOTH = 'both'; // Align both
    const ALIGN_JUSTIFY = 'justify'; // Alias for align both

    /**
     * Aliases
     *
     * @var array
     */
    protected $aliases = array('line-height' => 'lineHeight');

    /**
     * Paragraph alignment
     *
     * @var string
     */
    private $align;

    /**
     * Text line height
     *
     * @var int
     */
    private $lineHeight;

    /**
     * Set of Custom Tab Stops
     *
     * @var \PhpOffice\PhpWord\Style\Tab[]
     */
    private $tabs = array();

    /**
     * Parent style
     *
     * @var string
     */
    private $basedOn = 'Normal';

    /**
     * Style for next paragraph
     *
     * @var string
     */
    private $next;

    /**
     * Allow first/last line to display on a separate page
     *
     * @var bool
     */
    private $widowControl = true;

    /**
     * Keep paragraph with next paragraph
     *
     * @var bool
     */
    private $keepNext = false;

    /**
     * Keep all lines on one page
     *
     * @var bool
     */
    private $keepLines = false;

    /**
     * Start paragraph on next page
     *
     * @var bool
     */
    private $pageBreakBefore = false;

    /**
     * Indentation
     *
     * @var \PhpOffice\PhpWord\Style\Indentation
     */
    private $indentation;

    /**
     * Spacing
     *
     * @var \PhpOffice\PhpWord\Style\Spacing
     */
    private $spacing;

    /**
     * Set Style value
     *
     * @param string $key
     * @param mixed $value
     */
    public function setStyleValue($key, $value)
    {
        $key = String::removeUnderscorePrefix($key);
        if ($key == 'indent' || $key == 'hanging') {
            $value = $value * 720;
        } elseif ($key == 'spacing') {
            $value += 240; // because line height of 1 matches 240 twips
        }

        return parent::setStyleValue($key, $value);
    }

    /**
     * Get Paragraph Alignment
     *
     * @return string
     */
    public function getAlign()
    {
        return $this->align;
    }

    /**
     * Set Paragraph Alignment
     *
     * @param string $value
     * @return self
     */
    public function setAlign($value = null)
    {
        if (strtolower($value) == self::ALIGN_JUSTIFY) {
            $value = self::ALIGN_BOTH;
        }
        $enum = array(self::ALIGN_LEFT, self::ALIGN_RIGHT, self::ALIGN_CENTER, self::ALIGN_BOTH, self::ALIGN_JUSTIFY);
        $this->align = $this->setEnumVal($value, $enum, $this->align);

        return $this;
    }

    /**
     * Get space before paragraph
     *
     * @return integer
     */
    public function getSpaceBefore()
    {
        if (!is_null($this->spacing)) {
            return $this->spacing->getBefore();
        }
    }

    /**
     * Set space before paragraph
     *
     * @param int $value
     * @return self
     */
    public function setSpaceBefore($value = null)
    {
        return $this->setSpace(array('before' => $value));
    }

    /**
     * Get space after paragraph
     *
     * @return integer
     */
    public function getSpaceAfter()
    {
        if (!is_null($this->spacing)) {
            return $this->spacing->getAfter();
        }
    }

    /**
     * Set space after paragraph
     *
     * @param int $value
     * @return self
     */
    public function setSpaceAfter($value = null)
    {
        return $this->setSpace(array('after' => $value));
    }

    /**
     * Get spacing between lines
     *
     * @return int
     */
    public function getSpacing()
    {
        if (!is_null($this->spacing)) {
            return $this->spacing->getLine();
        }
    }

    /**
     * Set spacing between lines
     *
     * @param int $value
     * @return self
     */
    public function setSpacing($value = null)
    {
        return $this->setSpace(array('line' => $value));
    }

    /**
     * Get line height
     *
     * @return int|float
     */
    public function getLineHeight()
    {
        return $this->lineHeight;
    }

    /**
     * Set the line height
     *
     * @param int|float|string $lineHeight
     * @return $this
     * @throws \PhpOffice\PhpWord\Exception\InvalidStyleException
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
     * Get indentation
     *
     * @return int
     */
    public function getIndent()
    {
        if (!is_null($this->indentation)) {
            return $this->indentation->getLeft();
        }
    }

    /**
     * Set indentation
     *
     * @param int $value
     * @return self
     */
    public function setIndent($value = null)
    {
        return $this->setIndentation(array('left' => $value));
    }

    /**
     * Get hanging
     *
     * @return int
     */
    public function getHanging()
    {
        if (!is_null($this->indentation)) {
            return $this->indentation->getHanging();
        }
    }

    /**
     * Set hanging
     *
     * @param int $value
     * @return self
     */
    public function setHanging($value = null)
    {
        return $this->setIndentation(array('hanging' => $value));
    }

    /**
     * Get tabs
     *
     * @return \PhpOffice\PhpWord\Style\Tab[]
     */
    public function getTabs()
    {
        return $this->tabs;
    }

    /**
     * Set tabs
     *
     * @param array $value
     * @return self
     */
    public function setTabs($value = null)
    {
        if (is_array($value)) {
            $this->tabs = $value;
        }

        return $this;
    }

    /**
     * Get parent style ID
     *
     * @return string
     */
    public function getBasedOn()
    {
        return $this->basedOn;
    }

    /**
     * Set parent style ID
     *
     * @param string $value
     * @return self
     */
    public function setBasedOn($value = 'Normal')
    {
        $this->basedOn = $value;

        return $this;
    }

    /**
     * Get style for next paragraph
     *
     * @return string
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * Set style for next paragraph
     *
     * @param string $value
     * @return self
     */
    public function setNext($value = null)
    {
        $this->next = $value;

        return $this;
    }

    /**
     * Get allow first/last line to display on a separate page setting
     *
     * @return bool
     */
    public function hasWidowControl()
    {
        return $this->widowControl;
    }

    /**
     * Set keep paragraph with next paragraph setting
     *
     * @param bool $value
     * @return self
     */
    public function setWidowControl($value = true)
    {
        $this->widowControl = $this->setBoolVal($value, $this->widowControl);

        return $this;
    }

    /**
     * Get keep paragraph with next paragraph setting
     *
     * @return bool
     */
    public function isKeepNext()
    {
        return $this->keepNext;
    }

    /**
     * Set keep paragraph with next paragraph setting
     *
     * @param bool $value
     * @return self
     */
    public function setKeepNext($value = false)
    {
        $this->keepNext = $this->setBoolVal($value, $this->keepNext);

        return $this;
    }

    /**
     * Get keep all lines on one page setting
     *
     * @return bool
     */
    public function isKeepLines()
    {
        return $this->keepLines;
    }

    /**
     * Set keep all lines on one page setting
     *
     * @param bool $value
     * @return self
     */
    public function setKeepLines($value = false)
    {
        $this->keepLines = $this->setBoolVal($value, $this->keepLines);

        return $this;
    }

    /**
     * Get start paragraph on next page setting
     *
     * @return bool
     */
    public function hasPageBreakBefore()
    {
        return $this->pageBreakBefore;
    }

    /**
     * Set start paragraph on next page setting
     *
     * @param bool $value
     * @return self
     */
    public function setPageBreakBefore($value = false)
    {
        $this->pageBreakBefore = $this->setBoolVal($value, $this->pageBreakBefore);

        return $this;
    }

    /**
     * Get shading
     *
     * @return \PhpOffice\PhpWord\Style\Indentation
     */
    public function getIndentation()
    {
        return $this->indentation;
    }

    /**
     * Set shading
     *
     * @param mixed $value
     * @return self
     */
    public function setIndentation($value = null)
    {
        $this->setObjectVal($value, 'Indentation', $this->indentation);

        return $this;
    }

    /**
     * Get shading
     *
     * @return \PhpOffice\PhpWord\Style\Spacing
     * @todo Rename to getSpacing in 1.0
     */
    public function getSpace()
    {
        return $this->spacing;
    }

    /**
     * Set shading
     *
     * @param mixed $value
     * @return self
     * @todo Rename to setSpacing in 1.0
     */
    public function setSpace($value = null)
    {
        $this->setObjectVal($value, 'Spacing', $this->spacing);

        return $this;
    }

    /**
     * Get allow first/last line to display on a separate page setting
     *
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function getWidowControl()
    {
        return $this->hasWidowControl();
    }

    /**
     * Get keep paragraph with next paragraph setting
     *
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function getKeepNext()
    {
        return $this->isKeepNext();
    }

    /**
     * Get keep all lines on one page setting
     *
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function getKeepLines()
    {
        return $this->isKeepLines();
    }

    /**
     * Get start paragraph on next page setting
     *
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function getPageBreakBefore()
    {
        return $this->hasPageBreakBefore();
    }
}
