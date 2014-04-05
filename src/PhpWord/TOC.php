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
    private static $titles = array();

    /**
     * TOC style
     *
     * @var TOCStyle
     */
    private static $TOCStyle;

    /**
     * Font style
     *
     * @var Font|array|string
     */
    private static $fontStyle;

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
     * Min title depth to show
     *
     * @var int
     */
    private $minDepth = 1;

    /**
     * Max title depth to show
     *
     * @var int
     */
    private $maxDepth = 9;


    /**
     * Create a new Table-of-Contents Element
     *
     * @param mixed $styleFont
     * @param array $styleTOC
     */
    public function __construct($styleFont = null, $styleTOC = null, $minDepth = 1, $maxDepth = 9)
    {
        self::$TOCStyle = new TOCStyle();

        if (!is_null($styleTOC) && is_array($styleTOC)) {
            foreach ($styleTOC as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                self::$TOCStyle->setStyleValue($key, $value);
            }
        }

        if (!is_null($styleFont)) {
            if (is_array($styleFont)) {
                self::$fontStyle = new Font();
                foreach ($styleFont as $key => $value) {
                    if (substr($key, 0, 1) != '_') {
                        $key = '_' . $key;
                    }
                    self::$fontStyle->setStyleValue($key, $value);
                }
            } else {
                self::$fontStyle = $styleFont;
            }
        }

        $this->minDepth = $minDepth;
        $this->maxDepth = $maxDepth;
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
    public function getTitles()
    {
        $titles = self::$titles;
        foreach ($titles as $i => $title) {
            if ($this->minDepth > $title['depth']) {
                unset($titles[$i]);
            }
            if (($this->maxDepth != 0) && ($this->maxDepth < $title['depth'])) {
                unset($titles[$i]);
            }
        }
        $titles = array_merge(array(), $titles);

        return $titles;
    }

    /**
     * Reset footnotes
     */
    public static function reset()
    {
        self::$titles = array();
    }

    /**
     * Get TOC Style
     *
     * @return TOCStyle
     */
    public static function getStyleTOC()
    {
        return self::$TOCStyle;
    }

    /**
     * Get Font Style
     *
     * @return Font
     */
    public static function getStyleFont()
    {
        return self::$fontStyle;
    }

    /**
     * Set max depth
     *
     * @param int $value
     */
    public function setMaxDepth($value)
    {
        $this->maxDepth = $value;
    }

    /**
     * Get Max Depth
     *
     * @return int Max depth of titles
     */
    public function getMaxDepth()
    {
        return $this->maxDepth;
    }

    /**
     * Set min depth
     *
     * @param int $value
     */
    public function setMinDepth($value)
    {
        $this->minDepth = $value;
    }

    /**
     * Get Min Depth
     *
     * @return int Min depth of titles
     */
    public function getMinDepth()
    {
        return $this->minDepth;
    }
}
