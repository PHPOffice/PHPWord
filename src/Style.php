<?php
/**
 * PhpWord
 *
 * Copyright (c) 2014 PhpWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PhpWord
 * @package    PhpWord
 * @copyright  Copyright (c) 2014 PhpWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.8.0
 */

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Style\TableFull;

class Style
{
    /**
     * @var array
     */
    private static $_styleElements = array();

    /**
     * @param string $styleName
     * @param array $styles
     */
    public static function addParagraphStyle($styleName, $styles)
    {
        if (!array_key_exists($styleName, self::$_styleElements)) {
            $style = new Paragraph();
            foreach ($styles as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $style->setStyleValue($key, $value);
            }

            self::$_styleElements[$styleName] = $style;
        }
    }

    /**
     * @param string $styleName
     * @param array $styleFont
     * @param array $styleParagraph
     */
    public static function addFontStyle($styleName, $styleFont, $styleParagraph = null)
    {
        if (!array_key_exists($styleName, self::$_styleElements)) {
            $font = new Font('text', $styleParagraph);
            foreach ($styleFont as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $font->setStyleValue($key, $value);
            }
            self::$_styleElements[$styleName] = $font;
        }
    }

    /**
     * @param string $styleName
     * @param array $styles
     */
    public static function addLinkStyle($styleName, $styles)
    {
        if (!array_key_exists($styleName, self::$_styleElements)) {
            $style = new Font('link');
            foreach ($styles as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $style->setStyleValue($key, $value);
            }

            self::$_styleElements[$styleName] = $style;
        }
    }

    /**
     * @param string $styleName
     * @param array $styles
     */
    public static function addTableStyle($styleName, $styleTable, $styleFirstRow = null)
    {
        if (!array_key_exists($styleName, self::$_styleElements)) {
            $style = new TableFull($styleTable, $styleFirstRow);

            self::$_styleElements[$styleName] = $style;
        }
    }

    /**
     * @param string $styleName
     * @param array $styleFont
     * @param array $styleParagraph
     */
    public static function addTitleStyle($titleCount, $styleFont, $styleParagraph = null)
    {
        $styleName = 'Heading_' . $titleCount;
        if (!array_key_exists($styleName, self::$_styleElements)) {
            $font = new Font('title', $styleParagraph);
            foreach ($styleFont as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $font->setStyleValue($key, $value);
            }

            self::$_styleElements[$styleName] = $font;
        }
    }

    /**
     * @param array $styles Paragraph style definition
     */
    public static function setDefaultParagraphStyle($styles)
    {
        self::addParagraphStyle('Normal', $styles);
    }

    /**
     * Get all styles
     *
     * @return PhpOffice\PhpWord\Style\Font[]
     */
    public static function getStyles()
    {
        return self::$_styleElements;
    }

    /**
     * @param string
     */
    public static function getStyle($styleName)
    {
        if (array_key_exists($styleName, self::$_styleElements)) {
            return self::$_styleElements[$styleName];
        } else {
            return null;
        }
    }
}