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

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\Style\Colors\BasicColor;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Lengths\Absolute;
use PhpOffice\PhpWord\Style\Lengths\Auto;
use PhpOffice\PhpWord\Style\Lengths\Length;

class Table extends AbstractStyle
{
    use Border;

    //values for http://www.datypic.com/sc/ooxml/t-w_ST_TblLayoutType.html
    /**
     * AutoFit Table Layout
     *
     * @var string
     */
    const LAYOUT_AUTO = 'autofit';
    /**
     * Fixed Width Table Layout
     *
     * @var string
     */
    const LAYOUT_FIXED = 'fixed';

    /**
     * Is this a first row style?
     *
     * @var bool
     */
    private $isFirstRow = false;

    /**
     * Style for first row
     *
     * @var \PhpOffice\PhpWord\Style\Table
     */
    private $firstRowStyle;

    /**
     * Cell margin top
     *
     * @var Absolute
     */
    private $cellMarginTop;

    /**
     * Cell margin left
     *
     * @var Absolute
     */
    private $cellMarginLeft;

    /**
     * Cell margin right
     *
     * @var Absolute
     */
    private $cellMarginRight;

    /**
     * Cell margin bottom
     *
     * @var Absolute
     */
    private $cellMarginBottom;

    /**
     * Shading
     *
     * @var \PhpOffice\PhpWord\Style\Shading
     */
    private $shading;

    /**
     * @var string
     */
    private $alignment = '';

    /**
     * @var Length Width value
     */
    private $width;

    /**
     * @var Absolute cell spacing value
     */
    protected $cellSpacing = null;

    /**
     * @var string Table Layout
     */
    private $layout = self::LAYOUT_AUTO;

    /**
     * Position
     *
     * @var \PhpOffice\PhpWord\Style\TablePosition
     */
    private $position;

    /** @var Length */
    private $indent;

    /**
     * The width of each column, computed based on the max cell width of each column
     *
     * @var Length[]
     */
    private $columnWidths;

    /**
     * Visually Right to Left Table
     *
     * @see  http://www.datypic.com/sc/ooxml/e-w_bidiVisual-1.html
     * @var bool
     */
    private $bidiVisual = false;

    /**
     * Create new table style
     * @param null|mixed $tableStyle
     * @param null|mixed $firstRowStyle
     */
    public function __construct($tableStyle = null, $firstRowStyle = null)
    {
        $this->setIndent(new Auto());
        $this->setWidth(new Auto());
        $this->setCellSpacing(new Absolute(null));
        $this->setCellMargin(new Absolute(null));

        // Clone first row from table style, but with certain properties disabled
        if ($firstRowStyle !== null && is_array($firstRowStyle)) {
            $this->firstRowStyle = clone $this;
            $this->firstRowStyle->isFirstRow = true;
            $this->firstRowStyle
                ->setCellSpacing(new Absolute(null))
                ->setCellMargin(new Absolute(null));
            unset($this->firstRowStyle->firstRowStyle);
            $this->firstRowStyle->setStyleByArray($firstRowStyle);
        }

        if ($tableStyle !== null && is_array($tableStyle)) {
            $this->setStyleByArray($tableStyle);
        }
    }

    protected function getAllowedSides(): array
    {
        $sides = array(
            'top',
            'bottom',
            'start',
            'end',
        );

        if (!$this->isFirstRow) {
            $sides[] = 'insideH';
            $sides[] = 'insideV';
        }

        return $sides;
    }

    public function setCellSpacing(Absolute $cellSpacing): self
    {
        $this->cellSpacing = $cellSpacing;

        return $this;
    }

    public function getCellSpacing(): Absolute
    {
        return $this->cellSpacing;
    }

    /**
     * Set first row
     *
     * @return \PhpOffice\PhpWord\Style\Table
     */
    public function getFirstRow()
    {
        return $this->firstRowStyle;
    }

    /**
     * Get background
     */
    public function getBgColor(): BasicColor
    {
        if ($this->shading === null) {
            $this->setBgColor(new Hex(null));
        }

        return $this->shading->getFill();
    }

    /**
     * Set background
     *
     * @return self
     */
    public function setBgColor(BasicColor $value)
    {
        $this->setShading(array('fill' => $value));

        return $this;
    }

    /**
     * Get cell margin top
     */
    public function getCellMarginTop(): Absolute
    {
        return $this->cellMarginTop;
    }

    /**
     * Set cell margin top
     */
    public function setCellMarginTop(Absolute $value): self
    {
        if (!$this->isFirstRow) {
            $this->cellMarginTop = $value;
        }

        return $this;
    }

    /**
     * Get cell margin left
     */
    public function getCellMarginLeft(): Absolute
    {
        return $this->cellMarginLeft;
    }

    /**
     * Set cell margin left
     */
    public function setCellMarginLeft(Absolute $value): self
    {
        if (!$this->isFirstRow) {
            $this->cellMarginLeft = $value;
        }

        return $this;
    }

    /**
     * Get cell margin right
     */
    public function getCellMarginRight(): Absolute
    {
        return $this->cellMarginRight;
    }

    /**
     * Set cell margin right
     */
    public function setCellMarginRight(Absolute $value): self
    {
        if (!$this->isFirstRow) {
            $this->cellMarginRight = $value;
        }

        return $this;
    }

    /**
     * Get cell margin bottom
     */
    public function getCellMarginBottom(): Absolute
    {
        return $this->cellMarginBottom;
    }

    /**
     * Set cell margin bottom
     */
    public function setCellMarginBottom(Absolute $value): self
    {
        if (!$this->isFirstRow) {
            $this->cellMarginBottom = $value;
        }

        return $this;
    }

    /**
     * Get cell margin
     *
     * @return Absolute[]
     */
    public function getCellMargin(): array
    {
        return array(
            $this->getCellMarginTop(),
            $this->getCellMarginLeft(),
            $this->getCellMarginRight(),
            $this->getCellMarginBottom(),
        );
    }

    /**
     * Set TLRB cell margin
     *
     * @param Absolute $value Margin
     * @return self
     */
    public function setCellMargin(Absolute $value)
    {
        $this->setCellMarginTop($value);
        $this->setCellMarginLeft($value);
        $this->setCellMarginRight($value);
        $this->setCellMarginBottom($value);

        return $this;
    }

    /**
     * Check if any of the margin is not null
     *
     * @return bool
     */
    public function hasMargin()
    {
        foreach ($this->getCellMargin() as $margin) {
            if ($margin->toInt('twip') !== null) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get shading
     *
     * @return \PhpOffice\PhpWord\Style\Shading
     */
    public function getShading()
    {
        return $this->shading;
    }

    /**
     * Set shading
     *
     * @param null|mixed $value
     * @return self
     */
    public function setShading($value = null)
    {
        $this->setObjectVal($value, 'Shading', $this->shading);

        return $this;
    }

    /**
     * @since 0.13.0
     *
     * @return string
     */
    public function getAlignment()
    {
        return $this->alignment;
    }

    /**
     * @since 0.13.0
     *
     * @param string $value
     *
     * @return self
     */
    public function setAlignment($value)
    {
        if (JcTable::isValid($value) || Jc::isValid($value)) {
            $this->alignment = $value;
        }

        return $this;
    }

    /**
     * @deprecated 0.13.0 Use the `getAlignment` method instead.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getAlign()
    {
        return $this->getAlignment();
    }

    /**
     * @deprecated 0.13.0 Use the `setAlignment` method instead.
     *
     * @param string $value
     *
     * @return self
     *
     * @codeCoverageIgnore
     */
    public function setAlign($value = null)
    {
        return $this->setAlignment($value);
    }

    /**
     * Get width
     */
    public function getWidth(): Length
    {
        return $this->width;
    }

    /**
     * Set width
     */
    public function setWidth(Length $value): self
    {
        $this->width = $value;

        return $this;
    }

    /**
     * Get layout
     *
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Set layout
     *
     * @param string $value
     * @return self
     */
    public function setLayout($value = null)
    {
        $enum = array(self::LAYOUT_AUTO, self::LAYOUT_FIXED);
        $this->layout = $this->setEnumVal($value, $enum, $this->layout);

        return $this;
    }

    /**
     * Get position
     *
     * @return \PhpOffice\PhpWord\Style\TablePosition
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set position
     *
     * @param null|mixed $value
     * @return self
     */
    public function setPosition($value = null)
    {
        $this->setObjectVal($value, 'TablePosition', $this->position);

        return $this;
    }

    public function getIndent(): Length
    {
        return $this->indent;
    }

    /**
     * @return self
     * @see http://www.datypic.com/sc/ooxml/e-w_tblInd-1.html
     */
    public function setIndent(Length $indent)
    {
        $this->indent = $indent;

        return $this;
    }

    /**
     * Get the columnWidths
     *
     * @return null|Length[]
     */
    public function getColumnWidths()
    {
        return $this->columnWidths;
    }

    /**
     * The column widths
     */
    public function setColumnWidths(array $values = null)
    {
        foreach ($values as $value) {
            if (!($value instanceof Length)) {
                throw new Exception(sprintf('Column widths must be specified with `%s`', Length::class));
            }
        }

        $this->columnWidths = $values;
    }

    /**
     * Get bidiVisual
     *
     * @return bool
     */
    public function isBidiVisual()
    {
        return $this->bidiVisual;
    }

    /**
     * Set bidiVisual
     *
     * @param bool $bidi
     *            Set to true to visually present table as Right to Left
     * @return self
     */
    public function setBidiVisual($bidi)
    {
        $this->bidiVisual = $bidi;

        return $this;
    }
}
