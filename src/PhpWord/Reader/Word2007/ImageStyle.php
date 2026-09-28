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

namespace PhpOffice\PhpWord\Reader\Word2007;

use DOMElement;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Shared\XMLReader;
use PhpOffice\PhpWord\Style\Frame;

/**
 * Size, position and wrapping of an image, read from VML or DrawingML, as an array for Style\Image.
 */
class ImageStyle
{
    private const EMU_PER_POINT = 12700;

    private const H_POS = [Frame::POS_ABSOLUTE, Frame::POS_LEFT, Frame::POS_CENTER, Frame::POS_RIGHT, Frame::POS_INSIDE, Frame::POS_OUTSIDE];
    private const V_POS = [Frame::POS_ABSOLUTE, Frame::POS_TOP, Frame::POS_CENTER, Frame::POS_BOTTOM, Frame::POS_INSIDE, Frame::POS_OUTSIDE];

    /** mso-position-horizontal-relative, and relativeFrom of wp:positionH */
    private const H_POS_REL_TO = [
        'margin' => Frame::POS_RELTO_MARGIN,
        'page' => Frame::POS_RELTO_PAGE,
        'text' => Frame::POS_RELTO_COLUMN,
        'column' => Frame::POS_RELTO_COLUMN,
        'char' => Frame::POS_RELTO_CHAR,
        'character' => Frame::POS_RELTO_CHAR,
        'left-margin-area' => Frame::POS_RELTO_LMARGIN,
        'leftMargin' => Frame::POS_RELTO_LMARGIN,
        'right-margin-area' => Frame::POS_RELTO_RMARGIN,
        'rightMargin' => Frame::POS_RELTO_RMARGIN,
        'inner-margin-area' => Frame::POS_RELTO_IMARGIN,
        'insideMargin' => Frame::POS_RELTO_IMARGIN,
        'outer-margin-area' => Frame::POS_RELTO_OMARGIN,
        'outsideMargin' => Frame::POS_RELTO_OMARGIN,
    ];

    /** mso-position-vertical-relative, and relativeFrom of wp:positionV */
    private const V_POS_REL_TO = [
        'margin' => Frame::POS_RELTO_MARGIN,
        'page' => Frame::POS_RELTO_PAGE,
        'text' => Frame::POS_RELTO_TEXT,
        'paragraph' => Frame::POS_RELTO_TEXT,
        'line' => Frame::POS_RELTO_LINE,
        'top-margin-area' => Frame::POS_RELTO_TMARGIN,
        'topMargin' => Frame::POS_RELTO_TMARGIN,
        'bottom-margin-area' => Frame::POS_RELTO_BMARGIN,
        'bottomMargin' => Frame::POS_RELTO_BMARGIN,
        'inner-margin-area' => Frame::POS_RELTO_IMARGIN,
        'insideMargin' => Frame::POS_RELTO_IMARGIN,
        'outer-margin-area' => Frame::POS_RELTO_OMARGIN,
        'outsideMargin' => Frame::POS_RELTO_OMARGIN,
    ];

    private const WRAP = [
        'square' => Frame::WRAP_SQUARE,
        'tight' => Frame::WRAP_TIGHT,
        'through' => Frame::WRAP_THROUGH,
        'topAndBottom' => Frame::WRAP_TOPBOTTOM,
    ];

    /**
     * Read the style of a VML v:shape, as Word and the Word2007 writer write it.
     *
     * @return array<string, float|string>
     */
    public static function readVml(XMLReader $xmlReader, DOMElement $shape): array
    {
        $css = [];
        foreach (explode(';', $shape->getAttribute('style')) as $declaration) {
            $pair = explode(':', $declaration, 2);
            if (count($pair) === 2) {
                $css[strtolower(trim($pair[0]))] = trim($pair[1]);
            }
        }

        $style = [];
        $lengths = [
            'width' => 'width',
            'height' => 'height',
            'margin-left' => 'left',
            'margin-top' => 'top',
            'mso-wrap-distance-top' => 'wrapDistanceTop',
            'mso-wrap-distance-bottom' => 'wrapDistanceBottom',
            'mso-wrap-distance-left' => 'wrapDistanceLeft',
            'mso-wrap-distance-right' => 'wrapDistanceRight',
        ];
        foreach ($lengths as $property => $key) {
            $points = self::cssToPoint($css[$property] ?? '');
            if ($points !== null) {
                $style[$key] = $points;
            }
        }
        $style = array_merge($style, self::position(
            $css['mso-position-horizontal'] ?? '',
            $css['mso-position-horizontal-relative'] ?? '',
            $css['mso-position-vertical'] ?? '',
            $css['mso-position-vertical-relative'] ?? ''
        ));

        $position = $css['position'] ?? '';
        if ($position === Frame::POS_ABSOLUTE || $position === Frame::POS_RELATIVE) {
            $style['pos'] = $position;
            // A floating shape wraps as w10:wrap says, or is behind or in front of the text without it
            $wrap = self::WRAP[(string) $xmlReader->getAttribute('type', $shape, 'w10:wrap')] ?? null;
            if ($wrap === null) {
                $wrap = (int) ($css['z-index'] ?? 0) < 0 ? Frame::WRAP_BEHIND : Frame::WRAP_INFRONT;
            }
            $style['wrap'] = $wrap;
        }

        return $style;
    }

    /**
     * Read the style of a DrawingML wp:inline or wp:anchor.
     *
     * @return array<string, float|string>
     */
    public static function readDrawing(XMLReader $xmlReader, DOMElement $frame): array
    {
        $style = [];
        $lengths = [
            'wp:extent/@cx' => 'width',
            'wp:extent/@cy' => 'height',
            '@distT' => 'wrapDistanceTop',
            '@distB' => 'wrapDistanceBottom',
            '@distL' => 'wrapDistanceLeft',
            '@distR' => 'wrapDistanceRight',
        ];
        foreach ($lengths as $path => $key) {
            $emu = $xmlReader->getValue($path, $frame);
            if (is_numeric($emu)) {
                $style[$key] = $emu / self::EMU_PER_POINT;
            }
        }
        if ($frame->localName !== 'anchor') {
            return $style;
        }

        $style['pos'] = Frame::POS_ABSOLUTE;
        $style = array_merge($style, self::position(
            (string) $xmlReader->getValue('wp:positionH/wp:align', $frame),
            (string) $xmlReader->getAttribute('relativeFrom', $frame, 'wp:positionH'),
            (string) $xmlReader->getValue('wp:positionV/wp:align', $frame),
            (string) $xmlReader->getAttribute('relativeFrom', $frame, 'wp:positionV')
        ));
        foreach (['H' => 'left', 'V' => 'top'] as $axis => $key) {
            $emu = $xmlReader->getValue("wp:position{$axis}/wp:posOffset", $frame);
            if (is_numeric($emu)) {
                $style[$axis === 'H' ? 'hPos' : 'vPos'] = Frame::POS_ABSOLUTE;
                $style[$key] = $emu / self::EMU_PER_POINT;
            }
        }

        foreach (self::WRAP as $name => $wrap) {
            if ($xmlReader->elementExists('wp:wrap' . ucfirst($name), $frame)) {
                $style['wrap'] = $wrap;
            }
        }
        if (!isset($style['wrap'])) {
            $behind = in_array($frame->getAttribute('behindDoc'), ['1', 'true', 'on'], true);
            $style['wrap'] = $behind ? Frame::WRAP_BEHIND : Frame::WRAP_INFRONT;
        }

        return $style;
    }

    /**
     * Alignment and reference of the position, left out when Style\Frame does not know the value.
     *
     * @return array<string, string>
     */
    private static function position(string $hPos, string $hPosRelTo, string $vPos, string $vPosRelTo): array
    {
        $style = [];
        if (in_array($hPos, self::H_POS, true)) {
            $style['hPos'] = $hPos;
        }
        if (isset(self::H_POS_REL_TO[$hPosRelTo])) {
            $style['hPosRelTo'] = self::H_POS_REL_TO[$hPosRelTo];
        }
        if (in_array($vPos, self::V_POS, true)) {
            $style['vPos'] = $vPos;
        }
        if (isset(self::V_POS_REL_TO[$vPosRelTo])) {
            $style['vPosRelTo'] = self::V_POS_REL_TO[$vPosRelTo];
        }

        return $style;
    }

    /**
     * A VML length in points; a number without a unit is in pixels.
     */
    private static function cssToPoint(string $value): ?float
    {
        if (!preg_match('/^([+-]?)(\d*\.?\d+)(pt|px|in|cm|mm|pc)?$/', $value, $matches)) {
            return null;
        }
        $points = (float) Converter::cssToPoint($matches[2] . (empty($matches[3]) ? 'px' : $matches[3]));

        return $matches[1] === '-' ? -$points : $points;
    }
}
