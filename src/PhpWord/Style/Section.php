<?php
declare(strict_types=1);
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

use PhpOffice\PhpWord\SimpleType\VerticalJc;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

/**
 * Section settings
 */
class Section extends AbstractStyle
{
    use Border;

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
    const DEFAULT_WIDTH = 11905.511811024; // In twips.
    const DEFAULT_HEIGHT = 16837.79527559; // In twips.
    const DEFAULT_MARGIN = 1440;           // In twips.
    const DEFAULT_GUTTER = 0;              // In twips.
    const DEFAULT_HEADER_HEIGHT = 720;     // In twips.
    const DEFAULT_FOOTER_HEIGHT = 720;     // In twips.
    const DEFAULT_COLUMN_COUNT = 1;
    const DEFAULT_COLUMN_SPACING = 720;    // In twips.

    /**
     * Page Orientation
     *
     * @var string
     * @see  http://www.schemacentral.com/sc/ooxml/a-w_orient-1.html
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
     * @var Absolute
     */
    private $pageSizeW = self::DEFAULT_WIDTH;

    /**
     * Page Size Height
     *
     * @var Absolute
     */
    private $pageSizeH = self::DEFAULT_HEIGHT;

    /**
     * Top margin spacing
     *
     * @var int|Absolute
     */
    private $marginTop = self::DEFAULT_MARGIN;

    /**
     * Left margin spacing
     *
     * @var int|Absolute
     */
    private $marginLeft = self::DEFAULT_MARGIN;

    /**
     * Right margin spacing
     *
     * @var int|Absolute
     */
    private $marginRight = self::DEFAULT_MARGIN;

    /**
     * Bottom margin spacing
     *
     * @var int|Absolute
     */
    private $marginBottom = self::DEFAULT_MARGIN;

    /**
     * Page gutter spacing
     *
     * @var int|Absolute
     * @see  http://www.schemacentral.com/sc/ooxml/e-w_pgMar-1.html
     */
    private $gutter = self::DEFAULT_GUTTER;

    /**
     * Header height
     *
     * @var int|Absolute
     */
    private $headerHeight = self::DEFAULT_HEADER_HEIGHT;

    /**
     * Footer height
     *
     * @var int|Absolute
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
     * @var int|Absolute
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
     * @see  http://www.schemacentral.com/sc/ooxml/e-w_lnNumType-1.html
     */
    private $lineNumbering;

    /**
     * Vertical Text Alignment on Page
     * One of \PhpOffice\PhpWord\SimpleType\VerticalJc
     *
     * @var string
     */
    private $vAlign;

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
     */
    public function setOrientation($value = null): self
    {
        $enum = array(self::ORIENTATION_PORTRAIT, self::ORIENTATION_LANDSCAPE);
        $this->orientation = $this->setEnumVal($value, $enum, $this->orientation);

        $isWide = $this->pageSizeW->toInt('twip') >= $this->pageSizeH->toInt('twip');
        $shouldBeWide = $this->orientation === self::ORIENTATION_LANDSCAPE;

        // If orientation doesn't match dimensions,
        // swap the dimensions.
        if ($isWide !== $shouldBeWide) {
            $newWidth = $this->pageSizeW;
            $this->pageSizeW = $this->pageSizeH;
            $this->pageSizeH = $newWidth;
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
     *
     * @since 0.12.0
     */
    public function getPageSizeW(): Absolute
    {
        return $this->pageSizeW;
    }

    /**
     * @return \PhpOffice\PhpWord\Style\Section
     *
     * @since 0.12.0
     */
    public function setPageSizeW(Absolute $value): self
    {
        $this->pageSizeW = $value;

        return $this;
    }

    /**
     * Get Page Size Height
     *
     * @return Absolute
     *
     * @since 0.12.0: Absolute
     */
    public function getPageSizeH()
    {
        return $this->pageSizeH;
    }

    /**
     * @return \PhpOffice\PhpWord\Style\Section
     *
     * @since 0.12.0
     */
    public function setPageSizeH(Absolute $value): self
    {
        $this->pageSizeH = $value;

        return $this;
    }

    /**
     * Get Margin Top
     */
    public function getMarginTop(): Absolute
    {
        if (!($this->marginTop instanceof Absolute)) {
            $this->marginTop = Absolute::from('twip', $this->marginTop);
        }

        return $this->marginTop;
    }

    /**
     * Set Margin Top
     */
    public function setMarginTop(Absolute $value): self
    {
        $this->marginTop = $value;

        return $this;
    }

    /**
     * Get Margin Left
     */
    public function getMarginLeft(): Absolute
    {
        if (!($this->marginLeft instanceof Absolute)) {
            $this->marginLeft = Absolute::from('twip', $this->marginLeft);
        }

        return $this->marginLeft;
    }

    /**
     * Set Margin Left
     */
    public function setMarginLeft(Absolute $value): self
    {
        $this->marginLeft = $value;

        return $this;
    }

    /**
     * Get Margin Right
     */
    public function getMarginRight(): Absolute
    {
        if (!($this->marginRight instanceof Absolute)) {
            $this->marginRight = Absolute::from('twip', $this->marginRight);
        }

        return $this->marginRight;
    }

    /**
     * Set Margin Right
     */
    public function setMarginRight(Absolute $value): self
    {
        $this->marginRight = $value;

        return $this;
    }

    /**
     * Get Margin Bottom
     */
    public function getMarginBottom(): Absolute
    {
        if (!($this->marginBottom instanceof Absolute)) {
            $this->marginBottom = Absolute::from('twip', $this->marginBottom);
        }

        return $this->marginBottom;
    }

    /**
     * Set Margin Bottom
     */
    public function setMarginBottom(Absolute $value): self
    {
        $this->marginBottom = $value;

        return $this;
    }

    /**
     * Get gutter
     */
    public function getGutter(): Absolute
    {
        if (!($this->gutter instanceof Absolute)) {
            $this->gutter = Absolute::from('twip', $this->gutter);
        }

        return $this->gutter;
    }

    /**
     * Set gutter
     */
    public function setGutter(Absolute $value): self
    {
        $this->gutter = $value;

        return $this;
    }

    /**
     * Get Header Height
     */
    public function getHeaderHeight(): Absolute
    {
        if (!($this->headerHeight instanceof Absolute)) {
            $this->headerHeight = Absolute::from('twip', $this->headerHeight);
        }

        return $this->headerHeight;
    }

    /**
     * Set Header Height
     */
    public function setHeaderHeight(Absolute $value): self
    {
        $this->headerHeight = $value;

        return $this;
    }

    /**
     * Get Footer Height
     */
    public function getFooterHeight(): Absolute
    {
        if (!($this->footerHeight instanceof Absolute)) {
            $this->footerHeight = Absolute::from('twip', $this->footerHeight);
        }

        return $this->footerHeight;
    }

    /**
     * Set Footer Height
     *
     * @return self
     */
    public function setFooterHeight(Absolute $value)
    {
        $this->footerHeight = $value;

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
     */
    public function getColsSpace(): Absolute
    {
        if (!($this->colsSpace instanceof Absolute)) {
            $this->colsSpace = Absolute::from('twip', $this->colsSpace);
        }

        return $this->colsSpace;
    }

    /**
     * Set Section Space Between Columns
     */
    public function setColsSpace(Absolute $value): self
    {
        $this->colsSpace = $value;

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
     * @param null|mixed $value
     * @return self
     */
    public function setLineNumbering($value = null)
    {
        $this->setObjectVal($value, 'LineNumbering', $this->lineNumbering);

        return $this;
    }

    /**
     * Get vertical alignment
     *
     * @return string
     */
    public function getVAlign()
    {
        return $this->vAlign;
    }

    /**
     * Set vertical alignment
     *
     * @param string $value
     * @return self
     */
    public function setVAlign($value = null)
    {
        VerticalJc::validate($value);
        $this->vAlign = $value;

        return $this;
    }
}
