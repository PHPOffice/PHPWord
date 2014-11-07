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
 * Table column style
 */
class Column extends AbstractStyle
{

    /**
     * Width
     *
     * @var string
     */
    private $width;

    /**
     * Get width
     *
     * @return string Width
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set width
     *
     * @param string $value
     * @return self
     */
    public function setWidth($value)
    {
        $this->width = $value;

        return $this;
    }

}
