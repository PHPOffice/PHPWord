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
 * Bookmark element
 */
class Bookmark extends AbstractElement
{
    /**
     * Bookmark Name
     *
     * @var string
     */
    private $name;

    /**
     * Is part of collection
     *
     * @var bool
     */
    protected $collectionRelation = true;

    /**
     * Create a new Bookmark Element
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = CommonText::toUTF8($name);
    }

    /**
     * Get Bookmark name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
