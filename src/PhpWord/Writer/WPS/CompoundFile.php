<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * @license http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\WPS;

use PhpOffice\PhpWord\Exception\Exception;

/**
 * Compound document container for the Works CONTENTS stream (MS-CFB version 3).
 *
 * @internal
 */
final class CompoundFile
{
    private const FREE = 0xFFFFFFFF;
    private const END = 0xFFFFFFFE;
    private const FAT = 0xFFFFFFFD;
    private const DIFAT = 0xFFFFFFFC;

    public static function encode(string $contents): string
    {
        $length = strlen($contents);
        if ($length >= 0x80000000) {
            throw new Exception('A version 3 compound stream must be smaller than 2 GB.');
        }
        $small = $length > 0 && $length < 4096;
        $miniCount = $small ? (int) ceil($length / 64) : 0;
        $dataCount = (int) ceil($length / 512);
        $miniFatCount = $small ? 1 : 0;
        $directory = $dataCount + $miniFatCount;
        $baseCount = $directory + 1;
        $fatCount = 0;
        $difatCount = 0;
        do {
            $previous = [$fatCount, $difatCount];
            $fatCount = (int) ceil(($baseCount + $fatCount + $difatCount) / 128);
            $difatCount = (int) ceil(max(0, $fatCount - 109) / 127);
        } while ($previous !== [$fatCount, $difatCount]);

        $fatIds = range($baseCount, $baseCount + $fatCount - 1);
        $difatStart = $baseCount + $fatCount;
        $fat = [];
        for ($index = 0; $index < $fatCount * 128; ++$index) {
            $fat[] = self::FREE;
        }
        for ($sector = 0; $sector < $dataCount; ++$sector) {
            $fat[$sector] = $sector + 1 < $dataCount ? $sector + 1 : self::END;
        }
        if ($small) {
            $fat[$dataCount] = self::END;
        }
        $fat[$directory] = self::END;
        foreach ($fatIds as $sector) {
            $fat[$sector] = self::FAT;
        }
        for ($index = 0; $index < $difatCount; ++$index) {
            $fat[$difatStart + $index] = self::DIFAT;
        }

        $header = "\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1" . str_repeat("\0", 16);
        $header .= pack('v5', 0x3E, 3, 0xFFFE, 9, 6) . str_repeat("\0", 6);
        $header .= pack(
            'V9',
            0,
            $fatCount,
            $directory,
            0,
            4096,
            $small ? $dataCount : self::END,
            $miniFatCount,
            $difatCount ? $difatStart : self::END,
            $difatCount
        );
        $header .= pack('V*', ...array_pad(array_slice($fatIds, 0, 109), 109, self::FREE));

        $output = $header . str_pad($contents, $dataCount * 512, "\0");
        if ($small) {
            $miniFat = array_fill(0, 128, self::FREE);
            for ($index = 0; $index < $miniCount; ++$index) {
                $miniFat[$index] = $index + 1 < $miniCount ? $index + 1 : self::END;
            }
            $output .= pack('V*', ...$miniFat);
        }
        $output .= self::directoryEntry('Root Entry', 5, $small ? 0 : self::END, $miniCount * 64, 1);
        $output .= self::directoryEntry('CONTENTS', 2, $length ? 0 : self::END, $length, self::FREE);
        $output .= str_repeat("\0", 256);
        $output .= pack('V*', ...$fat);
        for ($index = 0; $index < $difatCount; ++$index) {
            $ids = array_pad(array_slice($fatIds, 109 + $index * 127, 127), 127, self::FREE);
            $ids[] = $index + 1 < $difatCount ? $difatStart + $index + 1 : self::END;
            $output .= pack('V*', ...$ids);
        }

        return $output;
    }

    private static function directoryEntry(string $name, int $type, int $start, int $size, int $child): string
    {
        $unicode = '';
        foreach (str_split($name) as $character) {
            $unicode .= $character . "\0";
        }
        $unicode .= "\0\0";

        return str_pad($unicode, 64, "\0")
            . pack('vCCVVV', strlen($unicode), $type, 1, self::FREE, self::FREE, $child)
            . str_repeat("\0", 36)
            . pack('V3', $start, $size, 0);
    }
}
