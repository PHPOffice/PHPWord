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
 * Serializes the Works 7/8 CONTENTS stream.
 *
 * @internal
 */
final class Document
{
    /** Encode document text and font names without a byte-order mark. */
    public static function encodeText(string $text): string
    {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($text, 'UTF-16LE', 'UTF-8');
        }
        if (function_exists('iconv')) {
            $encoded = iconv('UTF-8', 'UTF-16LE', $text);
            if ($encoded !== false) {
                return $encoded;
            }
        }

        throw new Exception('WPS export requires mbstring or iconv to encode UTF-8 text.');
    }

    /**
     * @param array<array{start: int, end: int, properties: string}> $paragraphs
     * @param array<array{start: int, end: int, properties: string}> $runs
     * @param string[] $fonts UTF-8 font names
     */
    public static function encode(string $text, array $paragraphs, array $runs, array $fonts, string $pageProperties = ''): string
    {
        $paragraphPages = self::groupFormatting($paragraphs);
        $fontPages = self::groupFormatting($runs);
        $count = 6 + count($paragraphPages) + count($fontPages) + ($pageProperties === '' ? 0 : 1);
        if ($count > 65535) {
            throw new Exception('The document exceeds the Works chunk index limit.');
        }
        $tableCount = (int) ceil($count / 32);
        $textOffset = (int) ceil((24 + 8 * $tableCount + 24 * $count) / 512) * 512;
        $chunks = [];
        $payload = str_repeat("\0", $textOffset);
        self::appendChunk($chunks, $payload, 'TEXT', 'TEXT', $text);
        $locations = [];
        foreach (['FDPP' => $paragraphPages, 'FDPC' => $fontPages] as $name => $pages) {
            $locations[$name] = [];
            foreach ($pages as $id => $records) {
                $offset = self::appendChunk($chunks, $payload, $name, $name, self::formattingPage($records, $textOffset), $id);
                $locations[$name][] = ['offset' => $offset, 'start' => $records[0]['start'], 'end' => $records[count($records) - 1]['end']];
            }
        }
        self::appendChunk($chunks, $payload, 'FONT', 'FONT', self::fontTable($fonts));
        $zones = pack('V5', 1, 8, 255, (int) (strlen($text) / 2), 0) . pack('vvvV', 10, 0, 0x2200, 1);
        self::appendChunk($chunks, $payload, 'STRS', 'PLC ', $zones);
        self::appendChunk($chunks, $payload, 'SGP ', 'SGP ', pack('vv', 4, 0));
        if ($pageProperties !== '') {
            self::appendChunk($chunks, $payload, 'DOP ', 'DOP ', $pageProperties);
        }
        foreach (['BTEP' => 'FDPP', 'BTEC' => 'FDPC'] as $name => $format) {
            $pages = $locations[$format];
            $index = pack('V3', count($pages), 4, 0);
            foreach ($pages as $page) {
                $index .= pack('V', $textOffset + $page['start']);
            }
            $index .= pack('V', $textOffset + strlen($text));
            foreach ($pages as $page) {
                $index .= pack('V', $page['offset']);
            }
            self::appendChunk($chunks, $payload, $name, 'PLC ', $index);
        }

        $header = 'CHNKWKS ' . pack('v8', 4, 8, $count, 0x300, 0x200, 0, 0x2600, 0);
        foreach (array_chunk($chunks, 32) as $tableId => $table) {
            $next = $tableId + 1 < $tableCount ? strlen($header) + 8 + 24 * count($table) : 0xFFFFFFFF;
            $header .= pack('vvV', 8 + 24 * count($table), count($table), $next);
            foreach ($table as $chunk) {
                $header .= pack('va4vvva4VV', 24, $chunk['name'], $chunk['id'], 1, 0, $chunk['type'], $chunk['offset'], $chunk['size']);
            }
        }

        return substr_replace($payload, $header, 0, strlen($header));
    }

    /** @param array<array{start: int, end: int, properties: string}> $records */
    private static function groupFormatting(array $records): array
    {
        $pages = [];
        $page = [];
        $size = 8;
        foreach ($records as $record) {
            $recordSize = 6 + strlen($record['properties']);
            if ($recordSize + 8 > 512) {
                throw new Exception('A Works formatting record exceeds one page.');
            }
            if ($size + $recordSize > 512) {
                $pages[] = $page;
                $page = [];
                $size = 8;
            }
            $page[] = $record;
            $size += $recordSize;
        }
        if ($page !== []) {
            $pages[] = $page;
        }

        return $pages;
    }

    private static function formattingPage(array $records, int $textOffset): string
    {
        $header = pack('vv', count($records), 1);
        foreach ($records as $record) {
            $header .= pack('V', $textOffset + $record['start']);
        }
        $header .= pack('V', $textOffset + $records[count($records) - 1]['end']);
        $properties = '';
        foreach ($records as $record) {
            $properties = $record['properties'] . $properties;
            $header .= pack('v', 512 - strlen($properties));
        }

        return str_pad($header, 512 - strlen($properties), "\0") . $properties;
    }

    /** @param string[] $fonts */
    private static function fontTable(array $fonts): string
    {
        $offsets = '';
        $records = '';
        foreach ($fonts as $name) {
            $offsets .= pack('V', 4 * count($fonts) + strlen($records));
            $unicode = self::encodeText($name);
            $records .= pack('v', (int) (strlen($unicode) / 2)) . $unicode . pack('V', 0x02010000);
        }

        return pack('V5', strlen($offsets . $records), count($fonts), 0, 4, 0) . $offsets . $records;
    }

    private static function appendChunk(array &$chunks, string &$payload, string $name, string $type, string $data, int $id = 0): int
    {
        $offset = (int) ceil(strlen($payload) / 512) * 512;
        $payload = str_pad($payload, $offset, "\0") . $data;
        $chunks[] = ['name' => $name, 'type' => $type, 'id' => $id, 'offset' => $offset, 'size' => strlen($data)];

        return $offset;
    }
}
