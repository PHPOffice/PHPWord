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
use PhpOffice\PhpWord\Shared\CompoundFile;

/**
 * Writer for Microsoft Works 7/8 .wps files.
 *
 * Produces an OLE compound document whose CONTENTS stream is a CHNKWKS
 * document (TEXT + FONT chunks), matching the format notes linked from #69.
 */
class WPS extends AbstractWriter implements WriterInterface
{
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

    public function save(string $filename): void
    {
        $compound = new CompoundFile();
        $compound->putStream('CONTENTS', $this->getWriterPart('Contents')->write());

        $this->writeFile($this->openFile($filename), $compound->toBinary());
    }
}
