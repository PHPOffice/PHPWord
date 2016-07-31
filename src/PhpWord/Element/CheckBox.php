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

namespace PhpOffice\PhpWord\Element;

use PhpOffice\Common\Text as CommonText;

/**
 * Check box element
 *
 * @since 0.10.0
 */
class CheckBox extends Text
{
    /**
     * Name content
     *
     * @var string
     */
    private $name;

    /**
     * Create new instance
     *
     * @param string $name
     * @param string $text
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     * @return self
     */
    public function __construct($name = null, $text = null, $fontStyle = null, $paragraphStyle = null)
    {
        $this->setName($name);
        parent::__construct($text, $fontStyle, $paragraphStyle);
    }

    /**
     * Set name content
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = CommonText::toUTF8($name);

        return $this;
    }

    /**
     * Get name content
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
