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

use Exception;
use PhpOffice\PhpWord\Settings;
use PhpOffice\WMF\Reader\Detector;
use PhpOffice\WMF\Reader\EMF\GD as EMFReader;
use PhpOffice\WMF\Reader\WMF\GD as WMFReader;

/**
 * Windows metafiles (WMF, EMF & EMF+) helper, based on phpoffice/wmf.
 *
 * The support is optional : it requires the phpoffice/wmf library and the GD extension.
 *
 * EMF+ files are EMF files containing EMF+ records : they are stored as EMF files.
 * The GD readers are used : they are pure PHP and don't depend on external delegates (like Imagick).
 */
class Metafile
{
    const MIME_WMF = 'image/x-wmf';
    const MIME_EMF = 'image/x-emf';

    /**
     * Returns if the metafiles are supported : phpoffice/wmf is installed and the GD extension is loaded.
     */
    public static function isSupported(): bool
    {
        return class_exists(Detector::class) && extension_loaded('gd');
    }

    /**
     * Returns the mime type of a metafile (`image/x-wmf` or `image/x-emf`).
     *
     * Returns null if the content is not a metafile or if the metafiles are not supported.
     */
    public static function getMimeType(string $content): ?string
    {
        if (!self::isSupported()) {
            return null; // @codeCoverageIgnore
        }

        switch (Detector::detect($content)) {
            case Detector::TYPE_WMF:
                return self::MIME_WMF;
            case Detector::TYPE_EMF:
            case Detector::TYPE_EMFPLUS:
                return self::MIME_EMF;
            default:
                return null;
        }
    }

    /**
     * Returns if the mime type is the one of a metafile.
     */
    public static function isMimeType(?string $mimeType): bool
    {
        return in_array($mimeType, [self::MIME_WMF, self::MIME_EMF], true);
    }

    /**
     * Returns the extension of a metafile mime type (`wmf` or `emf`).
     */
    public static function getExtension(string $mimeType): ?string
    {
        switch ($mimeType) {
            case self::MIME_WMF:
                return 'wmf';
            case self::MIME_EMF:
                return 'emf';
            default:
                return null;
        }
    }

    /**
     * Returns the size of a metafile and its mime type, like getimagesize() : [width, height, mimeType].
     *
     * Returns null if the content is not a metafile, if it can't be read or if the metafiles are not supported.
     *
     * @return null|array{0: int, 1: int, 2: string}
     */
    public static function getImageSize(string $content): ?array
    {
        $mimeType = self::getMimeType($content);
        if ($mimeType === null) {
            return null;
        }
        $reader = self::load($content, $mimeType);
        if ($reader === null) {
            return null;
        }

        $resource = $reader->getResource();

        // GdImage since PHP 8.0, resource before
        // @phpstan-ignore-next-line
        return [imagesx($resource), imagesy($resource), $mimeType];
    }

    /**
     * Converts a metafile to PNG.
     *
     * Returns null if the content is not a metafile, if it can't be read or if the metafiles are not supported.
     */
    public static function convertToPng(string $content): ?string
    {
        $mimeType = self::getMimeType($content);
        if ($mimeType === null) {
            return null;
        }
        $reader = self::load($content, $mimeType);
        if ($reader === null) {
            return null;
        }

        $tempFilename = tempnam(Settings::getTempDir(), 'PHPWordMetafile');
        if ($tempFilename === false) {
            return null; // @codeCoverageIgnore
        }

        $png = $reader->save($tempFilename, 'png') ? file_get_contents($tempFilename) : false;
        @unlink($tempFilename);

        return $png === false ? null : $png;
    }

    /**
     * @return null|EMFReader|WMFReader
     */
    private static function load(string $content, string $mimeType)
    {
        $reader = $mimeType === self::MIME_WMF ? new WMFReader() : new EMFReader();

        try {
            if (!$reader->loadFromString($content)) {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }

        return $reader;
    }
}
