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

namespace PhpOffice\PhpWord\Writer\WPS\Part;

use PhpOffice\PhpWord\Writer\AbstractWriter;
use PhpOffice\PhpWord\Writer\WriterPartInterface;

/**
 * Abstract class for WPS parts.
 *
 * @since 1.5.0
 */
abstract class AbstractPart implements WriterPartInterface
{
    /**
     * Parent writer.
     *
     * @var AbstractWriter
     */
    protected $parentWriter;

    /**
     * Set parent writer.
     */
    public function setParentWriter(AbstractWriter $writer): void
    {
        $this->parentWriter = $writer;
    }

    /**
     * Get parent writer.
     */
    public function getParentWriter(): AbstractWriter
    {
        return $this->parentWriter;
    }

    /**
     * Write part content.
     */
    abstract public function write(): string;

    /**
     * Convert a text to the UTF-16LE encoding used by the WPS format.
     */
    protected function encodeText(string $text): string
    {
        return (string) mb_convert_encoding($text, 'UTF-16LE', 'UTF-8');
    }
}
