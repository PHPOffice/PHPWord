<?php
/**
 * PHPWord
 *
 * Copyright (c) 2014 PHPWord
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
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.8.0
 */


/**
 * PHPWord_Footnote
 */
class PHPWord_Footnote
{

    /**
     * Footnote Elements
     *
     * @var array
     */
    private static $_footnoteCollection = array();

    /**
     * Footnote Link Elements
     *
     * @var array
     */
    private static $_footnoteLink = array();

    /**
     * Add new Footnote Element
     *
     * @param string $linkSrc
     * @param string $linkName
     *
     * @return mixed
     */
    public static function addFootnoteElement(PHPWord_Section_Footnote $footnote)
    {
        $refID = self::countFootnoteElements() + 2;

        self::$_footnoteCollection[] = $footnote;

        return $refID;
    }

    /**
     * Get Footnote Elements
     *
     * @return array
     */
    public static function getFootnoteElements()
    {
        return self::$_footnoteCollection;
    }

    /**
     * Get Footnote Elements Count
     *
     * @return int
     */
    public static function countFootnoteElements()
    {
        return count(self::$_footnoteCollection);
    }

    /**
     * Add new Footnote Link Element
     *
     * @param string $src
     * @param string $type
     *
     * @return mixed
     */
    public static function addFootnoteLinkElement($linkSrc)
    {
        $rID = self::countFootnoteLinkElements() + 1;

        $link = array();
        $link['target'] = $linkSrc;
        $link['rID'] = $rID;
        $link['type'] = 'hyperlink';

        self::$_footnoteLink[] = $link;

        return $rID;
    }

    /**
     * Get Footnote Link Elements
     *
     * @return array
     */
    public static function getFootnoteLinkElements()
    {
        return self::$_footnoteLink;
    }

    /**
     * Get Footnote Link Elements Count
     *
     * @return int
     */
    public static function countFootnoteLinkElements()
    {
        return count(self::$_footnoteLink);
    }
}
