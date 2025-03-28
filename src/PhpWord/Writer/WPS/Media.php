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

namespace PhpOffice\PhpWord\Writer\WPS;

/**
 * WPS Media collection
 */
class Media extends \PhpOffice\PhpWord\Writer\Word2007\Media
{
    /**
     * Media elements
     *
     * @var array
     */
    private static $elements = [
        'section' => [],
        'header' => [],
        'footer' => [],
    ];

    /**
     * Add new media element
     */
    public static function addElement(string $source, string $target, string $type, string $imageType = null): void
    {
        if (!in_array($type, ['header', 'footer', 'section'])) {
            return;
        }

        self::$elements[$type][] = ['source' => $source, 'target' => $target, 'type' => $imageType];
    }

    /**
     * Get media elements
     */
    public static function getElements(string $type = null): array
    {
        if ($type !== null) {
            return self::$elements[$type] ?? [];
        }

        return self::$elements;
    }

    /**
     * Clear media elements
     */
    public static function clearElements(): void
    {
        self::$elements = [
            'section' => [],
            'header' => [],
            'footer' => [],
        ];
    }
}
