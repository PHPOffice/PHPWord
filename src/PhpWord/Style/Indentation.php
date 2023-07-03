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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

/**
 * Paragraph indentation style.
 *
 * @see  http://www.schemacentral.com/sc/ooxml/t-w_CT_Ind.html
 * @since 0.10.0
 */
class Indentation extends AbstractStyle
{
    /**
     * Left indentation (twip).
     *
     * @var float|int
     */
    private $left;

    /**
     * Right indentation (twip).
     *
     * @var float|int
     */
    private $right;

    /**
     * Additional first line indentation (twip).
     * 额外的首行缩进（twip）。
     *
     * @var float|int
     */
    private $firstLine;

    /**
     * Additional first line chars indentation (twip).
     * 额外的首行缩进（twip）。
     *
     * @var float|int
     */
    private $firstLineChars;

    /**
     * Indentation removed from first line (twip).
     *
     * @var float|int
     */
    private $hanging;

    /**
     * Indentation removed from first line (twip).
     *
     * @var float|int
     */
    private $hangingChars;



    /**
     * Indentation removed from first line (twip).
     *
     * @var float|int
     */
    private $indLeftChar;



    /**
     * Create a new instance.
     *
     * @param array $style
     */
    public function __construct($style = [])
    {
        $this->setStyleByArray($style);
    }

    /**
     * Get left.
     *
     * @return float|int
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * Set left.
     *
     * @param float|int $value
     *
     * @return self
     */
    public function setLeft($value = null)
    {
        $this->left = $this->setNumericVal($value, $this->left);

        return $this;
    }

    /**
     * Get right.
     *
     * @return float|int
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * Set right.
     *
     * @param float|int $value
     *
     * @return self
     */
    public function setRight($value = null)
    {
        $this->right = $this->setNumericVal($value, $this->right);

        return $this;
    }

    /**
     * Get first line.
     *
     * @return float|int
     */
    public function getFirstLine()
    {
        return $this->firstLine;
    }

    /**
     * Set first line.
     *
     * @param float|int $value
     *
     * @return self
     */
    public function setFirstLine($value = null)
    {
        $this->firstLine = $this->setNumericVal($value, $this->firstLine);

        return $this;
    }

    /**
     * Get firstLine.
     *
     * @return float|int
     */
    public function getFirstLineChars()
    {
        return $this->firstLineChars;
    }

    /**
     * Set firstline.
     *
     * @param float|int $value
     *
     * @return self
     */
    public function setFirstLineChars($value = null)
    {
        $this->firstLineChars = $this->setNumericVal($value, $this->firstLineChars);

        return $this;
    }

    /**
     * Get leftChar.
     *
     * @return float|int
     */
    public function getIndLeftChar()
    {
        return $this->indLeftChar;
    }

    /**
     * Set firstline.
     *
     * @param float|int $value
     *
     * @return self
     */
    public function setIndLeftChar($value = null)
    {
        $this->indLeftChar = $this->setNumericVal($value, $this->indLeftChar);

        return $this;
    }

    /**
     * Get hanging.
     *
     * @return float|int
     */
    public function getHanging()
    {
        return $this->hanging;
    }

    /**
     * Set hanging.
     *
     * @param float|int $value
     *
     * @return self
     */
    public function setHanging($value = null)
    {
        $this->hanging = $this->setNumericVal($value, $this->hanging);

        return $this;
    }

    /**
     * Get hanging.
     *
     * @return float|int
     */
    public function getHangingChars()
    {
        return $this->hangingChars;
    }

    /**
     * Set hanging.
     *
     * @param float|int $value
     *
     * @return self
     */
    public function setHangingChars($value = null)
    {
        $this->hangingChars = $this->setNumericVal($value, $this->hangingChars);

        return $this;
    }
}
