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

/**
 * SVG element
 */
class SVG extends AbstractElement
{
    /** @var string */
    private $source;


    /**
     * Create new SVG element
     *
     * @param string $source
     * @param mixed $style
     */
    public function __construct($source, $style = null)
    {
        $this->source = $source;

        $this->validate();
    }


    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Validate SVG/XML
     */
    private function validate()
    {
        // TODO: implement validator for svg/xml
    }

}
