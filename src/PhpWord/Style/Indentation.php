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
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

/**
 * Paragraph indentation style
 *
 * @link http://www.schemacentral.com/sc/ooxml/t-w_CT_Ind.html
 * @since 0.10.0
 */
class Indentation extends AbstractStyle
{
    /**
     * Left indentation (twip)
     *
     * @var int|float
     */
    private $left = 0;

    /**
     * Right indentation (twip)
     *
     * @var int|float
     */
    private $right = 0;

    /**
     * Additional first line indentation (twip)
     *
     * @var int|float
     */
    private $firstLine;

    /**
     * Indentation removed from first line (twip)
     *
     * @var int|float
     */
    private $hanging;

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
     * Get left
     *
     * @return int|float
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * Set left
     *
     * @param int|float $value
     * @return self
     */
    public function setLeft($value = null)
    {
        $this->left = $this->setNumericVal($value, $this->left);

        return $this;
    }

    /**
     * Get right
     *
     * @return int|float
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * Set right
     *
     * @param int|float $value
     * @return self
     */
    public function setRight($value = null)
    {
        $this->right = $this->setNumericVal($value, $this->right);

        return $this;
    }

    /**
     * Get first line
     *
     * @return int|float
     */
    public function getFirstLine()
    {
        return $this->firstLine;
    }

    /**
     * Set first line
     *
     * @param int|float $value
     * @return self
     */
    public function setFirstLine($value = null)
    {
        $this->firstLine = $this->setNumericVal($value, $this->firstLine);

        return $this;
    }

    /**
     * Get hanging
     *
     * @return int|float
     */
    public function getHanging()
    {
        return $this->hanging;
    }

    /**
     * Set hanging
     *
     * @param int|float $value
     * @return self
     */
    public function setHanging($value = null)
    {
        $this->hanging = $this->setNumericVal($value, $this->hanging);

        return $this;
    }
}
