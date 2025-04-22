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

use PhpOffice\PhpWord\Element\AbstractContainer;
use PhpOffice\PhpWord\Element\AbstractElement;
use PhpOffice\PhpWord\Element\Image;

/**
 * WPS Media handler.
 *
 * @since 0.18.0
 */
class Media
{
    /**
     * Media elements collection, categorized by document part (section, header, footer).
     *
     * @var array<string, array<int, array>>
     */
    private static $elements = [];

    /**
     * Add a media element to the collection.
     *
     * @param string          $docPart e.g., 'section', 'header', 'footer'
     * @param AbstractElement $element The media element (e.g., Image)
     */
    public static function addElement(string $docPart, AbstractElement $element): void
    {
        if (!isset(self::$elements[$docPart])) {
            self::$elements[$docPart] = [];
        }

        if ($element instanceof Image) {
            $mediaIndex = count(self::$elements[$docPart]) + 1;
            $element->setMediaIndex($mediaIndex);
            $element->setTarget("image{$mediaIndex}.{$element->getImageExtension()}");

            self::$elements[$docPart][] = [
                'type' => 'image', // Add the missing 'type' index
                'source' => $element->getSource(),
                'target' => $element->getTarget(),
                'isMemImage' => $element->isMemImage(),
                'imageString' => $element->isMemImage() ? $element->getImageString() : null,
            ];
        }
        // Add handling for other media types (OLEObject) if needed
    }

    /**
     * Get all media elements for a specific document part.
     *
     * @param string $docPart e.g., 'section', 'header', 'footer'
     *
     * @return array<int, array>
     */
    public static function getElements(string $docPart): array
    {
        return self::$elements[$docPart] ?? [];
    }

    /**
     * Clear all stored media elements.
     */
    public static function clearElements(): void
    {
        self::$elements = [];
    }

    /**
     * Recursively collect media elements from a container.
     *
     * @param string            $docPart   The document part ('section', 'header', 'footer')
     * @param AbstractContainer $container The container element to traverse
     */
    public static function collectMediaRelations(string $docPart, AbstractContainer $container): void
    {
        foreach ($container->getElements() as $element) {
            if ($element instanceof Image) {
                self::addElement($docPart, $element);
            } elseif ($element instanceof AbstractContainer) {
                // Recursively check sub-containers
                self::collectMediaRelations($docPart, $element);
            }
            // Add checks for other media types (OLEObject) if needed
        }
    }
}
