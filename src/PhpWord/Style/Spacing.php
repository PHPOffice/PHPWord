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

use PhpOffice\PhpWord\SimpleType\LineSpacingRule;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

/**
 * Spacing between lines and above/below paragraph style
 *
 * @see  http://www.datypic.com/sc/ooxml/t-w_CT_Spacing.html
 * @since 0.10.0
 */
class Spacing extends AbstractStyle
{
    /**
     * Spacing above paragraph
     *
     * @var Absolute
     */
    private $before;

    /**
     * Spacing below paragraph
     *
     * @var Absolute
     */
    private $after;

    /**
     * Spacing between lines in paragraph
     *
     * @var Absolute
     */
    private $line;

    /**
     * Type of spacing between lines
     *
     * @var string
     */
    private $lineRule = LineSpacingRule::AUTO;

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
     * Get before
     */
    public function getBefore(): Absolute
    {
        if ($this->before === null) {
            $this->before = new Absolute(null);
        }

        return $this->before;
    }

    /**
     * Set before
     */
    public function setBefore(Absolute $value): self
    {
        $this->before = $value;

        return $this;
    }

    /**
     * Get after
     */
    public function getAfter(): Absolute
    {
        if ($this->after === null) {
            $this->after = new Absolute(null);
        }

        return $this->after;
    }

    /**
     * Set after
     */
    public function setAfter(Absolute $value): self
    {
        $this->after = $value;

        return $this;
    }

    /**
     * Get vertical spacing between lines of text within paragraph.
     * Spacing may vary for same value depending on value of line rule.
     * See `getLineRule()``.
     */
    public function getLine(): Absolute
    {
        if ($this->line === null) {
            $this->line = new Absolute(null);
        }

        return $this->line;
    }

    /**
     * Set vertical spacing between lines of text within paragraph.
     * Spacing may vary for same value depending on value of line rule.
     * See `setLineRule()``.
     */
    public function setLine(Absolute $value): self
    {
        $this->line = $value;

        return $this;
    }

    /**
     * Get line rule
     *
     * @return string
     */
    public function getLineRule()
    {
        return $this->lineRule;
    }

    /**
     * Set line rule
     *
     * @param string $value
     * @return self
     */
    public function setLineRule($value = null)
    {
        LineSpacingRule::validate($value);
        $this->lineRule = $value;

        return $this;
    }

    /**
     * Get line rule
     *
     * @return string
     * @deprecated Use getLineRule() instead
     * @codeCoverageIgnore
     */
    public function getRule()
    {
        return $this->lineRule;
    }

    /**
     * Set line rule
     *
     * @param string $value
     * @return self
     * @deprecated Use setLineRule() instead
     * @codeCoverageIgnore
     */
    public function setRule($value = null)
    {
        $this->lineRule = $value;

        return $this;
    }
}
