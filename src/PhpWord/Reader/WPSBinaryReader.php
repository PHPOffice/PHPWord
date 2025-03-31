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

use Exception;

/**
 * Reader for binary Microsoft Works WPS files
 * Based on the WPS file format as documented at:
 * http://blog.digitally-disturbed.co.uk/2012/04/reading-microsoft-works-wps-files-in.html.
 */
class WPSBinaryReader
{
    /**
     * Magic pattern to identify WPS binary format files.
     */
    const WPS_MAGIC_PATTERN = '/(CHNKWKS|CHNKINK)/';

    /**
     * TEXT block size.
     */
    const TEXT_BLOCK = 0x0E00;

    /**
     * Entry magic tag value.
     */
    const ENTRY_MAGIC = 0x01F8;

    /**
     * Extract text from a WPS file.
     *
     * @param string $fileName Path to the WPS file
     *
     * @return string Extracted text content
     */
    public function extractText($fileName)
    {
        // Check if file exists
        if (!file_exists($fileName)) {
            throw new Exception("File not found: {$fileName}");
        }

        // Read file contents
        $fileContent = file_get_contents($fileName);
        if ($fileContent === false) {
            throw new Exception("Unable to read file: {$fileName}");
        }

        // Check for OLE format
        if (substr($fileContent, 0, 8) === "\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1") {
            return $this->extractTextFromOleDocument($fileName);
        }

        // Check for WPS magic
        if (!preg_match(self::WPS_MAGIC_PATTERN, $fileContent, $matches, PREG_OFFSET_CAPTURE)) {
            throw new Exception("No 'Magic' block: not a valid WPS file");
        }

        $magicType = $matches[1][0];
        $headersStart = $matches[0][1];

        if ($magicType === 'CHNKINK') {
            // Check WPS version
            $versionData = unpack('v', substr($fileContent, $headersStart - 2, 2));
            if (!is_array($versionData) || !isset($versionData[1])) {
                throw new Exception('Unable to read WPS version');
            }
            $version = $versionData[1];
            if ($version < 8) {
                throw new Exception('Unable to convert a WPS file prior to version 8');
            }
        }

        // Get entries position and total entries
        $entriesPos = $headersStart + 24;
        if (strlen($fileContent) < $headersStart + 14) {
            throw new Exception('File corrupt: not enough data for total entries');
        }
        $data = unpack('x12/vTotalEntries', substr($fileContent, $headersStart, 14));
        if (!is_array($data) || !isset($data['TotalEntries'])) { // Ensure valid unpacked data
            throw new Exception('File corrupt: unable to retrieve total entries');
        }
        $totalEntries = $data['TotalEntries'];

        // Process entries to find TEXT section
        $textData = '';
        while (true) {
            [$entries, $nextOffset, $textHeaderOffset, $textSize] =
                $this->processEntries(substr($fileContent, $entriesPos));

            if ($textSize > 0) {
                // TEXT section found
                $textOffset = $textHeaderOffset + $headersStart;

                // Get text from main block
                $blockSize = min(self::TEXT_BLOCK, $textSize);
                $textData = substr($fileContent, $textOffset, (int) $blockSize);
                $textSize -= $blockSize;

                // Handle additional blocks if present
                if ($textSize > 0) {
                    $textOffset = 0x800; // Second block location
                    $blockSize = min(self::TEXT_BLOCK, $textSize);
                    $textData .= substr($fileContent, $textOffset, (int) $blockSize);
                    $textSize -= $blockSize;
                }

                // Handle any remaining text
                if ($textSize > 0) {
                    $textOffset = $textHeaderOffset + $headersStart + self::TEXT_BLOCK;
                    $textData .= substr($fileContent, $textOffset, (int) $textSize);
                }

                break;
            }

            $totalEntries -= $entries;
            if ($totalEntries > 0 && $nextOffset > 0) {
                $entriesPos = $nextOffset;
            } else {
                throw new Exception('Unable to find TEXT section. File corrupt?');
            }
        }

        // Convert binary text to UTF-16 and then to UTF-8
        return $this->convertToUtf8($textData);
    }

    /**
     * Extract text from an OLE Compound Document formatted WPS file.
     *
     * @param string $fileName Path to the WPS file
     *
     * @return string Extracted text content
     */
    private function extractTextFromOleDocument($fileName)
    {
        // This is the improved method from the second blog post
        // For now we'll implement a simple version that extracts the CONTENTS stream
        // and processes it similar to the direct binary approach

        // TODO: Implement proper OLE Compound Document reader
        // For now, we'll extract all text content we can find

        $fileContent = file_get_contents($fileName);
        $text = '';

        if (!is_string($fileContent)) {
            return $text;
        }

        // Look for UTF-16 encoded text blocks (common in WPS files)
        preg_match_all('/(?:[\x20-\x7E]\x00){4,}/', $fileContent, $matches);

        if (!empty($matches[0])) {
            foreach ($matches[0] as $match) {
                $text .= $this->convertToUtf8($match) . "\n";
            }
        }

        return $text;
    }

    /**
     * Process entries in the WPS file to find the TEXT section.
     *
     * @param string $entryBuff The buffer containing entries
     *
     * @return array Array with entries count, next offset, text header offset and text size
     */
    private function processEntries($entryBuff)
    {
        // Check if the buffer has enough data
        if (strlen($entryBuff) < 8) {
            throw new Exception('Invalid format - Entry buffer too short');
        }

        // Unpack entry header
        $data = unpack('vmagic/vlocal/Inext_offset', substr($entryBuff, 0, 8));
        if (!is_array($data) || !isset($data['magic'], $data['local'], $data['next_offset'])) { // Ensure valid unpacked data
            throw new Exception('Invalid format - Entry header unpacking failed');
        }

        if ($data['magic'] != self::ENTRY_MAGIC) {
            throw new Exception('Invalid format - Entry magic tag incorrect');
        }

        $local = $data['local'];
        $nextOffset = $data['next_offset'];
        $entryPos = 0x08; // 2 WORDs & 1 DWORD

        // Process each entry
        for ($i = 0; $i < $local; ++$i) {
            // Get entry size
            if (strlen($entryBuff) < $entryPos + 2) {
                throw new Exception('Invalid format - Entry buffer too short');
            }
            $sizeData = unpack('v', substr($entryBuff, $entryPos, 2));
            if (!is_array($sizeData) || count($sizeData) === 0) {
                throw new Exception('Invalid format - Unable to unpack entry size');
            }
            $size = $sizeData[1];

            // Get name, offset and size
            if (strlen($entryBuff) < $entryPos + $size) {
                throw new Exception('Invalid format - Entry buffer too short');
            }
            $entryData = substr($entryBuff, $entryPos, $size);
            $entryInfo = unpack('x2/a4name/x10/Ioffset/Isize', $entryData);
            if (!is_array($entryInfo) || !isset($entryInfo['name'], $entryInfo['offset'], $entryInfo['size'])) { // Ensure valid unpacked data
                throw new Exception('Invalid format - Entry data unpacking failed');
            }

            if ($entryInfo['name'] === 'TEXT') {
                // Success! Found TEXT section
                return [$local, 0, $entryInfo['offset'], $entryInfo['size']];
            }

            $entryPos += $size;
        }

        // No TEXT section found in this block, need to continue to next block
        return [$local, $nextOffset, 0, 0];
    }

    /**
     * Convert UTF-16 text to UTF-8.
     *
     * @param string $text Text in UTF-16 format
     *
     * @return string Text in UTF-8 format
     */
    private function convertToUtf8($text)
    {
        // Remove carriage returns and convert to UTF-8
        $text = preg_replace('/\r/', "\r\n", $text);

        // Check if it's UTF-16LE (common in Windows files)
        if (substr($text, 0, 2) === "\xFF\xFE") {
            return mb_convert_encoding(substr($text, 2), 'UTF-8', 'UTF-16LE');
        }

        // Try to convert as UTF-16LE first (most WPS files use this)
        $result = @mb_convert_encoding($text, 'UTF-8', 'UTF-16LE');

        // If conversion looks good (no replacement characters), use it
        if (strpos($result, '�') === false) {
            return $result;
        }

        // Otherwise try UTF-16BE
        return mb_convert_encoding($text, 'UTF-8', 'UTF-16BE');
    }
}
