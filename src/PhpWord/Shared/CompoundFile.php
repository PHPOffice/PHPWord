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

namespace PhpOffice\PhpWord\Shared;

use PhpOffice\PhpWord\Exception\Exception;

/**
 * Writer for OLE compound files (MS-CFB version 3, 512-byte sectors).
 *
 * Counterpart of OLERead: given named streams, emit a document that OLERead
 * can open. Used by the Microsoft Works WPS writer.
 *
 * @see https://learn.microsoft.com/en-us/openspecs/windows_protocols/ms-cfb
 */
class CompoundFile
{
    const SECTOR_SIZE = 512;

    const MINI_SECTOR_SIZE = 64;

    const MINI_CUTOFF = 4096;

    const FAT_PER_SECTOR = 128;

    const HEADER_DIFAT = 109;

    const FREESECT = 0xFFFFFFFF;

    const ENDOFCHAIN = 0xFFFFFFFE;

    const FATSECT = 0xFFFFFFFD;

    const NOSTREAM = 0xFFFFFFFF;

    const TYPE_STREAM = 2;

    const TYPE_ROOT = 5;

    /**
     * @var array<string, string>
     */
    private $streams = [];

    public function putStream(string $name, string $bytes): self
    {
        if ($name === '' || strlen($name) > 31) {
            throw new Exception("Invalid compound-file stream name: {$name}.");
        }
        $this->streams[$name] = $bytes;

        return $this;
    }

    public function toBinary(): string
    {
        $names = array_keys($this->streams);
        usort($names, static function (string $left, string $right): int {
            $delta = strlen($left) - strlen($right);
            if ($delta !== 0) {
                return $delta;
            }

            return strcmp(strtoupper($left), strtoupper($right));
        });

        [$miniBytes, $miniFat, $miniStart] = $this->packMiniStream($names);

        $dirSectors = (int) ceil((count($names) + 1) / 4);
        $miniFatSectors = $miniFat === [] ? 0 : (int) ceil(count($miniFat) / self::FAT_PER_SECTOR);
        $miniStreamSectors = $miniBytes === '' ? 0 : (int) ceil(strlen($miniBytes) / self::SECTOR_SIZE);

        $next = $dirSectors + $miniFatSectors + $miniStreamSectors;
        $start = $miniStart;
        $size = [];
        foreach ($names as $name) {
            $length = strlen($this->streams[$name]);
            $size[$name] = $length;
            if ($length >= self::MINI_CUTOFF) {
                $start[$name] = $next;
                $next += (int) ceil($length / self::SECTOR_SIZE);
            }
        }

        $fatSectors = 0;
        while ($fatSectors * self::FAT_PER_SECTOR < $next + $fatSectors) {
            ++$fatSectors;
        }
        if ($fatSectors > self::HEADER_DIFAT) {
            throw new Exception('Compound file exceeds the header DIFAT capacity.');
        }

        $fat = array_fill(0, $fatSectors * self::FAT_PER_SECTOR, self::FREESECT);
        $this->link($fat, 0, $dirSectors);
        $this->link($fat, $dirSectors, $miniFatSectors);
        $this->link($fat, $dirSectors + $miniFatSectors, $miniStreamSectors);
        foreach ($names as $name) {
            if ($size[$name] >= self::MINI_CUTOFF) {
                $this->link($fat, $start[$name], (int) ceil($size[$name] / self::SECTOR_SIZE));
            }
        }
        for ($i = 0; $i < $fatSectors; ++$i) {
            $fat[$next + $i] = self::FATSECT;
        }

        $out = $this->header($fatSectors, $miniFatSectors, $dirSectors, $next);
        $out .= $this->directory($names, $start, $size, $dirSectors, $miniFatSectors, $miniStreamSectors, strlen($miniBytes));
        $out .= $this->fatBytes($miniFat, $miniFatSectors);
        $out .= $this->align($miniBytes, self::SECTOR_SIZE);
        foreach ($names as $name) {
            if ($size[$name] >= self::MINI_CUTOFF) {
                $out .= $this->align($this->streams[$name], self::SECTOR_SIZE);
            }
        }
        $out .= $this->fatBytes($fat, $fatSectors);

        return $out;
    }

    /**
     * @param string[] $names
     *
     * @return array{0: string, 1: int[], 2: array<string, int>}
     */
    private function packMiniStream(array $names): array
    {
        $bytes = '';
        $fat = [];
        $start = [];
        foreach ($names as $name) {
            $payload = $this->streams[$name];
            if (strlen($payload) >= self::MINI_CUTOFF) {
                continue;
            }
            if ($payload === '') {
                $start[$name] = self::ENDOFCHAIN;

                continue;
            }
            $start[$name] = count($fat);
            $bytes .= $this->align($payload, self::MINI_SECTOR_SIZE);
            $this->link($fat, $start[$name], (int) ceil(strlen($payload) / self::MINI_SECTOR_SIZE));
        }

        return [$bytes, $fat, $start];
    }

    /**
     * @param int[] $table
     */
    private function link(array &$table, int $first, int $count): void
    {
        for ($i = 0; $i < $count; ++$i) {
            $table[$first + $i] = ($i === $count - 1) ? self::ENDOFCHAIN : $first + $i + 1;
        }
    }

    private function header(int $fatSectors, int $miniFatSectors, int $dirSectors, int $firstFat): string
    {
        $header = "\xd0\xcf\x11\xe0\xa1\xb1\x1a\xe1";
        $header .= str_repeat("\x00", 16);
        $header .= pack('vvv', 0x3E, 0x03, 0xFFFE);
        $header .= pack('vv', 9, 6);
        $header .= str_repeat("\x00", 6);
        $header .= pack('V', 0);
        $header .= pack('V', $fatSectors);
        $header .= pack('V', 0);
        $header .= pack('V', 0);
        $header .= pack('V', self::MINI_CUTOFF);
        $header .= pack('V', $miniFatSectors > 0 ? $dirSectors : self::ENDOFCHAIN);
        $header .= pack('V', $miniFatSectors);
        $header .= pack('V', self::ENDOFCHAIN);
        $header .= pack('V', 0);
        for ($i = 0; $i < self::HEADER_DIFAT; ++$i) {
            $header .= pack('V', $i < $fatSectors ? $firstFat + $i : self::FREESECT);
        }

        return $header;
    }

    /**
     * @param string[] $names
     * @param array<string, int> $start
     * @param array<string, int> $size
     */
    private function directory(
        array $names,
        array $start,
        array $size,
        int $dirSectors,
        int $miniFatSectors,
        int $miniStreamSectors,
        int $miniStreamSize
    ): string {
        $nodes = [];
        $child = $this->balancedTree(0, count($names) - 1, $nodes);
        $rootStart = $miniStreamSectors > 0 ? $dirSectors + $miniFatSectors : self::ENDOFCHAIN;

        $dir = $this->dirEntry('Root Entry', self::TYPE_ROOT, self::NOSTREAM, self::NOSTREAM, $child, $rootStart, $miniStreamSize);
        foreach ($names as $index => $name) {
            $dir .= $this->dirEntry(
                $name,
                self::TYPE_STREAM,
                $nodes[$index]['left'],
                $nodes[$index]['right'],
                self::NOSTREAM,
                $start[$name],
                $size[$name]
            );
        }

        return $this->align($dir, self::SECTOR_SIZE);
    }

    /**
     * @param array<int, array{left: int, right: int}> $nodes
     */
    private function balancedTree(int $lo, int $hi, array &$nodes): int
    {
        if ($lo > $hi) {
            return self::NOSTREAM;
        }
        $mid = intdiv($lo + $hi, 2);
        $nodes[$mid] = [
            'left' => $this->balancedTree($lo, $mid - 1, $nodes),
            'right' => $this->balancedTree($mid + 1, $hi, $nodes),
        ];

        return $mid + 1;
    }

    private function dirEntry(string $name, int $type, int $left, int $right, int $child, int $start, int $size): string
    {
        $utf16 = (string) mb_convert_encoding($name, 'UTF-16LE', 'UTF-8');
        $entry = str_pad($utf16 . "\x00\x00", 64, "\x00");
        $entry .= pack('v', strlen($utf16) + 2);
        $entry .= pack('CC', $type, 1);
        $entry .= pack('VVV', $left, $right, $child);
        $entry .= str_repeat("\x00", 16);
        $entry .= pack('V', 0);
        $entry .= str_repeat("\x00", 16);
        $entry .= pack('V', $start);
        $entry .= pack('VV', $size, 0);

        return $entry;
    }

    /**
     * @param int[] $table
     */
    private function fatBytes(array $table, int $sectors): string
    {
        $bytes = '';
        foreach ($table as $entry) {
            $bytes .= pack('V', $entry);
        }

        return str_pad($bytes, $sectors * self::SECTOR_SIZE, "\xff\xff\xff\xff");
    }

    private function align(string $bytes, int $size): string
    {
        if ($bytes === '' || $size === 0) {
            return $bytes;
        }

        return str_pad($bytes, (int) ceil(strlen($bytes) / $size) * $size, "\x00");
    }
}
