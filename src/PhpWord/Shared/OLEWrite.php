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
 * OLE compound document writer.
 *
 * Builds a version 3 compound file (512 bytes sectors) from a set of named
 * streams. It is the counterpart of OLERead, used by the writers of the
 * binary Microsoft formats.
 *
 * @see https://learn.microsoft.com/en-us/openspecs/windows_protocols/ms-cfb
 * @since 1.5.0
 */
class OLEWrite
{
    /** Size of a sector, in bytes. */
    const SECTOR_SIZE = 512;

    /** Size of a mini sector, in bytes. */
    const MINI_SECTOR_SIZE = 64;

    /** Streams smaller than this size are stored in the mini stream. */
    const MINI_STREAM_CUTOFF = 4096;

    /** Number of sector numbers stored in a sector of the FAT. */
    const FAT_ENTRIES_PER_SECTOR = 128;

    /** Number of FAT sector numbers stored in the header. */
    const HEADER_DIFAT_ENTRIES = 109;

    /** Maximum length of a stream name, in characters. */
    const MAX_NAME_LENGTH = 31;

    const FREESECT = 0xFFFFFFFF;

    const ENDOFCHAIN = 0xFFFFFFFE;

    const FATSECT = 0xFFFFFFFD;

    const NOSTREAM = 0xFFFFFFFF;

    const TYPE_STREAM = 2;

    const TYPE_ROOT = 5;

    /**
     * Streams, name => content.
     *
     * @var array<string, string>
     */
    private $streams = [];

    /**
     * Add a stream to the document.
     */
    public function addStream(string $name, string $content): self
    {
        if ($name === '' || strlen($name) > self::MAX_NAME_LENGTH) {
            throw new Exception("Invalid OLE stream name: {$name}.");
        }
        $this->streams[$name] = $content;

        return $this;
    }

    /**
     * Get the binary content of the compound document.
     */
    public function write(): string
    {
        $names = array_keys($this->streams);
        usort($names, [self::class, 'compareNames']);

        [$miniStream, $miniFat, $miniStarts] = $this->buildMiniStream($names);

        $numDirectorySectors = (int) ceil((count($names) + 1) / 4);
        $numMiniFatSectors = (int) ceil(count($miniFat) / self::FAT_ENTRIES_PER_SECTOR);
        $numMiniStreamSectors = (int) ceil(strlen($miniStream) / self::SECTOR_SIZE);

        // Sectors are allocated in writing order: directory, mini FAT, mini
        // stream, streams stored outside of the mini stream, then the FAT.
        $sector = $numDirectorySectors + $numMiniFatSectors + $numMiniStreamSectors;
        $starts = $miniStarts;
        $lengths = [];
        foreach ($names as $name) {
            $length = strlen($this->streams[$name]);
            $lengths[$name] = $length;
            if ($length >= self::MINI_STREAM_CUTOFF) {
                $starts[$name] = $sector;
                $sector += (int) ceil($length / self::SECTOR_SIZE);
            }
        }

        $numFatSectors = 0;
        while ($numFatSectors * self::FAT_ENTRIES_PER_SECTOR < $sector + $numFatSectors) {
            ++$numFatSectors;
        }
        if ($numFatSectors > self::HEADER_DIFAT_ENTRIES) {
            throw new Exception('OLE document too large to be written.');
        }

        $fat = array_fill(0, $numFatSectors * self::FAT_ENTRIES_PER_SECTOR, self::FREESECT);
        $this->addChain($fat, 0, $numDirectorySectors);
        $this->addChain($fat, $numDirectorySectors, $numMiniFatSectors);
        $this->addChain($fat, $numDirectorySectors + $numMiniFatSectors, $numMiniStreamSectors);
        foreach ($names as $name) {
            if ($lengths[$name] >= self::MINI_STREAM_CUTOFF) {
                $this->addChain($fat, $starts[$name], (int) ceil($lengths[$name] / self::SECTOR_SIZE));
            }
        }
        for ($i = 0; $i < $numFatSectors; ++$i) {
            $fat[$sector + $i] = self::FATSECT;
        }

        $content = $this->writeHeader($numFatSectors, $numMiniFatSectors, $numDirectorySectors, $sector);
        $content .= $this->writeDirectory($names, $starts, $lengths, $numDirectorySectors, $numMiniFatSectors, $numMiniStreamSectors, strlen($miniStream));
        $content .= $this->writeFat($miniFat, $numMiniFatSectors);
        $content .= $this->pad($miniStream, self::SECTOR_SIZE);
        foreach ($names as $name) {
            if ($lengths[$name] >= self::MINI_STREAM_CUTOFF) {
                $content .= $this->pad($this->streams[$name], self::SECTOR_SIZE);
            }
        }
        $content .= $this->writeFat($fat, $numFatSectors);

        return $content;
    }

    /**
     * Compare two stream names, using the compound file ordering.
     */
    private static function compareNames(string $nameA, string $nameB): int
    {
        if (strlen($nameA) !== strlen($nameB)) {
            return strlen($nameA) < strlen($nameB) ? -1 : 1;
        }

        return strcmp(strtoupper($nameA), strtoupper($nameB));
    }

    /**
     * Build the mini stream, its allocation table and the start of each stream in it.
     *
     * @param string[] $names
     *
     * @return array{0: string, 1: int[], 2: array<string, int>}
     */
    private function buildMiniStream(array $names): array
    {
        $miniStream = '';
        $miniFat = [];
        $starts = [];
        foreach ($names as $name) {
            $content = $this->streams[$name];
            if (strlen($content) >= self::MINI_STREAM_CUTOFF) {
                continue;
            }
            if ($content === '') {
                $starts[$name] = self::ENDOFCHAIN;

                continue;
            }
            $starts[$name] = count($miniFat);
            $miniStream .= $this->pad($content, self::MINI_SECTOR_SIZE);
            $this->addChain($miniFat, $starts[$name], (int) ceil(strlen($content) / self::MINI_SECTOR_SIZE));
        }

        return [$miniStream, $miniFat, $starts];
    }

    /**
     * Add a chain of consecutive sectors to an allocation table.
     *
     * @param int[] $table
     */
    private function addChain(array &$table, int $start, int $count): void
    {
        for ($i = 0; $i < $count; ++$i) {
            $table[$start + $i] = ($i === $count - 1) ? self::ENDOFCHAIN : $start + $i + 1;
        }
    }

    /**
     * Write the header of the compound document.
     */
    private function writeHeader(int $numFatSectors, int $numMiniFatSectors, int $numDirectorySectors, int $firstFatSector): string
    {
        $header = "\xd0\xcf\x11\xe0\xa1\xb1\x1a\xe1";
        $header .= str_repeat("\x00", 16); // CLSID
        $header .= pack('vvv', 0x3E, 0x03, 0xFFFE); // minor version, major version, byte order
        $header .= pack('vv', 9, 6); // sector shift, mini sector shift
        $header .= str_repeat("\x00", 6); // reserved
        $header .= pack('V', 0); // number of directory sectors, unused in version 3
        $header .= pack('V', $numFatSectors);
        $header .= pack('V', 0); // first directory sector
        $header .= pack('V', 0); // transaction signature
        $header .= pack('V', self::MINI_STREAM_CUTOFF);
        $header .= pack('V', $numMiniFatSectors > 0 ? $numDirectorySectors : self::ENDOFCHAIN);
        $header .= pack('V', $numMiniFatSectors);
        $header .= pack('V', self::ENDOFCHAIN); // first DIFAT sector
        $header .= pack('V', 0); // number of DIFAT sectors

        for ($i = 0; $i < self::HEADER_DIFAT_ENTRIES; ++$i) {
            $header .= pack('V', $i < $numFatSectors ? $firstFatSector + $i : self::FREESECT);
        }

        return $header;
    }

    /**
     * Write the directory sectors.
     *
     * @param string[] $names
     * @param array<string, int> $starts
     * @param array<string, int> $lengths
     */
    private function writeDirectory(array $names, array $starts, array $lengths, int $numDirectorySectors, int $numMiniFatSectors, int $numMiniStreamSectors, int $miniStreamSize): string
    {
        $nodes = [];
        $rootChild = $this->buildTree(0, count($names) - 1, $nodes);
        // The mini stream is stored right after the mini FAT.
        $rootStart = $numMiniStreamSectors > 0 ? $numDirectorySectors + $numMiniFatSectors : self::ENDOFCHAIN;

        $directory = $this->writeDirectoryEntry('Root Entry', self::TYPE_ROOT, self::NOSTREAM, self::NOSTREAM, $rootChild, $rootStart, $miniStreamSize);
        foreach ($names as $index => $name) {
            $directory .= $this->writeDirectoryEntry(
                $name,
                self::TYPE_STREAM,
                $nodes[$index]['left'],
                $nodes[$index]['right'],
                self::NOSTREAM,
                $starts[$name],
                $lengths[$name]
            );
        }

        return $this->pad($directory, self::SECTOR_SIZE);
    }

    /**
     * Write a directory entry.
     */
    private function writeDirectoryEntry(string $name, int $type, int $left, int $right, int $child, int $start, int $size): string
    {
        $utf16Name = (string) mb_convert_encoding($name, 'UTF-16LE', 'UTF-8');

        $entry = str_pad($utf16Name . "\x00\x00", 64, "\x00");
        $entry .= pack('v', strlen($utf16Name) + 2);
        $entry .= pack('CC', $type, 1); // object type, black node
        $entry .= pack('VVV', $left, $right, $child);
        $entry .= str_repeat("\x00", 16); // CLSID
        $entry .= pack('V', 0); // state bits
        $entry .= str_repeat("\x00", 16); // creation and modification times
        $entry .= pack('V', $start);
        $entry .= pack('VV', $size, 0);

        return $entry;
    }

    /**
     * Build the tree of the directory entries and return the directory index of its root.
     *
     * The entries being sorted, a balanced tree is built by taking the middle
     * entry of each range as its root.
     *
     * @param array<int, array{left: int, right: int}> $nodes
     */
    private function buildTree(int $first, int $last, array &$nodes): int
    {
        if ($first > $last) {
            return self::NOSTREAM;
        }

        $root = intdiv($first + $last, 2);
        $nodes[$root] = [
            'left' => $this->buildTree($first, $root - 1, $nodes),
            'right' => $this->buildTree($root + 1, $last, $nodes),
        ];

        // Directory entries are numbered after the root entry of the document.
        return $root + 1;
    }

    /**
     * Write an allocation table.
     *
     * @param int[] $table
     */
    private function writeFat(array $table, int $numSectors): string
    {
        $content = '';
        foreach ($table as $entry) {
            $content .= pack('V', $entry);
        }

        return str_pad($content, $numSectors * self::SECTOR_SIZE, "\xff\xff\xff\xff");
    }

    /**
     * Pad a content to a multiple of the given size.
     */
    private function pad(string $content, int $size): string
    {
        return str_pad($content, (int) ceil(strlen($content) / $size) * $size, "\x00");
    }
}
