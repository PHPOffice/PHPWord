<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\Exception\InvalidStyleException;
use PhpOffice\PhpWord\Shared\String;
use PhpOffice\PhpWord\Style\Indentation;
use PhpOffice\PhpWord\Style\Spacing;

/**
 * Paragraph style
 */
class Paragraph extends AbstractStyle
{
    const LINE_HEIGHT = 240;

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
     * Set style by array
     *
     * @param array $style
     * @return $this
     */
    public function setArrayStyle(array $style = array())
    {
        foreach ($style as $key => $value) {
            if ($key === 'line-height') {
                null;
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
        $key = String::removeUnderscorePrefix($key);
        if ($key == 'indent' || $key == 'hanging') {
            $value = $value * 720;
        } elseif ($key == 'spacing') {
            $value += 240; // because line height of 1 matches 240 twips
        } elseif ($key === 'line-height') {
            $this->setLineHeight($value);
            return;
        }
        $method = 'set' . $key;
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
        if (strtolower($value) == 'justify') {
            // justify becames both
            $value = 'both';
        }
        $this->align = $value;
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
     * @return  string
     */
    public function getBasedOn()
    {
        return $this->basedOn;
    }

    /**
     * Set parent style ID
     *
     * @param   string $value
     * @return  self
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
     * @param   string $value
     * @return  self
     */
    public function setNext($value = null)
    {
        $this->next = $value;
        return $this;
    }

    /**
     * Get allow first/last line to display on a separate page setting
     *
     * @return  bool
     */
    public function getWidowControl()
    {
        return $this->widowControl;
    }

    /**
     * Set keep paragraph with next paragraph setting
     *
     * @param   bool $value
     * @return  self
     */
    public function setWidowControl($value = true)
    {
        if (!is_bool($value)) {
            $value = true;
        }
        $this->widowControl = $value;
        return $this;
    }

    /**
     * Get keep paragraph with next paragraph setting
     *
     * @return  bool
     */
    public function getKeepNext()
    {
        return $this->keepNext;
    }

    /**
     * Set keep paragraph with next paragraph setting
     *
     * @param   bool $value
     * @return  self
     */
    public function setKeepNext($value = false)
    {
        if (!is_bool($value)) {
            $value = false;
        }
        $this->keepNext = $value;
        return $this;
    }

    /**
     * Get keep all lines on one page setting
     *
     * @return  bool
     */
    public function getKeepLines()
    {
        return $this->keepLines;
    }

    /**
     * Set keep all lines on one page setting
     *
     * @param   bool $value
     * @return  self
     */
    public function setKeepLines($value = false)
    {
        if (!is_bool($value)) {
            $value = false;
        }
        $this->keepLines = $value;
        return $this;
    }

    /**
     * Get start paragraph on next page setting
     *
     * @return bool
     */
    public function getPageBreakBefore()
    {
        return $this->pageBreakBefore;
    }

    /**
     * Set start paragraph on next page setting
     *
     * @param   bool $value
     * @return  self
     */
    public function setPageBreakBefore($value = false)
    {
        if (!is_bool($value)) {
            $value = false;
        }
        $this->pageBreakBefore = $value;
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
     * @param array $value
     * @return self
     */
    public function setIndentation($value = null)
    {
        if (is_array($value)) {
            if (!$this->indentation instanceof Indentation) {
                $this->indentation = new Indentation();
            }
            $this->indentation->setStyleByArray($value);
        } else {
            $this->indentation = null;
        }

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
     * @param array $value
     * @return self
     * @todo Rename to setSpacing in 1.0
     */
    public function setSpace($value = null)
    {
        if (is_array($value)) {
            if (!$this->spacing instanceof Spacing) {
                $this->spacing = new Spacing();
            }
            $this->spacing->setStyleByArray($value);
        } else {
            $this->spacing = null;
        }

        return $this;
    }
}
