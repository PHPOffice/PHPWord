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

namespace PhpOffice\PhpWord\Reader;

use InvalidArgumentException;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\OLERead;

/**
 * Reader for Microsoft Works WPS documents (Works 8 and later).
 *
 * Extracts the textual content stored in the OLE "CONTENTS" stream. The
 * stream begins with the "CHNKWKS" magic and holds a linked list of chunk
 * blocks; each block starts with an 0x01F8 tag followed by named entries,
 * one of which ("TEXT") points at the UTF-16LE document text.
 *
 * @see https://github.com/PHPOffice/PHPWord/issues/69
 * @since 0.19.0
 */
class WPS extends AbstractReader implements ReaderInterface
{
    /**
     * Chunk block tag.
     */
    const CHUNK_MAGIC = 0x01F8;

    /**
     * Chunk block size.
     */
    const CHUNK_BLOCK_SIZE = 0x0E00;

    public function __construct()
    {
        $this->readDataOnly = true;
    }

    /**
     * Can the current Reader read the file?
     *
     * @param string $filename
     *
     * @return bool
     */
    public function canRead($filename)
    {
        try {
            $contents = $this->readContentsStream($filename);

            return strpos($contents, 'CHNKWKS') === 0;
        } catch (Exception $e) {
            return false;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * Loads PhpWord from file.
     *
     * @param string $docFile
     *
     * @return PhpWord
     */
    public function load($docFile)
    {
        $phpWord = new PhpWord();
        $text = $this->extractText($docFile);

        $section = $phpWord->addSection();
        foreach (preg_split('/\r\n|\r|\n/u', $text) as $line) {
            if ($line !== '') {
                $section->addText($line);
            }
        }

        return $phpWord;
    }

    /**
     * Extract the UTF-16LE text from the CONTENTS stream of a WPS document.
     *
     * @param string $filename
     *
     * @return string
     */
    private function extractText($filename)
    {
        $contents = $this->readContentsStream($filename);

        if (strpos($contents, 'CHNKWKS') !== 0) {
            throw new Exception('Invalid WPS file: CHNKWKS magic not found');
        }

        if (substr($contents, 0, 7) === 'CHNKINK') {
            throw new Exception('WPS files prior to version 8 are not supported');
        }

        $totalEntries = self::getUShort($contents, 12);
        $entriesPos = 24;

        while (true) {
            $magic = self::getUShort($contents, $entriesPos);
            if ($magic !== self::CHUNK_MAGIC) {
                throw new Exception('Invalid WPS file: chunk tag mismatch');
            }

            $localEntries = self::getUShort($contents, $entriesPos + 2);
            $nextOffset = self::getULong($contents, $entriesPos + 4);
            $entryPos = $entriesPos + 8;

            for ($i = 0; $i < $localEntries; ++$i) {
                $entrySize = self::getUShort($contents, $entryPos);
                $name = substr($contents, $entryPos + 4, 4);
                if ($name === 'TEXT') {
                    $textOffset = self::getULong($contents, $entryPos + 18);
                    $textSize = self::getULong($contents, $entryPos + 22);
                    $raw = substr($contents, $textOffset, $textSize);
                    if ($raw === '') {
                        throw new Exception('Invalid WPS file: empty TEXT chunk');
                    }
                    $text = iconv('UTF-16LE', 'UTF-8', $raw);
                    if ($text === false) {
                        throw new Exception('Invalid WPS file: text is not valid UTF-16LE');
                    }

                    return str_replace("\r", "\r\n", $text);
                }
                $entryPos += $entrySize;
            }

            $totalEntries -= $localEntries;
            if ($totalEntries > 0 && $nextOffset > 0) {
                $entriesPos = $nextOffset;
            } else {
                throw new Exception('Invalid WPS file: TEXT chunk not found');
            }
        }
    }

    /**
     * Read the OLE "CONTENTS" stream of a WPS document.
     *
     * @param string $filename
     *
     * @return string
     */
    private function readContentsStream($filename)
    {
        $ole = new OLERead();
        $ole->read($filename);

        foreach ($ole->props as $index => $property) {
            if (strtoupper($property['name']) === 'CONTENTS' && $property['type'] === 2) {
                $stream = $ole->getStream($index);
                if ($stream === null || $stream === '') {
                    break;
                }

                return $stream;
            }
        }

        throw new Exception('Invalid WPS file: CONTENTS stream not found');
    }

    /**
     * Read a 16-bit unsigned integer (little endian).
     *
     * @param string $data
     * @param int $offset
     *
     * @return int
     */
    private static function getUShort($data, $offset)
    {
        if ($offset + 2 > strlen($data)) {
            throw new Exception('Invalid WPS file: unexpected end of data');
        }

        $value = unpack('v', substr($data, $offset, 2));

        return $value[1];
    }

    /**
     * Read a 32-bit unsigned integer (little endian).
     *
     * @param string $data
     * @param int $offset
     *
     * @return int
     */
    private static function getULong($data, $offset)
    {
        if ($offset + 4 > strlen($data)) {
            throw new Exception('Invalid WPS file: unexpected end of data');
        }

        $value = unpack('V', substr($data, $offset, 4));

        return $value[1];
    }
}
