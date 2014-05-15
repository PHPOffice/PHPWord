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

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Style writer
 *
 * @since 0.10.0
 */
abstract class AbstractStyle
{
    /**
     * XML writer
     *
     * @var \PhpOffice\PhpWord\Shared\XMLWriter
     */
    private $xmlWriter;

    /**
     * Style; set protected for a while
     *
     * @var string|\PhpOffice\PhpWord\Style\AbstractStyle
     */
    protected $style;

    /**
     * Write style
     */
    abstract public function write();

    /**
     * Create new instance
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param string|\PhpOffice\PhpWord\Style\AbstractStyle $style
     */
    public function __construct(XMLWriter $xmlWriter, $style = null)
    {
        $this->xmlWriter = $xmlWriter;
        $this->style = $style;
    }

    /**
     * Get XML Writer
     *
     * @return \PhpOffice\PhpWord\Shared\XMLWriter
     */
    protected function getXmlWriter()
    {
        return $this->xmlWriter;
    }

    /**
     * Get Style
     *
     * @return \PhpOffice\PhpWord\Style\AbstractStyle
     */
    protected function getStyle()
    {
        return $this->style;
    }

    /**
     * Convert twip value
     *
     * @param int|float $value
     * @param int|float $default
     * @return int|float
     */
    protected function convertTwip($value, $default = 0)
    {
        $conversions = array(
            Settings::UNIT_CM => 567,
            Settings::UNIT_MM => 56.7,
            Settings::UNIT_INCH => 1440,
            Settings::UNIT_POINT => 20,
            Settings::UNIT_PICA => 240,
        );
        $unit = Settings::getMeasurementUnit();
        if (in_array($unit, $conversions) && $value != $default) {
            return $value * $conversions[$unit];
        } else {
            return $value;
        }
    }
}
