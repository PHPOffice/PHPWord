<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

/**
 * Title element
 */
class Title
{
    /**
     * Title Text content
     *
     * @var string
     */
    private $_text;

    /**
     * Title depth
     *
     * @var int
     */
    private $_depth;

    /**
     * Title anchor
     *
     * @var int
     */
    private $_anchor;

    /**
     * Title Bookmark ID
     *
     * @var int
     */
    private $_bookmarkId;

    /**
     * Title style
     *
     * @var string
     */
    private $_style;


    /**
     * Create a new Title Element
     *
     * @param string $text
     * @param int $depth
     * @param mixed $style
     */
    public function __construct($text, $depth = 1, $style = null)
    {
        if (!is_null($style)) {
            $this->_style = $style;
        }

        $this->_text = $text;
        $this->_depth = $depth;

        return $this;
    }

    /**
     * Set Anchor
     *
     * @param int $anchor
     */
    public function setAnchor($anchor)
    {
        $this->_anchor = $anchor;
    }

    /**
     * Get Anchor
     *
     * @return int
     */
    public function getAnchor()
    {
        return $this->_anchor;
    }

    /**
     * Set Bookmark ID
     *
     * @param int $bookmarkId
     */
    public function setBookmarkId($bookmarkId)
    {
        $this->_bookmarkId = $bookmarkId;
    }

    /**
     * Get Anchor
     *
     * @return int
     */
    public function getBookmarkId()
    {
        return $this->_bookmarkId;
    }

    /**
     * Get Title Text content
     *
     * @return string
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     * Get Title style
     *
     * @return string
     */
    public function getStyle()
    {
        return $this->_style;
    }
}
