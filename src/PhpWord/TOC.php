<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\TOC as TOCStyle;

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
    private static $_titles = array();

    /**
     * TOC style
     *
     * @var TOCStyle
     */
    private static $_styleTOC;

    /**
     * Font style
     *
     * @var Font|array|string
     */
    private static $_styleFont;

    /**
     * Title anchor
     *
     * @var int
     */
    private static $_anchor = 252634154;

    /**
     * Title bookmark
     *
     * @var int
     */
    private static $_bookmarkId = 0;


    /**
     * Create a new Table-of-Contents Element
     *
     * @param mixed $styleFont
     * @param array $styleTOC
     */
    public function __construct($styleFont = null, $styleTOC = null)
    {
        self::$_styleTOC = new TOCStyle();

        if (!is_null($styleTOC) && is_array($styleTOC)) {
            foreach ($styleTOC as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                self::$_styleTOC->setStyleValue($key, $value);
            }
        }

        if (!is_null($styleFont)) {
            if (is_array($styleFont)) {
                self::$_styleFont = new Font();
                foreach ($styleFont as $key => $value) {
                    if (substr($key, 0, 1) != '_') {
                        $key = '_' . $key;
                    }
                    self::$_styleFont->setStyleValue($key, $value);
                }
            } else {
                self::$_styleFont = $styleFont;
            }
        }
    }

    /**
     * Add a Title
     *
     * @param string $text
     * @param int $depth
     * @return array
     */
    public static function addTitle($text, $depth = 0)
    {
        $anchor = '_Toc' . ++self::$_anchor;
        $bookmarkId = self::$_bookmarkId++;

        $title = array();
        $title['text'] = $text;
        $title['depth'] = $depth;
        $title['anchor'] = $anchor;
        $title['bookmarkId'] = $bookmarkId;

        self::$_titles[] = $title;

        return array($anchor, $bookmarkId);
    }

    /**
     * Get all titles
     *
     * @return array
     */
    public static function getTitles()
    {
        return self::$_titles;
    }

    /**
     * Get TOC Style
     *
     * @return TOCStyle
     */
    public static function getStyleTOC()
    {
        return self::$_styleTOC;
    }

    /**
     * Get Font Style
     *
     * @return Font
     */
    public static function getStyleFont()
    {
        return self::$_styleFont;
    }
}
