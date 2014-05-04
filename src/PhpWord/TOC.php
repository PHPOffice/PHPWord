<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord;

/**
 * Table of contents
 */
class TOC
{
    /**
     * Title elements
     *
     * @var array
     */
    private static $titles = array();

    /**
     * Title anchor
     *
     * @var int
     */
    private static $anchor = 252634154;

    /**
     * Title bookmark
     *
     * @var int
     */
    private static $bookmarkId = 0;

    /**
     * Add a Title
     *
     * @param string $text
     * @param int $depth
     * @return array
     */
    public static function addTitle($text, $depth = 0)
    {
        $anchor = '_Toc' . ++self::$anchor;
        $bookmarkId = self::$bookmarkId++;

        $title = array();
        $title['text'] = $text;
        $title['depth'] = $depth;
        $title['anchor'] = $anchor;
        $title['bookmarkId'] = $bookmarkId;

        self::$titles[] = $title;

        return array($anchor, $bookmarkId);
    }

    /**
     * Get all titles
     *
     * @return array
     */
    public static function getTitles()
    {
        return self::$titles;
    }

    /**
     * Reset titles
     */
    public static function resetTitles()
    {
        self::$titles = array();
    }
}
