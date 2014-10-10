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
 * Section settings
 */
class Section extends Border
{
    /**
     * Page orientation
     *
     * @const string
     */
    const ORIENTATION_PORTRAIT = 'portrait';
    const ORIENTATION_LANDSCAPE = 'landscape';

    /**
     * Page default constants
     *
     * @const int|float
     */
    const DEFAULT_WIDTH = 11870; // In twip
    const DEFAULT_HEIGHT = 16787; // In twip
    const DEFAULT_MARGIN = 1440; // In twip
    const DEFAULT_GUTTER = 0; // In twip
    const DEFAULT_HEADER_HEIGHT = 720; // In twip
    const DEFAULT_FOOTER_HEIGHT = 720; // In twip
    const DEFAULT_COLUMN_COUNT = 1;
    const DEFAULT_COLUMN_SPACING = 720; // In twip

    /**
     * Page Orientation
     *
     * @var string
     * @link http://www.schemacentral.com/sc/ooxml/a-w_orient-1.html
     */
    private $orientation = self::ORIENTATION_PORTRAIT;

    /**
     * Paper size
     *
     * @var \PhpOffice\PhpWord\Style\Paper
     */
    private $paper;

    /**
     * Page Size Width
     *
     * @var int|float
     */
    private $pageSizeW = self::DEFAULT_WIDTH;

    /**
     * Page Size Height
     *
     * @var int|float
     */
    private $pageSizeH = self::DEFAULT_HEIGHT;

    /**
     * Top margin spacing
     *
     * @var int|float
     */
    private $marginTop = self::DEFAULT_MARGIN;

    /**
     * Left margin spacing
     *
     * @var int|float
     */
    private $marginLeft = self::DEFAULT_MARGIN;

    /**
     * Right margin spacing
     *
     * @var int|float
     */
    private $marginRight = self::DEFAULT_MARGIN;

    /**
     * Bottom margin spacing
     *
     * @var int|float
     */
    private $marginBottom = self::DEFAULT_MARGIN;

    /**
     * Page gutter spacing
     *
     * @var int|float
     * @link http://www.schemacentral.com/sc/ooxml/e-w_pgMar-1.html
     */
    private $gutter = self::DEFAULT_GUTTER;

    /**
     * Header height
     *
     * @var int|float
     */
    private $headerHeight = self::DEFAULT_HEADER_HEIGHT;

    /**
     * Footer height
     *
     * @var int|float
     */
    private $footerHeight = self::DEFAULT_FOOTER_HEIGHT;

    /**
     * Page Numbering Start
     *
     * @var int
     */
    private $pageNumberingStart;

    /**
     * Section columns count
     *
     * @var int
     */
    private $colsNum = self::DEFAULT_COLUMN_COUNT;

    /**
     * Section spacing between columns
     *
     * @var int|float
     */
    private $colsSpace = self::DEFAULT_COLUMN_SPACING;

    /**
     * Section break type
     *
     * Options:
     * - nextPage: Next page section break
     * - nextColumn: Column section break
     * - continuous: Continuous section break
     * - evenPage: Even page section break
     * - oddPage: Odd page section break
     *
     * @var string
     */
    private $breakType;

    /**
     * Line numbering
     *
     * @var \PhpOffice\PhpWord\Style\LineNumbering
     * @link http://www.schemacentral.com/sc/ooxml/e-w_lnNumType-1.html
     */
    private $lineNumbering;

    /**
     * Create new instance
     */
    public function __construct()
    {
        $this->setPaperSize();
    }

    /**
     * Get paper size
     *
     * @return string
     */
    public function getPaperSize()
    {
        return $this->paper->getSize();
    }

    /**
     * Set paper size
     *
     * @param string $value
     * @return self
     */
    public function setPaperSize($value = 'A4')
    {
        if ($this->paper === null) {
            $this->paper = new Paper();
        }
        $this->paper->setSize($value);
        $this->pageSizeW = $this->paper->getWidth();
        $this->pageSizeH = $this->paper->getHeight();

        return $this;
    }

    /**
     * Set Setting Value
     *
     * @param string $key
     * @param string $value
     * @return self
     */
    public function setSettingValue($key, $value)
    {
        return $this->setStyleValue($key, $value);
    }

    /**
     * Set orientation
     *
     * @param string $value
     * @return self
     */
    public function setOrientation($value = null)
    {
        $enum = array(self::ORIENTATION_PORTRAIT, self::ORIENTATION_LANDSCAPE);
        $this->orientation = $this->setEnumVal($value, $enum, $this->orientation);

        /** @var int|float $longSide Type hint */
        $longSide = $this->pageSizeW >= $this->pageSizeH ? $this->pageSizeW : $this->pageSizeH;

        /** @var int|float $shortSide Type hint */
        $shortSide = $this->pageSizeW < $this->pageSizeH ? $this->pageSizeW : $this->pageSizeH;

        if ($this->orientation == self::ORIENTATION_PORTRAIT) {
            $this->pageSizeW = $shortSide;
            $this->pageSizeH = $longSide;
        } else {
            $this->pageSizeW = $longSide;
            $this->pageSizeH = $shortSide;
        }

        return $this;
    }

    /**
     * Get Page Orientation
     *
     * @return string
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * Set Portrait Orientation
     *
     * @return self
     */
    public function setPortrait()
    {
        return $this->setOrientation(self::ORIENTATION_PORTRAIT);
    }

    /**
     * Set Landscape Orientation
     *
     * @return self
     */
    public function setLandscape()
    {
        return $this->setOrientation(self::ORIENTATION_LANDSCAPE);
    }

    /**
     * Get Page Size Width
     *
     * @return int|float|null
     *
     * @since 0.12.0
     */
    public function getPageSizeW()
    {
        return $this->pageSizeW;
    }

    /**
     * @param int|float|null $value
     *
     * @return \PhpOffice\PhpWord\Style\Section
     *
     * @since 0.12.0
     */
    public function setPageSizeW($value = null)
    {
        $this->pageSizeW = $this->setNumericVal($value, self::DEFAULT_WIDTH);

        return $this;
    }

    /**
     * Get Page Size Height
     *
     * @return int|float|null
     *
     * @since 0.12.0
     */
    public function getPageSizeH()
    {
        return $this->pageSizeH;
    }

    /**
     * @param int|float|null $value
     *
     * @return \PhpOffice\PhpWord\Style\Section
     *
     * @since 0.12.0
     */
    public function setPageSizeH($value = null)
    {
        $this->pageSizeH = $this->setNumericVal($value, self::DEFAULT_HEIGHT);

        return $this;
    }

    /**
     * Get Margin Top
     *
     * @return int|float
     */
    public function getMarginTop()
    {
        return $this->marginTop;
    }

    /**
     * Set Margin Top
     *
     * @param int|float $value
     * @return self
     */
    public function setMarginTop($value = null)
    {
        $this->marginTop = $this->setNumericVal($value, self::DEFAULT_MARGIN);

        return $this;
    }

    /**
     * Get Margin Left
     *
     * @return int|float
     */
    public function getMarginLeft()
    {
        return $this->marginLeft;
    }

    /**
     * Set Margin Left
     *
     * @param int|float $value
     * @return self
     */
    public function setMarginLeft($value = null)
    {
        $this->marginLeft = $this->setNumericVal($value, self::DEFAULT_MARGIN);

        return $this;
    }

    /**
     * Get Margin Right
     *
     * @return int|float
     */
    public function getMarginRight()
    {
        return $this->marginRight;
    }

    /**
     * Set Margin Right
     *
     * @param int|float $value
     * @return self
     */
    public function setMarginRight($value = null)
    {
        $this->marginRight = $this->setNumericVal($value, self::DEFAULT_MARGIN);

        return $this;
    }

    /**
     * Get Margin Bottom
     *
     * @return int|float
     */
    public function getMarginBottom()
    {
        return $this->marginBottom;
    }

    /**
     * Set Margin Bottom
     *
     * @param int|float $value
     * @return self
     */
    public function setMarginBottom($value = null)
    {
        $this->marginBottom = $this->setNumericVal($value, self::DEFAULT_MARGIN);

        return $this;
    }

    /**
     * Get gutter
     *
     * @return int|float
     */
    public function getGutter()
    {
        return $this->gutter;
    }

    /**
     * Set gutter
     *
     * @param int|float $value
     * @return self
     */
    public function setGutter($value = null)
    {
        $this->gutter = $this->setNumericVal($value, self::DEFAULT_GUTTER);

        return $this;
    }

    /**
     * Get Header Height
     *
     * @return int|float
     */
    public function getHeaderHeight()
    {
        return $this->headerHeight;
    }

    /**
     * Set Header Height
     *
     * @param int|float $value
     * @return self
     */
    public function setHeaderHeight($value = null)
    {
        $this->headerHeight = $this->setNumericVal($value, self::DEFAULT_HEADER_HEIGHT);

        return $this;
    }

    /**
     * Get Footer Height
     *
     * @return int|float
     */
    public function getFooterHeight()
    {
        return $this->footerHeight;
    }

    /**
     * Set Footer Height
     *
     * @param int|float $value
     * @return self
     */
    public function setFooterHeight($value = null)
    {
        $this->footerHeight = $this->setNumericVal($value, self::DEFAULT_FOOTER_HEIGHT);

        return $this;
    }

    /**
     * Get page numbering start
     *
     * @return null|int
     */
    public function getPageNumberingStart()
    {
        return $this->pageNumberingStart;
    }

    /**
     * Set page numbering start
     *
     * @param null|int $pageNumberingStart
     * @return self
     */
    public function setPageNumberingStart($pageNumberingStart = null)
    {
        $this->pageNumberingStart = $pageNumberingStart;
        return $this;
    }

    /**
     * Get Section Columns Count
     *
     * @return int
     */
    public function getColsNum()
    {
        return $this->colsNum;
    }

    /**
     * Set Section Columns Count
     *
     * @param int $value
     * @return self
     */
    public function setColsNum($value = null)
    {
        $this->colsNum = $this->setIntVal($value, self::DEFAULT_COLUMN_COUNT);

        return $this;
    }

    /**
     * Get Section Space Between Columns
     *
     * @return int|float
     */
    public function getColsSpace()
    {
        return $this->colsSpace;
    }

    /**
     * Set Section Space Between Columns
     *
     * @param int|float $value
     * @return self
     */
    public function setColsSpace($value = null)
    {
        $this->colsSpace = $this->setNumericVal($value, self::DEFAULT_COLUMN_SPACING);

        return $this;
    }

    /**
     * Get Break Type
     *
     * @return string
     */
    public function getBreakType()
    {
        return $this->breakType;
    }

    /**
     * Set Break Type
     *
     * @param string $value
     * @return self
     */
    public function setBreakType($value = null)
    {
        $this->breakType = $value;
        return $this;
    }

    /**
     * Get line numbering
     *
     * @return \PhpOffice\PhpWord\Style\LineNumbering
     */
    public function getLineNumbering()
    {
        return $this->lineNumbering;
    }

    /**
     * Set line numbering
     *
     * @param mixed $value
     * @return self
     */
    public function setLineNumbering($value = null)
    {
        $this->setObjectVal($value, 'LineNumbering', $this->lineNumbering);

        return $this;
    }
}
