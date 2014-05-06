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
    protected $xmlWriter;

    /**
     * Style
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
     * @param string|\PhpOffice\PhpWord\Style\AbstractStyle $style
     */
    public function __construct(XMLWriter $xmlWriter, $style = null)
    {
        $this->xmlWriter = $xmlWriter;
        $this->style = $style;
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
        $unit = Settings::getMeasurementUnit();
        if ($unit == Settings::UNIT_TWIP || $value == $default) {
            return $value;
        } else {
            return $value * $unit;
        }
    }

    /**
     * Write element when ...
     *
     * @param bool $condition
     * @param string $element
     * @param string $attribute
     * @param string $value
     */
    protected function writeElementIf($condition, $element, $attribute = null, $value = null)
    {
        if (!$condition) {
            return;
        }

        if (is_null($attribute)) {
            $this->xmlWriter->writeElement($element, $value);
        } else {
            $this->xmlWriter->startElement($element);
            $this->xmlWriter->writeAttribute($attribute, $value);
            $this->xmlWriter->endElement();
        }
    }
}
