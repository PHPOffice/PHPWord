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

namespace PhpOffice\PhpWord\SimpleType;

use PhpOffice\PhpWord\Shared\AbstractEnum;

/**
 * Colors.
 * See https://learn.microsoft.com/en-us/dotnet/api/documentformat.openxml.drawing.presetcolor
 * See https://www.datypic.com/sc/ooxml/t-a_ST_PresetColorVal.html.
 * See https://c-rex.net/samples/ooxml/e1/Part4/OOXML_P4_DOCX_ST_PresetColorVal_topic_ID0ELA5NB.html.
 *
 * Highlight colors limited to certain select colors.
 * See https://c-rex.net/samples/ooxml/e1/Part4/OOXML_P4_DOCX_ST_HighlightColor_topic_ID0E4PY2.html.
 *
 * Highlight but not foreground: GREEN, WHITE
 * Foreground but not highlight: DARKGRAY, LIGHTGREEN
 */
final class Color extends AbstractEnum
{
    const AQUA = 'aqua';
    const BLACK = 'black'; // highlight color, foreground
    const BLUE = 'blue'; // highlight color, foreground
    const BROWN = 'brown';
    const CYAN = 'cyan'; // highlight color, foreground
    const DARKBLUE = 'darkBlue'; // highlight color, foreground
    const DARKCYAN = 'darkCyan'; // highlight color, foreground
    const DARKGRAY = 'darkGray'; // foreground
    const DARKGREEN = 'darkGreen'; // highlight color, foreground
    const DARKMAGENTA = 'darkMagenta'; // highlight color, foreground
    const DARKORANGE = 'darkOrange';
    const DARKRED = 'darkRed'; // highlight color, foreground
    const DARKVIOLET = 'darkViolet';
    const DARKYELLOW = 'darkYellow'; // highlight color, foreground
    const FUCHSIA = 'fuchsia';
    const GOLD = 'gold';
    const GRAY = 'gray';
    const GREEN = 'green'; // highlight color
    const LIGHTBLUE = 'lightBlue';
    const LIGHTCYAN = 'lightCyan';
    const LIGHTGRAY = 'lightGray'; // highlight color, foreground
    const LIGHTGREEN = 'lightGreen'; // foreground
    const LIGHTPINK = 'lightPink';
    const LIGHTYELLOW = 'lightYellow';
    const LIME = 'lime';
    const MAGENTA = 'magenta'; // highlight color, foreground
    const MAROON = 'maroon';
    const NAVY = 'navy';
    const OLIVE = 'olive';
    const ORANGE = 'orange';
    const PINK = 'pink';
    const PURPLE = 'purple';
    const RED = 'red'; // highlight color, foreground
    const SILVER = 'silver';
    const TAN = 'tan';
    const TEAL = 'teal';
    const TURQUOISE = 'turqoise';
    const VIOLET = 'violet';
    const WHITE = 'white'; // highlight color
    const YELLOW = 'yellow'; // highlight color, foreground
}
