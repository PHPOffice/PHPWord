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
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\EPub3\Style;

use PhpOffice\PhpWord\Writer\AbstractWriter;

/**
 * Abstract class for EPub3 styles.
 */
abstract class AbstractStyle
{
    /**
     * Parent writer.
     *
     * @var AbstractWriter
     */
    protected $parentWriter;

    /**
     * Set parent writer.
     *
     * @return self
     */
    public function setParentWriter(AbstractWriter $writer)
    {
        $this->parentWriter = $writer;

        return $this;
    }

    /**
     * Get parent writer.
     *
     * @return AbstractWriter
     */
    public function getParentWriter()
    {
        return $this->parentWriter;
    }

    /**
     * Write style content.
     *
     * @return string
     */
    abstract public function write();
}
