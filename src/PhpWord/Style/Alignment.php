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
 * Alignment style
 *
 * @link http://www.schemacentral.com/sc/ooxml/t-w_CT_Jc.html
 * @since 0.11.0
 */
class Alignment extends AbstractStyle
{
    /**
     * @const string Alignment http://www.schemacentral.com/sc/ooxml/t-w_ST_Jc.html
     */
    const ALIGN_LEFT = 'left'; // Align left
    const ALIGN_RIGHT = 'right'; // Align right
    const ALIGN_CENTER = 'center'; // Align center
    const ALIGN_BOTH = 'both'; // Align both
    const ALIGN_JUSTIFY = 'justify'; // Alias for align both

    /**
     * @var string Alignment
     */
    private $value = null;

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
     * Get alignment
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set alignment
     *
     * @param string $value
     * @return self
     */
    public function setValue($value = null)
    {
        if (strtolower($value) == self::ALIGN_JUSTIFY) {
            $value = self::ALIGN_BOTH;
        }
        $enum = array(self::ALIGN_LEFT, self::ALIGN_RIGHT, self::ALIGN_CENTER, self::ALIGN_BOTH, self::ALIGN_JUSTIFY);
        $this->value = $this->setEnumVal($value, $enum, $this->value);

        return $this;
    }
}
