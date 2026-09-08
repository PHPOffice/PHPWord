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

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Writer\AbstractWriter;

abstract class AbstractPart
{
    /**
     * @var null|AbstractWriter
     */
    private $parentWriter;

    abstract public function write(): string;

    public function setParentWriter(?AbstractWriter $writer = null): void
    {
        $this->parentWriter = $writer;
    }

    public function getParentWriter(): AbstractWriter
    {
        if ($this->parentWriter !== null) {
            return $this->parentWriter;
        }

        throw new Exception('No parent WriterInterface assigned.');
    }

    protected function toUtf16Le(string $text): string
    {
        return (string) mb_convert_encoding($text, 'UTF-16LE', 'UTF-8');
    }
}
