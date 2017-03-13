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

namespace PhpOffice\PhpWord\Metadata;

/**
 * Revisions class
 *
 * @since 0.12.0 // TODO: modify the version
 * @link http://www.datypic.com/sc/ooxml/t-w_CT_DocProtect.html // TODO: find link
 */
class Revisions
{
    /**
     * Tracking revisions
     *
     * @var boolean
     * @link http://www.datypic.com/sc/ooxml/a-w_edit-1.html // TODO: find docs
     */
    private $enabled = false;

    /**
     * Whether track revisions is enabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Enable track revisions
     *
     * @param bool $value
     * @return self
     */
    public function setEnabled($value)
    {
        $this->enabled = $value;

        return $this;
    }
}
