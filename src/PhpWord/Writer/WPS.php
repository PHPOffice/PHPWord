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

namespace PhpOffice\PhpWord\Writer;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\OLEWrite;

/**
 * WPS writer, Microsoft Works word processor.
 *
 * The document is an OLE compound file whose CONTENTS stream holds the
 * chunked (CHNKWKS) representation of the document.
 *
 * @since 1.5.0
 */
class WPS extends AbstractWriter implements WriterInterface
{
    /**
     * Create new instance.
     */
    public function __construct(?PhpWord $phpWord = null)
    {
        $this->setPhpWord($phpWord);

        $this->parts = ['Contents'];
        foreach ($this->parts as $partName) {
            $partClass = static::class . '\\Part\\' . $partName;
            if (class_exists($partClass)) {
                /** @var WPS\Part\AbstractPart $part */
                $part = new $partClass();
                $part->setParentWriter($this);
                $this->writerParts[strtolower($partName)] = $part;
            }
        }
    }

    /**
     * Save content to file.
     */
    public function save(string $filename): void
    {
        $ole = new OLEWrite();
        $ole->addStream('CONTENTS', $this->getWriterPart('Contents')->write());

        $this->writeFile($this->openFile($filename), $ole->write());
    }
}
